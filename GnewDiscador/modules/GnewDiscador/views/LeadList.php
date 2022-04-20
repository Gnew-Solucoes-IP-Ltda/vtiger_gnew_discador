<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

/* Include dependency required for using Server API */
include_once 'include/Webservices/Query.php';

class GnewDiscador_LeadList_View extends Vtiger_Index_View
{

	protected function redis_connect()
	{
		$this->redis = new Redis();
		$this->redis->connect('localhost', 6379);
	}

	protected function obter_dados_pabx()
	{
		if ($this->redis->exists('gnew_discador_config')) {
			return json_decode($this->redis->get('gnew_discador_config'), TRUE);
		}
		else {
			return NULL;
		}
	}

	protected function atualizar_dados_usuario($usuario, $lead, $contato){
		if ($this->redis->exists('gnew_discador_user_dados_'.$usuario->user_name)){
			$dados_usuario = json_decode(
				$this->redis->get('gnew_discador_user_dados_'.$usuario->user_name),
				TRUE
			);
		}
		else{
			$dados_usuario = array(
				'id' => $usuario->id,
				'nome' => $usuario->first_name.' '.$usuario->last_name,
				'ligacoes' => 0
			);
		}
		
		$data = new DateTime();
		$dados_usuario['data_requisicao'] = $data->format('d/m/Y H:i:s');
		$dados_usuario['lead'] = array(
			'leadid' => $lead['leadid'],
			'nome_completo' => $lead['firstname'].' '.$lead['lastname'],
			'telefone_discado' => $lead[$contato]
		);
		$dados_usuario['campaign'] = array(
			'campaignid' => $lead['campaign']['campaignid'],
			'nome' => $lead['campaign']['campaignname']
		);
		$dados_usuario['ligacoes'] ++;
		$this->redis->set(
			'gnew_discador_user_dados_'.$usuario->user_name,
			json_encode($dados_usuario)
		);
	}

	protected function solicitar_discagem($destino, $extensao, $username){
		//Tratando intervalo entre requisicoes de discagem
		//obtendo requisicao anterior
		$requisicao_atual = new DateTime();

		if ($this->redis->exists('gnew_discador_ultima_requisicao_'.$username)){
			$ultima_requisicao = new Datetime(
				$this->redis->get(
					'gnew_discador_ultima_requisicao_'.$username
				)
			);
			$intervalo = $requisicao_atual->diff($ultima_requisicao);

			if (
				$intervalo->s <= 5 &&
				$intevalo->i == 0 &&
				$intevalo->h == 0
			){
				return array(
					'msg' => "Aguarde enquanto processamos sua última requisição!",
					'resposta' => NULL,
					'sucesso' => FALSE
				);
			}
		}
		
		$this->redis->set(
			'gnew_discador_ultima_requisicao_'.$username,
			$requisicao_atual->format('Y-m-d H:i:s')
		);
		$dados_pabx = $this->obter_dados_pabx();
		$params=[
			'pk_enc' => $dados_pabx['pk_enc'], 
			'telefone' => $destino, 
			'extensao' => $extensao
		];
		$headers = [
			'Content-Type:application/json',
			'Authorization:Token '.$dados_pabx['token']
		];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $dados_pabx['url']);
		curl_setopt($ch, CURLOPT_POST, True);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$resultado = curl_exec($ch);
		curl_close($ch);
		
		if(curl_error($ch)) {
			return array(
				'msg' => curl_error($ch),
				'resposta' => json_decode($resultado, TRUE),
				'sucesso' => FALSE
			);
		}
		else{
			return array(
				'msg' => 'Sucesso na requisição!',
				'resposta' => json_decode($resultado, TRUE),
				'sucesso' => TRUE
			);
		}
	}

	protected function solicitar_lead($username, $campanha)
	{
		$tentativas = 5;
		
		if ($this->redis->exists('gnew_discador_user_' . $username)) {
			// $this->redis->delete('gnew_discador_user_' . $username);
			return json_decode($this->redis->get('gnew_discador_user_' . $username), TRUE);
		}

		if (!$this->redis->exists('fila_pedido_leads')) {
			$fila_pedidos_leads = array();
		}

		$fila_pedidos_leads = json_decode(
			$this->redis->get('fila_pedido_leads'),
			TRUE
		);
		$fila_pedidos_leads[$username] = array(
			'campanha' => $campanha
		);
		$this->redis->set(
			'fila_pedido_leads',
			json_encode($fila_pedidos_leads)
		);

		while(!$this->redis->exists('gnew_discador_user_' . $username)){
			sleep(1);
			
			if ($this->redis->exists('gnew_discador_user_' . $username)){
				return json_decode($this->redis->get('gnew_discador_user_' . $username), TRUE);
			}

			if ($tentativas <= 0){
				break;
			}
			$tentativas --;

		}

		return NULL;
	}

	public function get_lead_status(){
		return array(
			array(
				'id' =>2,
				'value' => 'Attempted to Contact',
				'label' => 'Tentativa Contato'
			),
			array(
				'id' =>3, 
				'value' => 'Cold',
				'label'=> 'Frio'
			),
			array(
				'id' =>4, 
				'value' => 'Contact in Future',
				'label'=> 'Contactar no Futuro'
			),
			array(
				'id' =>5, 
				'value' => 'Contacted',
				'label' => 'Contactado'
			),
			array(
				'id' =>6, 
				'value' => 'Hot',
				'label' => 'Quente'
			),
			array(
				'id' =>7, 
				'value' => 'Junk Lead',
				'label' => 'Descartado'
			),
			array(
				'id' =>8,
				'value' => 'Lost Lead', 
				'label' => 'Perdido'
			),
			array(
				'id' =>9, 
				'value' => 'Not Contacted',
				'label' => 'Não Contactado'
			),
			array(
				'id' =>10,
				'value' => 'Pre Qualified', 
				'label' => 'Pré Qualificado'
			),
			array(
				'id' =>11, 
				'value' => 'Qualified',
				'label' => 'Qualificado'
			),
			array(
				'id' =>12, 
				'value' => 'Warm',
				'label' => 'Morno'
			),
		);
	}

	protected function obter_proximo_contato($lead, $contato_atual){
		switch ($contato_atual){
			case 'phone':
				if ($lead['mobile'] != NULL){
					return 'mobile';
				}
				elseif($lead['fax'] != NULL){
					return 'fax';
				}
				break;
			
			case 'mobile':
				if ($lead['fax'] != NULL){
					return 'fax';
				}
				elseif($lead['phone'] != NULL){
					return 'phone';
				}
				break;

			case 'fax':
				if ($lead['phone'] != NULL){
					return 'phone';
				}
				elseif($lead['mobile'] != NULL){
					return 'mobile';
				}

				break;
		}

		return NULL;
	}

	public function process(Vtiger_Request $request)
	{
		$this->redis_connect();
		// Current User Context	
		$userContext = vglobal('current_user');
		$viewer = $this->getViewer($request);
		$lead = NULL;
		$mensagem = NULL;

		if ($request->has('campaign')) {
			// Enviando pedido de lead para fila
			$campanha = $request->get('campaign');
			$lead = $this->solicitar_lead($userContext->user_name, $campanha);
			$contatos_validos = array('phone', 'mobile', 'fax');
			$contato = 'phone';

			// Verificando o contato que esta selecionado para a discagem atual
			if ($lead){
				if ($request->has('contato')) {
					if (in_array($request->get('contato'), $contatos_validos)){
						$contato = $request->get('contato');
						$resultado_discagem = $this->solicitar_discagem(
							$lead[$contato],
							$userContext->phone_crm_extension,
							$userContext->user_name
						);
						$this->atualizar_dados_usuario($userContext, $lead, $contato);
					}
				}
			}
		}
		$viewer->assign('EXTENSION', $userContext->phone_crm_extension);
		$viewer->assign('LEAD', $lead);
		$viewer->assign('LEADSTATUS', $this->get_lead_status());
		$viewer->assign('CAMPAIGN', $campanha);
		$viewer->assign('CONTATO', $contato);
		$viewer->assign('PROXIMOCONTATO', $this->obter_proximo_contato($lead, $contato));
		$viewer->assign('RESULTADO', $resultado_discagem);
		$viewer->view('LeadListViewContents.tpl', $request->getModule());
	}
}
