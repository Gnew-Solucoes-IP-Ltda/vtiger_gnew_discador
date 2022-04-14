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

	protected function solicitar_lead($username, $campanha)
	{
		$this->redis_connect();
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
		// Current User Context	
		$userContext = vglobal('current_user');
		$viewer = $this->getViewer($request);
		$lead = NULL;

		if ($request->has('campaign')) {
			// Enviando pedido de lead para fila
			$campanha = $request->get('campaign');
			$lead = $this->solicitar_lead($userContext->user_name, $campanha);
			$contatos_validos = array('phone', 'mobile', 'fax');
			$contato = 'phone';

			// Verificando o contato que esta selecionado para a discagem atual
			if ($request->has('contato')) {
				if (in_array($request->get('contato'), $contatos_validos)){
					$contato = $request->get('contato');
				}
			}
		}
		$viewer->assign('LEAD', $lead);
		$viewer->assign('LEADSTATUS', $this->get_lead_status());
		$viewer->assign('CAMPAIGN', $campanha);
		$viewer->assign('CONTATO', $contato);
		$viewer->assign('PROXIMOCONTATO', $this->obter_proximo_contato($lead, $contato));
		$viewer->view('LeadListViewContents.tpl', $request->getModule());
	}
}
