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

class GnewDiscador_LeadList_View extends Vtiger_Index_View{

	public function __construct() {
		$this->contato = NULL;
	}

	public function process(Vtiger_Request $request){
		$this->redisConnect();
		$userContext = vglobal('current_user');
		$viewer = $this->getViewer($request);
		$extension = $userContext->phone_crm_extension;
		$this->contato = 'phone';
		$lead = $this->getLead($userContext, $request);
		$campanha = $request->get('campaign');

		if($extension == '' || is_null($extension)){

			$viewer->view('Campaign/NotExistsExtension.tpl', $request->getModule());

		} elseif (is_null($lead)){

			$viewer->view('Lead/LeadNotExists.tpl', $request->getModule());

		} else {
			$proximo_contato = $this->getNextContact($lead);
			$viewer->assign('ADMIN', $userContext->is_admin);
			$viewer->assign('LEAD', $lead);
			$viewer->assign('LEADSTATUS', $this->getLeadStatus());
			$viewer->assign('CAMPAIGN', $campanha);
			$viewer->assign('CONTATO', $this->contato);
			$viewer->assign('PROXIMOCONTATO', $proximo_contato);
			$viewer->assign('RESULTADO', $resultado_discagem);
			$viewer->view('Lead/Lead.tpl', $request->getModule());
		}
	}

	protected function getLead($userContext, $request){
		$lead = NULL;

		if ($request->has('campaign')) {
			// Enviando pedido de lead para fila
			$campanha = $request->get('campaign');
			$lead = $this->getLeadData($userContext->user_name, $campanha);
			$contatos_validos = array('phone', 'mobile', 'fax');

			// Verificando o contato que esta selecionado para a discagem atual
			if ($lead){
				if ($request->has('contato')) {
					if (in_array($request->get('contato'), $contatos_validos)){
						$this->contato = $request->get('contato');
						$resultado_discagem = $this->getDial(
							$lead[$this->contato],
							$userContext->phone_crm_extension,
							$userContext->user_name
						);
						$this->updateUserData($userContext, $lead, $this->contato);
					}
				}
			}
		}

		return $lead;
	}

	protected function getPabxData(){
		if ($this->redis->exists('gnew_discador_config')) {
			return json_decode($this->redis->get('gnew_discador_config'), TRUE);
		}
		else {
			return NULL;
		}
	}

	protected function updateUserData($usuario, $lead, $contato){
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

		$timezone = new DateTimeZone($usuario->time_zone);
		$data = new DateTime('now', $timezone);
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

	protected function getDial($destino, $extensao, $username){
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
		$dados_pabx = $this->getPabxData();

		if ($dados_pabx['versao'] == 'checked'){
			// API GNEW 2.0
			$url = $dados_pabx['url'];
			$params='password='.$dados_pabx['token'].'&phone='.$destino.'&exten='.$extensao;
			$headers = [
				'Content-Type:application/x-www-form-urlencoded'
			];
		}else{
			// API GNEW 3.5 
			$url = $dados_pabx['url'];
			$params=json_encode(
				[
					'pk_enc' => $dados_pabx['pk_enc'], 
					'telefone' => $destino, 
					'extensao' => $extensao
				]
			);
			$headers = [
				'Content-Type:application/json',
				'Authorization:Token '.$dados_pabx['token']
			];
		}

		if ($dados_pabx['ignorar_ssl'] == 'checked'){
			$ignorar_ssl = FALSE;
		}
		else{
			$ignorar_ssl = TRUE;
		} 

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $ignorar_ssl);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ignorar_ssl);
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

	protected function getLeadData($username, $campanha){
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

	public function getLeadStatus(){

		if (!$this->redis->exists('lead_status')){
			$lead_status = array();
		} else {
			$lead_status = json_decode(
				$this->redis->get('lead_status'),
				TRUE
			);
		}
		return $lead_status;
	}

	protected function getNextContact($lead){

		switch ($this->contato){
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

	protected function redisConnect(){
		$this->redis = new Redis();
		$this->redis->connect('localhost', 6379);
	}
}
