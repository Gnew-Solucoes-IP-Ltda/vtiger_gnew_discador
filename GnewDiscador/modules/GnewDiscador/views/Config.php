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

class GnewDiscador_Config_View extends Vtiger_Index_View
{

	protected function redis_connect()
	{
		$this->redis = new Redis();
		$this->redis->connect('localhost', 6379);
	}

	protected function obter_configuracao()
	{
		$this->redis_connect();
		
		if ($this->redis->exists('gnew_discador_config')) {
			return json_decode($this->redis->get('gnew_discador_config'), TRUE);
		}
		else {
			$configuracao = array(
				'url' => '',
				'token' => '',
				'pk_enc' => '',
				'versao' => '',
				'ignorar_ssl' => ''
			);
			$this->redis->set('gnew_discador_config', json_encode($configuracao));
			return $configuracao;
		}
	}

	protected function salvar_configuracao($url, $pk_enc, $token, $versao, $ignorar_ssl){
		$this->redis_connect();
		$configuracao = array(
			'url' => $url,
			'pk_enc' => $pk_enc,
			'token' => $token,
			'versao' => $versao,
			'ignorar_ssl' => $ignorar_ssl
		);
		$this->redis->set('gnew_discador_config', json_encode($configuracao));
		return $configuracao;
	}

	public function process(Vtiger_Request $request)
	{
		// Current User Context
		$userContext = vglobal('current_user');
		$viewer = $this->getViewer($request);
		
		if ($userContext->is_admin == 'on'){
			if (
				$request->has('url') &&
				$request->has('pk_enc') &&
				$request->has('token')
			){
				if ($request->has('versao')){
					$versao = $request->get('versao');
				}
				else{
					$versao = '';
				}

				if ($request->has('ignorar_ssl')){
					$ignorar_ssl = $request->get('ignorar_ssl');
				}
				else{
					$ignorar_ssl = '';
				}

				$configuracao = $this->salvar_configuracao(
					$request->get('url'),
					$request->get('pk_enc'),
					$request->get('token'),
					$versao,
					$ignorar_ssl
				);
			}
			else{
				$configuracao = $this->obter_configuracao();
			}
		}
		else{
			$configuracao = array();
		}

		$viewer->assign('CONFIG', $configuracao);
		$viewer->assign('ADMIN', $userContext->is_admin);
		$viewer->view('ConfigViewContents.tpl', $request->getModule());
	}
}
