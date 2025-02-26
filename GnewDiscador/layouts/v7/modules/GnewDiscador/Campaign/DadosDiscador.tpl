<div class="main-container clearfix">
	<div class="editViewPageDiv viewContent">
		<div class="col-sm-12 col-xs-12 content-area ">
			<div class="editViewHeader">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-lg-pull-0">
						<a class="btn btn-default" href="index.php?module=GnewDiscador&view=List&viewname=&app=TOOLS">Discador</a>
						<a class="btn btn-default disabled" href="index.php?module=GnewDiscador&view=DadosDiscador&viewname=&app=TOOLS">Dados</a>
						<a class="btn btn-default" href="index.php?module=GnewDiscador&view=Config&viewname=&app=TOOLS">Configurações gerais</a>
					</div>
				</div>
			</div>
			<br/>
			<div class="editViewBody">
				<div class="editViewContents">
					<div name="editContent">
						<div class="fieldBlockContainer">
							<h4 class="fieldBlockHeader">Usuários ativos</h4>
							<hr>
							<table class="table table-borderless">
							<tbody>
								<th>Nome</th>
								<th>Ligações</th>
								<th>Última requisição</th>
								<th>Campanha</th>
								<th>Lead</th>
								<th>Contato</th>
								{foreach item=DADO from=$DADOS}
								<tr>
									<td>
										<a href="index.php?module=Users&parent=Settings&view=Detail&record={$DADO.id}&parentblock=LBL_USER_MANAGEMENT">
											{$DADO.nome}
										</a>
									</td>
									<td>{$DADO.ligacoes}</td>
									<td>{$DADO.data_requisicao}</td>
									<td>
										<a href="index.php?module=Campaigns&view=Detail&record={$DADO.campaign.campaignid}&app=MARKETING">
											{$DADO.campaign.nome}
										</a>
									</td>
									<td>
										<a href="index.php?module=Leads&view=Detail&record={$DADO.lead.leadid}&app=MARKETING">
											{$DADO.lead.nome_completo}
										</a>	
									</td>
									<td>{$DADO.lead.telefone_discado}</td>
								</tr>
								{/foreach}
							</tbody>
						</table>
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
</div>