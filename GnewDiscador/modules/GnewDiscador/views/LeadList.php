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
			$this->redis->delete('gnew_discador_user_' . $username);
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

	public function process(Vtiger_Request $request)
	{
		// Current User Context	
		$userContext = vglobal('current_user');
		$viewer = $this->getViewer($request);
		$lead = NULL;

		if ($request->has('campaign')) {
			// Enviando pedido de lead para fila
			$campanha = $srcModule = $request->get('campaign');
			$lead = $this->solicitar_lead($userContext->user_name, $campanha);
	
		}
		$viewer->assign('LEAD', $lead);
		$viewer->view('LeadListViewContents.tpl', $request->getModule());
	}
}
