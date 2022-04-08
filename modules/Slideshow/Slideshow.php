<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Slideshow {

	public function vtlib_handler($moduleName, $eventType) {
		if ($eventType == 'module.postinstall') {
			$this->_registerLinks($moduleName);
		} else if ($eventType == 'module.enabled') {
			$this->_registerLinks($moduleName);
		} else if ($eventType == 'module.disabled') {
			$this->_deregisterLinks($moduleName);
		}
	}

	protected function _registerLinks($moduleName) {
		$thisModuleInstance = Vtiger_Module::getInstance($moduleName);
		if ($thisModuleInstance) {
			$thisModuleInstance->addLink("HEADERSCRIPT", "SlideshowShared", "modules/Slideshow/js/SlideshowShared.js");
			
			$leadsModuleInstance = Vtiger_Module::getInstance('Leads');
			$leadsModuleInstance->addLink("LISTVIEW", "Slideshow", 'javascript:Slideshow.viewSelected();');
			
			$contactsModuleInstance = Vtiger_Module::getInstance('Contacts');
			$contactsModuleInstance->addLink("LISTVIEW", "Slideshow", 'javascript:Slideshow.viewSelected();');
		}
	}

	protected function _deregisterLinks($moduleName) {
		$thisModuleInstance = Vtiger_Module::getInstance($moduleName);
		if ($thisModuleInstance) {
			$thisModuleInstance->deleteLink("HEADERSCRIPT", "SlideshowShared", "modules/Slideshow/js/SlideshowShared.js");
			
			$leadsModuleInstance = Vtiger_Module::getInstance('Leads');
			$leadsModuleInstance->deleteLink("LISTVIEW", "Slideshow");

			$contactsModuleInstance = Vtiger_Module::getInstance('Contacts');
			$contactsModuleInstance->deleteLink("LISTVIEW", "Slideshow");
		}
	}
}