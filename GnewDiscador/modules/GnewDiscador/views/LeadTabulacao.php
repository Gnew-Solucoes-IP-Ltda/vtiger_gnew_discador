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
// include_once 'include/Webservices/Revise.php';
include_once 'include/Webservices/Query.php';


class GnewDiscador_LeadTabulacao_View extends Vtiger_Index_View {

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

			// try {
			// 	$wsid = vtws_getWebserviceEntityId('Leads', $leadid); // Module_Webservice_ID x CRM_ID
			// 	$data = array('firstname' => 'FIRSTNAME', 'id' => $wsid);
			// 	$lead = vtws_revise($data, $current_user);
			// 	print_r($lead);
		
			// } catch (WebServiceException $ex) {
			// 		echo $ex->getMessage();
			// }
			$query = "UPDATE Leads SET firstname='teste';";
			$records = vtws_query($query, $current_user);
			echo "teste";
			var_dump($records);
		}

		// $viewer->assign('RECORDS', $records);
		// $viewer->view('LeadTabulacaoViewContents.tpl', $request->getModule());
	}
}