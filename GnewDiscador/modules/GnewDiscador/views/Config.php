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
	
	public function process(Vtiger_Request $request){
		$userContext = vglobal('current_user');
		$viewer = $this->getViewer($request);

		if ($userContext->is_admin == 'on'){

			$config = $this->getConfig($userContext, $request);
			$viewer->assign('CONFIG', $config);
			$viewer->view('Config/Config.tpl', $request->getModule());

		} else {

			$viewer->view('Config/Forbidden.tpl', $request->getModule());

		}
	}
	
	protected function getConfig($userContext, $request){
		$config = array();

		if ($userContext->is_admin == 'on'){
			if ($request->has('url') && $request->has('pk_enc') && $request->has('token')){
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

				$config = $this->saveRedisConfig(
					$request->get('url'),
					$request->get('pk_enc'),
					$request->get('token'),
					$versao,
					$ignorar_ssl
				);
			}
			else{
				$config = $this->getRedisConfig();
			}
		}

		return $config;
	}
	
	protected function getRedisConfig(){
		$this->redisConnect();
		
		if ($this->redis->exists('gnew_discador_config')) {
			return json_decode($this->redis->get('gnew_discador_config'), TRUE);
		}
		else {
			$config = array(
				'url' => '',
				'token' => '',
				'pk_enc' => '',
				'versao' => '',
				'ignorar_ssl' => ''
			);
			$this->redis->set('gnew_discador_config', json_encode($config));
			return $config;
		}
	}
	
	protected function saveRedisConfig($url, $pk_enc, $token, $versao, $ignorar_ssl){
		$this->redisConnect();
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
	
	protected function redisConnect(){
		$this->redis = new Redis();
		$this->redis->connect('localhost', 6379);
	}
}
