<div class="main-container clearfix">
	<div class="editViewPageDiv viewContent">
		<div class="col-sm-12 col-xs-12 content-area ">
			<form class="form-horizontal recordEditView" id="EditView" name="edit" method="get" action="index.php" enctype="multipart/form-data" novalidate="novalidate">
				<div class="editViewHeader">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-lg-pull-0">
							<a class="btn btn-default" href="index.php?module=GnewDiscador&view=List&viewname=&app=TOOLS">Discador</a>
							<a class="btn btn-default" href="index.php?module=GnewDiscador&view=DadosDiscador&viewname=&app=TOOLS">Dados</a>
							<a class="btn btn-default disabled" href="index.php?module=GnewDiscador&view=Config&viewname=&app=TOOLS">Configurações gerais</a>
						</div>
					</div>
				</div>
				<div class="editViewBody">
					<div class="editViewContents">
						<input type="hidden" name="module" value="GnewDiscador">
						<input type="hidden" name="view" value="Config"> 
						<input type="hidden" name="app" value="TOOLS">

						<div name="editContent">
							<div class="fieldBlockContainer">
								<h4 class="fieldBlockHeader">Configurações PABX GNEW</h4>
								<hr>
								<table class="table table-borderless">
									<tbody>
										<tr>
											<td class="fieldLabel alignMiddle">Endpoint para discagem</td>
											<td class="fieldValue">
												<input type="text" class="inputElement" style="width: 500px;" id="UrlInputForm" name="url" value="{$CONFIG.url}">
											</td>
										</tr>
										<tr>
											<td class="fieldLabel alignMiddle">PK ENC (GNEW 3.5)</td>
											<td class="fieldValue">
												<input type="text" class="inputElement" style="width: 500px;" id="PkEncInputForm" name="pk_enc" value="{$CONFIG.pk_enc}">
											</td>
										</tr>
										<tr>
											<td class="fieldLabel alignMiddle">Token para autenticação</td>
											<td class="fieldValue">
												<input type="text" class="inputElement" style="width: 500px;" id="TokenInputForm" name="token" value="{$CONFIG.token}">
											</td>
										</tr>
										<tr>
											<td class="fieldLabel alignMiddle">GNEW 2.0</td>
											<td class="fieldValue">
												<input type="checkbox" class="form-check-input" id="VersaoInputForm" name="versao" value="checked" {$CONFIG.versao}>
											</td>
										</tr>
										<tr>
											<td class="fieldLabel alignMiddle">Ignorar erros com certificados</td>
											<td class="fieldValue">
												<input type="checkbox" class="form-check-input" id="SslInputForm" name="ignorar_ssl" value="checked" {$CONFIG.ignorar_ssl}>
											</td>
										</tr>
									</tbody>
								</table>
									<button type="submit" class="btn btn-primary saveButton">Salvar</button>&nbsp;&nbsp;
							</div>
						</div>
					</div>
				</div>	
			</form>
		</div>
	</div>
</div>