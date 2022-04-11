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

class GnewDiscador_LeadList_View extends Vtiger_Index_View {

	public function process(Vtiger_Request $request) {

		// Current User Context	
		$userContext = vglobal('current_user');
		$viewer = $this->getViewer($request);
		// $query = "SELECT firstname, lastname FROM vtiger_leaddetails WHERE leadid = (
		// 	SELECT leadid FROM vtiger_campaignleadrel WHERE campaignid = (
		// 		SELECT campaignid FROM vtiger_campaign WHERE campaign_no  = 'CAM1'
		// 	)
		// );";
		$query = "SELECT * FROM Leads;";
		$records = vtws_query($query, $userContext);
		var_dump($records);
		$viewer->assign('RECORDS', $records);
		$viewer->view('LeadListViewContents.tpl', $request->getModule());
	}
}