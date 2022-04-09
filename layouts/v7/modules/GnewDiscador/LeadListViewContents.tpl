{*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************}

<br>
{if !$RECORDS}
	<center><h1>Discador GNEW<h1>
	<h4>Não existem campanhas cadastradas!</h4></center>
{else}
<div class="col-sm-12 col-xs-12 content-area ">
	<form class="form-horizontal recordEditView" id="EditView" name="edit" method="get" action="index.php" enctype="multipart/form-data" novalidate="novalidate">
		<div class="editViewHeader">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-lg-pull-0">
					<h4 class="editHeader" style="margin-top:5px;">
						Escolha de campanha
					</h4>
				</div>
			</div>
		</div>
		<div class="editViewBody">
			<div class="editViewContents">
				<input type="hidden" name="module" value="GnewDiscador">
				<input type="hidden" name="view" value="List"> 
				<input type="hidden" name="app" value="TOOLS">

				<div name="editContent">
					<div class="fieldBlockContainer">
						<h4 class="fieldBlockHeader">Campanha</h4>
						<hr>
						<table class="table table-borderless">
							<tbody>
								<tr>
									<td class="fieldLabel alignMiddle">Escolha uma campanha&nbsp;</td>
									<td class="fieldValue">
										<select data-fieldname="campaign" data-fieldtype="picklist" class="inputElement select2  select2-offscreen" type="picklist" name="campaign" data-selected-value="" tabindex="-1" title="">
											{foreach item=RECORD from=$RECORDS}
												<option value="{$RECORD.campaign_no}">{$RECORD.campaignname}</option>
											{/foreach}
										</select>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="textAlignCenter col-lg-12 col-md-12 col-sm-12 ">
						<button type="submit" class="btn btn-primary saveButton">Pesquisar</button>&nbsp;&nbsp;
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

{/if}
