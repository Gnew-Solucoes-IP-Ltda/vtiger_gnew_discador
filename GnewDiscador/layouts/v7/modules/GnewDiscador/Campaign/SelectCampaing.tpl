<div class="main-container clearfix">
	<div class="editViewPageDiv viewContent">
		<div class="col-sm-12 col-xs-12 content-area ">
			<form class="form-horizontal recordEditView" id="EditView" name="edit" method="get" action="index.php" enctype="multipart/form-data" novalidate="novalidate">
				<div class="editViewHeader">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-lg-pull-0">
							<a class="btn btn-default disabled" href="index.php?module=GnewDiscador&view=List&viewname=&app=TOOLS">Discador</a>
							{if $ADMIN == 'on'}
								<a class="btn btn-default" href="index.php?module=GnewDiscador&view=DadosDiscador&viewname=&app=TOOLS">Dados</a>
								<a class="btn btn-default" href="index.php?module=GnewDiscador&view=Config&viewname=&app=TOOLS">Configurações gerais</a>
							{else}
								<a class="btn btn-default disabled" href="index.php?module=GnewDiscador&view=DadosDiscador&viewname=&app=TOOLS">Dados</a>
								<a class="btn btn-default disabled" href="index.php?module=GnewDiscador&view=Config&viewname=&app=TOOLS">Configurações gerais</a>
							{/if}
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
												<select data-fieldname="campaign" data-fieldtype="picklist" class="inputElement select2  select2-offscreen" style="width:600px;" type="picklist" name="campaign" data-selected-value="" tabindex="-1" title="">
													{foreach item=RECORD from=$RECORDS}
														<option value="{$RECORD.campaign_no}">{$RECORD.campaignname}</option>
													{/foreach}
												</select>
											</td>
											<td>
												<button type="submit" class="btn btn-primary saveButton textAlignCenter">Iniciar</button>&nbsp;&nbsp;
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>