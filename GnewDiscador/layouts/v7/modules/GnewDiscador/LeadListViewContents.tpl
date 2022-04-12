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
	<center><h1>Discador GNEW<h1>
	<h4>Não existem leads disponíveis!</h4></center>
{else}
<div class="col-sm-12 col-xs-12 content-area ">
	<div class="detailview-content container-fluid">
		<div class="col-lg-12 resizable-summary-view">
			<div class="left-block col-lg-4">
				<div class="summaryView">
					<div class="summaryViewHeader">
						<h4 class="display-inline-block">Campos Chave</h4>
					</div>
					<div class="summaryViewFields">
						<div class="recordDetails">
						<table class="summary-table no-border">
							<tbody>
								<tr class="summaryViewEntries">
									<td class="fieldLabel">
										<label class="muted textOverflowEllipsis">Lead NO</label>
									</td>
									<td class="fieldValue">
										<div class="">
											<span class="value textOverflowEllipsis">{$LEAD.lead_no}</span>
										</div>
									</td>
								</tr>
								<tr class="summaryViewEntries">
									<td class="fieldLabel">
										<label class="muted textOverflowEllipsis">Nome</label>
									</td>
									<td class="fieldValue">
										<div class="">
											<span class="value textOverflowEllipsis">{$LEAD.firstname} {$LEAD.lastname}</span>
										</div>
									</td>
								</tr>
								<tr class="summaryViewEntries">
									<td class="fieldLabel">
										<label class="muted textOverflowEllipsis">Phone</label>
									</td>
									<td class="fieldValue">
										<div class="">
											<span class="value textOverflowEllipsis">{$LEAD.phone}</span>
										</div>
									</td>
								</tr>
								<tr class="summaryViewEntries">
									<td class="fieldLabel">
										<label class="muted textOverflowEllipsis">Mobile</label>
									</td>
									<td class="fieldValue">
										<div class="">
											<span class="value textOverflowEllipsis">{$LEAD.mobile}</span>
										</div>
									</td>
								</tr>
								<tr class="summaryViewEntries">
									<td class="fieldLabel">
										<label class="muted textOverflowEllipsis">Informações lead</label>
									</td>
									<td class="fieldValue">
										<div class="">
											<span class="value textOverflowEllipsis" title="" style="display: inline-block;">
												<a class="urlField cursorPointer" href="/index.php?module=Leads&view=Detail&record={$LEAD.leadid}&mode=showDetailViewByMode&requestMode=summary&tab_label=Lead%20Resumo&app=MARKETING" target="_blank">
													Clique aqui
												</a>
											</span>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>

{/if}
