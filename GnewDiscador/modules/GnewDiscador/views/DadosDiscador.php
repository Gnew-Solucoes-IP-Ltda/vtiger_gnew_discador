<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

/* Include dependency required for using Server API */
include_once 'include/Webservices/Query.php';

class GnewDiscador_DadosDiscador_View extends Vtiger_Index_View {

	protected function redis_connect()
	{
		$this->redis = new Redis();
		$this->redis->connect('localhost', 6379);
	}

	protected function obter_dados_usuarios(){
		if ($this->redis->exists('fila_tabulacao_leads')){
			$lista_usuarios = json_decode(
				$this->redis->get('fila_tabulacao_leads'),
				TRUE
			);
			$dados_usuarios = array();
			
			foreach($lista_usuarios as $usuario){
				if ($this->redis->exists('gnew_discador_user_dados_'.$usuario)){
					$dado_usuario = json_decode(
						$this->redis->get('gnew_discador_user_dados_'.$usuario),
						TRUE
					);
					$dados_usuarios[$dado_usuario['nome']] = $dado_usuario;
				}
			}

			$dados_ordenados = array();
			ksort($dados_usuarios);

			foreach($dados_usuarios as $dado_usuario){
				array_push($dados_ordenados, $dado_usuario);
			}

		}

		return $dados_ordenados;
	}

	public function process(Vtiger_Request $request) {
		$this->redis_connect();
		// Current User Context	
		$userContext = vglobal('current_user');
		$viewer = $this->getViewer($request);
		// var_dump($this->obter_dados_usuarios());
		// $query = "SELECT campaign_no, campaignname FROM Campaigns;";
		// $records = vtws_query($query, $userContext);
		$viewer->assign('ADMIN', $userContext->is_admin);
		$viewer->assign('DADOS', $this->obter_dados_usuarios());
		$viewer->view('DadosDiscadorViewContents.tpl', $request->getModule());
	}
}