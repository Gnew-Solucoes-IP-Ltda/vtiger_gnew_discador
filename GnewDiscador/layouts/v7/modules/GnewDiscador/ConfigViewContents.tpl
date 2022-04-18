{*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************}

<br>
{if $ADMIN != "on"}
<div class="main-container clearfix">
	<div class="editViewPageDiv viewContent">
		<div class="col-sm-12 col-xs-12 content-area ">
			<div class="editViewHeader">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-lg-pull-0">
						<h4 class="editHeader" style="margin-top:5px;">Sem permissão de acesso!</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>		
{else}
<div class="main-container clearfix">
	<div class="editViewPageDiv viewContent">
		<div class="col-lg-6" style="margin-top:50px;">
			<form method="get" action="index.php" enctype="multipart/form-data">
				<input type="hidden" name="module" value="GnewDiscador">
				<input type="hidden" name="view" value="Config"> 
				<input type="hidden" name="app" value="TOOLS">
				<div class="form-group">
					<hr>
					<h4>Configurações PABX GNEW</h4>
					<div class="form-group">
						<label for="UrlInputForm">Endpoint para discagem</label>
						<input type="text" class="form-control" id="UrlInputForm" name="url" value="{$CONFIG.url}">
					</div>
					<div class="form-group">
						<label for="PkEncInputForm">PK Empresa Encriptado (PK ENC)</label>
						<input type="text" class="form-control" id="PkEncInputForm" name="pk_enc" value="{$CONFIG.pk_enc}">
					</div>
					<div class="form-group">
						<label for="TokenInputForm">Token para autenticação</label>
						<input type="text" class="form-control" id="TokenInputForm" name="token" value="{$CONFIG.token}">
					</div>
				</div>
				<button type="submit" class="btn btn-success">Salvar</button>
			</form>
		</div>
	</div>
</div>

{/if}


