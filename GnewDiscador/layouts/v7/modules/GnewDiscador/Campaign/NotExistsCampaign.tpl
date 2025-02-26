<div class="main-container clearfix">
    <div class="editViewPageDiv viewContent">
        <div class="col-sm-12 col-xs-12 content-area ">
			<div class="editViewHeader">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-lg-pull-0">
							<a class="btn btn-default disabled" href="index.php?module=GnewDiscador&view=List&viewname=&app=TOOLS">Discador</a>
							<a class="btn btn-default disabled" href="index.php?module=GnewDiscador&view=DadosDiscador&viewname=&app=TOOLS">Dados</a>
							{if $ADMIN == 'on'}
								<a class="btn btn-default" href="index.php?module=GnewDiscador&view=Config&viewname=&app=TOOLS">Configurações gerais</a>
							{else}
								<a class="btn btn-default disabled" href="index.php?module=GnewDiscador&view=Config&viewname=&app=TOOLS">Configurações gerais</a>
							{/if}
					</div>
				</div>
			</div>
			<div class="editViewBody">
				<div class="editViewContents">
					<h4 class="textAlignCenter editHeader" style="margin-top:5px;">Não existem campanhas cadastradas!</h4>
				</div>
			</div>
        </div>
    </div>
</div>
