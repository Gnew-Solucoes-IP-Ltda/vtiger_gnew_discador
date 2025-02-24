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
		$userContext = vglobal('current_user');
		$viewer = $this->getViewer($request);
		$extension = $userContext->phone_crm_extension;
		$records = $this->getCampaigns($userContext);

		if($extension == '' || is_null($extension)){

			$viewer->view('Campaign/NotExistsExtension.tpl', $request->getModule());

		}elseif (count($records) == 0){

			$viewer->view('Campaign/NotExistsCampaign.tpl', $request->getModule());

		} else {

			$viewer->assign('RECORDS', $records);
			$viewer->assign('EXTENSION', $userContext->phone_crm_extension);
			$viewer->view('Campaign/SelectCampaing.tpl', $request->getModule());

		}
	}

	protected function getCampaigns($userContext){
		$query = "SELECT campaign_no, campaignname FROM Campaigns;";
		$records = vtws_query($query, $userContext);
		return $records;
	}
}