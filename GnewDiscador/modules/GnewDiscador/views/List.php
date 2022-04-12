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

class GnewDiscador_List_View extends Vtiger_Index_View {

	public function process(Vtiger_Request $request) {

		// Current User Context	
		$userContext = vglobal('current_user');
		$viewer = $this->getViewer($request);
		$query = "SELECT campaign_no, campaignname FROM Campaigns;";
		$records = vtws_query($query, $userContext);
		$viewer->assign('RECORDS', $records);
		$viewer->view('ListViewContents.tpl', $request->getModule());
	}
}