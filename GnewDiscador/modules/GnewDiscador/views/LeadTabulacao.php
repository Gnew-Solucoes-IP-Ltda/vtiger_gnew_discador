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

class GnewDiscador_LeadTabulacao_View extends Vtiger_Index_View {

	protected function redis_connect()
	{
		$this->redis = new Redis();
		$this->redis->connect('localhost', 6379);
	}

	protected function tabular_lead($username, $leadid, $leadstatus)
	{
		$this->redis_connect();
		$tentativas = 5;
		$this->redis->set(
			'gnew_discador_tabulacao_'.$username,
			json_encode(
				array(
					'leadid' => $leadid,
					'leadstatus' => $leadstatus
				)
			)
		);
	
	}

	public function process(Vtiger_Request $request) {

		// Current User Context	
		$userContext = vglobal('current_user');
		$viewer = $this->getViewer($request);
		$campanha = NULL;
		$lead = NULL;
		$lead_status = NULL;

		if (
			$request->has('campaign') &&
			$request->has('leadid') &&
			$request->has('leadstatus')
		) {

			$campanha = $request->get('campaign');
			$leadid = $request->get('leadid');
			$lead_status = $request->get('leadstatus');
			$this->tabular_lead(
				$userContext->user_name ,
				$leadid, 
				$lead_status
			);
			echo "Tabulado";
		}

		echo "Não tabulado";
	}
}