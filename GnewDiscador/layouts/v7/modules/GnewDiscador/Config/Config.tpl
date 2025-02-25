{*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************}

<div class="main-container clearfix">
	<div class="editViewPageDiv viewContent">
		<div class="col-lg-6" style="margin-top:50px;">
			<form method="get" action="index.php" enctype="multipart/form-data">
				<input type="hidden" name="module" value="GnewDiscador">
				<input type="hidden" name="view" value="Config"> 
				<input type="hidden" name="app" value="TOOLS">
				<div class="form-group">
					<h4>Configurações PABX GNEW</h4>
					<hr>
					<div class="form-group">
						<label for="UrlInputForm">Endpoint para discagem</label>
						<input type="text" class="form-control" id="UrlInputForm" name="url" value="{$CONFIG.url}">
					</div>
					<div class="form-group">
						<label for="PkEncInputForm">PK ENC (GNEW 3.5)</label>
						<input type="text" class="form-control" id="PkEncInputForm" name="pk_enc" value="{$CONFIG.pk_enc}">
					</div>
					<div class="form-group">
						<label for="TokenInputForm">Token para autenticação</label>
						<input type="text" class="form-control" id="TokenInputForm" name="token" value="{$CONFIG.token}">
					</div>
					<div class="form-check">						
						<input type="checkbox" class="form-check-input" id="VersaoInputForm" name="versao" value="checked" {$CONFIG.versao}>
						<label for="VersaoInputForm">GNEW 2.0</label>
					</div>
					<div class="form-check">						
						<input type="checkbox" class="form-check-input" id="SslInputForm" name="ignorar_ssl" value="checked" {$CONFIG.ignorar_ssl}>
						<label for="SslInputForm">Ignorar erros com certificados</label>
					</div>
				</div>
				<button type="submit" class="btn btn-success">Salvar</button>
			</form>
		</div>
	</div>
</div>



