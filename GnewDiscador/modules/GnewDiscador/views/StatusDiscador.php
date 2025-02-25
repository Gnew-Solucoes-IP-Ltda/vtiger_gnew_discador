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

	public function process(Vtiger_Request $request) {
		$this->redis_connect();
		$userContext = vglobal('current_user');
		$viewer = $this->getViewer($request);

		if ($userContext->is_admin == 'on'){

			$viewer->assign('DADOS', $this->getUsersData());
			$viewer->view('Campaign/DadosDiscador.tpl', $request->getModule());
		
		} else {

			$viewer->view('Config/Forbidden.tpl', $request->getModule());

		}

	protected function getUsersData(){

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

	protected function redis_connect(){
		$this->redis = new Redis();
		$this->redis->connect('localhost', 6379);
	}
}