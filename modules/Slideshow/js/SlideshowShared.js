/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class('Slideshow', {
	viewSelected: function() {
		var listView    = Vtiger_List_Js.getInstance();
		var nothingSelected = listView.checkListRecordSelected();
		if (nothingSelected) {
			alert('Nothing selected!');
		} else {
			var selectedIds = listView.readSelectedIds(false);
			var excludedIds = listView.readExcludedIds(false);
			if (selectedIds.length) {
				window.open("index.php?module=Slideshow&view=List&ids="+JSON.stringify(selectedIds)+"&src_module="+app.getModuleName());
			}
		}
	}
}, {});