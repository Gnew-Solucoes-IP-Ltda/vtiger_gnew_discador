{*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************}

<br>
{if !$LEAD}
<div class="main-container clearfix">
	<div class="editViewPageDiv viewContent">
		<div class="col-sm-12 col-xs-12 content-area ">
			<div class="editViewHeader">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-lg-pull-0">
						<h4 class="editHeader" style="margin-top:5px;">Não existem leads cadastrados!</h4>
					</div>
				</div>
			</div>
			<div class="modal-overlay-footer clearfix">
				<div class="row clearfix">
				</div>
			</div>
		</div>
	</div>
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
<div class="container-fluid main-container">
	<div class="row">
		<div class="detailViewContainer viewContent clearfix">
			<div class="col-sm-12 col-xs-12 content-area ">
				<!-- Inicio da parte de dados do lead -->
				<div class=" detailview-header-block">
					<div class="detailview-header">
						<div class="row">
							<div class="col-sm-6 col-lg-6 col-md-6">
								<div class="record-header clearfix">
									<div class="hidden-sm hidden-xs recordImage bgleads app-MARKETING">
										<div class="name">
											<span><strong><i class="vicon-leads" title="Leads"></i></strong></span>
										</div>
									</div>
									<div class="recordBasicInfo">
										<div class="info-row">
											<h4>
												<span class="recordLabel pushDown">
													<span class="firstname">{$LEAD.firstname}</span>&nbsp;
													<span class="lastname">{$LEAD.lastname}</span>
												</span>
											</h4>
										</div>
										<div class="info-row">
											<div class="col-lg-7 fieldLabel">
												<span class="phone value" title="Telefone : {$LEAD.phone}">
													<a class="phoneField" data-value="{$LEAD.phone}" record="{$LEAD.leadid}" onclick="Vtiger_PBXManager_Js.registerPBXOutboundCall('{$LEAD.phone}',{$LEAD.leadid})">
														{$LEAD.phone} {if $CONTATO == 'phone'}<i class="fa fa-arrow-left"></i>{/if}
													</a>
												</span>
											</div>
										</div>
										{if $LEAD.mobile}
										<div class="info-row">
											<div class="col-lg-7 fieldLabel">
												<span class="phone value" title="Telefone : {$LEAD.mobile}">
													<a class="phoneField" data-value="{$LEAD.mobile}" record="{$LEAD.leadid}" onclick="Vtiger_PBXManager_Js.registerPBXOutboundCall('{$LEAD.mobile}',{$LEAD.leadid})">
														{$LEAD.mobile} {if $CONTATO == 'mobile'}<i class="fa fa-arrow-left"></i>{/if}
													</a>
												</span>
											</div>
										</div>
										{/if}
										{if $LEAD.fax}
										<div class="info-row">
											<div class="col-lg-7 fieldLabel">
												<span class="phone value" title="Telefone : {$LEAD.fax}">
													<a class="phoneField" data-value="{$LEAD.fax}" record="{$LEAD.leadid}" onclick="Vtiger_PBXManager_Js.registerPBXOutboundCall('{$LEAD.fax}',{$LEAD.leadid})">
														{$LEAD.fax} {if $CONTATO == 'fax'}<i class="fa fa-arrow-left"></i>{/if}
													</a>
												</span>
											</div>
										</div>
										{/if}
										{if $LEAD.email}
										<div class="info-row">
											<div class="col-lg-7 fieldLabel">
												<span class="email value" title="E-mail : {$LEAD.email}">
													<a class="emailField cursorPointer" onclick="Vtiger_Helper_Js.getInternalMailer({$LEAD.leadid},'email','Leads');">
														{$LEAD.email}
													</a>
												</span>
											</div>
										</div>
										{/if}
										<div class="info-row">
											<i class="fa fa-map-marker"></i>&nbsp;
											<a class="showMap" href="javascript:void(0);" onclick="Vtiger_Index_Js.showMap(this);" data-module="Leads" data-record="{$LEAD.leadid}">
												Exibir Mapa
											</a>
										</div>
										<div class="row">
											<form method="get" action="index.php" enctype="multipart/form-data">
												<input type="hidden" name="module" value="GnewDiscador">
												<input type="hidden" name="view" value="LeadList"> 
												<input type="hidden" name="app" value="TOOLS">
												<input type="hidden" name="campaign" value="{$CAMPAIGN}">
												<input type="hidden" name="campaignid" value="{$LEAD.campaign.campaignid}">
												<button name="contato" value="{$CONTATO}" class="btn btn-default" style="margin-top:5px;">Rediscar</button>
												{if $PROXIMOCONTATO}
												<button name="contato" value="{$PROXIMOCONTATO}" class="btn btn-primary" style="margin-top:5px;">Próximo contato</button>
												{/if}
											</form>
										</div>
										<div class="row">
											<div class="col-lg-6" style="margin-top:50px;">
												<hr>
												<h4>Dados da campanha</h4>
												<p>Nome: {$LEAD.campaign.campaignname}</p>
												<p>Tipo: {$LEAD.campaign.campaigntype}</p>
												<p>Status: {$LEAD.campaign.campaignstatus}</p>
												<p>Público Alvo: {$LEAD.campaign.targetaudience}</p>
												<p>Data de encerramento: {$LEAD.campaign.closingdate}</p>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-6" style="margin-top:50px;">
												<form method="get" action="index.php" enctype="multipart/form-data">
													<input type="hidden" name="module" value="GnewDiscador">
													<input type="hidden" name="view" value="LeadTabulacao"> 
													<input type="hidden" name="app" value="TOOLS">
													<input type="hidden" name="leadid" value="{$LEAD.leadid}">
													<input type="hidden" name="campaign" value="{$CAMPAIGN}">
													<div class="form-group">
														<hr>
														<h4>Lead Status</h4>
														<select class="form-control" id="LeadStatusFormControlSelect" name="leadstatus">
															{foreach item=ITEM from=$LEADSTATUS}
																<option value="{$ITEM.value}">{$ITEM.label}</option>
															{/foreach}
														</select>
													</div>
													<button type="submit" class="btn btn-success">Finalizar</button>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 detailViewButtoncontainer">
								<div class="pull-right btn-toolbar">
									<div class="btn-group">
										<a class="btn btn-default" href="index.php?module=Leads&amp;view=Detail&amp;record={$LEAD.leadid}&amp;mode=showDetailViewByMode&amp;requestMode=summary&amp;tab_label=Lead%20Resumo&amp;app=MARKETING" target="_blank" style="margin-left:3px;margin-top:3px;">Detalhes</a>
										<a class="btn btn-default" href="index.php?module=Calendar&amp;view=Edit&amp;mode=Events&amp;parent_id={$LEAD.leadid}&amp;app=MARKETING" target="_blank" style="margin-left:3px;margin-top:3px;">Adicionar Evento</a>
										<a class="btn btn-default" href="index.php?module=Calendar&amp;view=Edit&amp;mode=Calendar&amp;parent_id={$LEAD.leadid}&amp;app=MARKETING" target="_blank" style="margin-left:3px;margin-top:3px;">Adicionar Tarefa</a>	
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- fim da parte de dados do lead -->
			</div>				
		</div>
	</div>
</div>
{/if}