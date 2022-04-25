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
<div class="main-container clearfix">
	<div class="editViewPageDiv viewContent">
		<div class="col-sm-12 col-xs-12 content-area ">
			<div class="editViewHeader">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-lg-pull-0">
						<h4 class="editHeader" style="margin-top:5px;">Não existem campanhas cadastradas!</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
	<a class="btn btn-default" href="index.php?module=GnewDiscador&view=Config&viewname=&app=TOOLS" style="margin-top:10px; margin-left:10px;">Configurações gerais</a>
</div>
{elseif !$EXTENSION}
<div class="main-container clearfix">
	<div class="editViewPageDiv viewContent">
		<div class="col-sm-12 col-xs-12 content-area ">
			<div class="editViewHeader">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-lg-pull-0">
						<h4 class="editHeader" style="margin-top:5px;">O seu usuário não possui uma extensão associada!</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>		
{else}
<div class="main-container clearfix">
	<div class="editViewPageDiv viewContent">
		<div class="col-sm-12 col-xs-12 content-area ">
			<form class="form-horizontal recordEditView" id="EditView" name="edit" method="get" action="index.php" enctype="multipart/form-data" novalidate="novalidate">
				<div class="editViewHeader">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-lg-pull-0">
							<a class="btn btn-primary" href="index.php?module=GnewDiscador&view=DadosDiscador&viewname=&app=TOOLS">Dados</a>
							<a class="btn btn-default" href="index.php?module=GnewDiscador&view=Config&viewname=&app=TOOLS">Configurações gerais</a>
						</div>
					</div>
				</div>
				<div class="editViewBody">
					<div class="editViewContents">
						<input type="hidden" name="module" value="GnewDiscador">
						<input type="hidden" name="view" value="LeadList"> 
						<input type="hidden" name="app" value="TOOLS">
						<input type="hidden" name="contato" value="phone">

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
						</div>
					</div>
				</div>
				<div class="modal-overlay-footer clearfix">
					<div class="row clearfix">
						<div class="textAlignCenter col-lg-12 col-md-12 col-sm-12 ">
							<button type="submit" class="btn btn-primary saveButton">Iniciar</button>&nbsp;&nbsp;
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

{/if}


