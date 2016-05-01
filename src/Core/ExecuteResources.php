<?php

/**
 * ItePHP: Framework PHP (http://itephp.com)
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the file LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) NewClass (http://newclass.pl)
 * @link          http://itephp.com ItePHP Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace ItePHP\Core;

use ItePHP\Contener\GlobalConfig;
use ItePHP\Core\Presenter;
use ItePHP\Provider\Response;
use ItePHP\Core\RequestProvider;
use ItePHP\Exception\ResourcesNotRegisteredException;
use ItePHP\Core\Enviorment;

/**
 * Container for all execute resources e.g.: services, providers
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
class ExecuteResources{
	
	/**
	 * Global config.
	 *
	 * @var \ItePHP\Contener\GlobalConfig $globalConfig
	 */
	private $globalConfig;

	/**
	 * Registered services.
	 *
	 * @var array $services
	 */
	private $services=array();

	/**
	 * Registered snippets.
	 *
	 * @var array $snippets
	 */
	private $snippets=array();

	/**
	 * Registered presenter.
	 *
	 * @var \ItePHP\Core\Presenter $presenter
	 */
	private $presenter;

	/**
	 * Registered request.
	 *
	 * @var \ItePHP\Core\RequestProvider $request
	 */
	private $request;

	/**
	 * Registered response.
	 *
	 * @var \ItePHP\Provider\Response $response
	 */
	private $response;

	/**
	 * Registered http url.
	 *
	 * @var string $url
	 */
	private $url;

	/**
	 * Registered enviorment.
	 *
	 * @var \ItePHP\Core\Enviorment $enviorment
	 */
	private $enviorment;

	/**
	 * Register global config.
	 *
	 * @param \ItePHP\Contener\GlobalConfig $config
	 * @since 0.1.0
	 */
	public function registerGlobalConfig(GlobalConfig $config){
		$this->globalConfig=$config;
	}

	/**
	 * Get global config.
	 *
	 * @return \ItePHP\Contener\GlobalConfig
	 * @since 0.1.0
	 */
	public function getGlobalConfig(){
		return $this->globalConfig;
	}

	/**
	 * Register service.
	 *
	 * @param string $name service name
	 * @param object $object serivce object
	 * @since 0.1.0
	 */
	public function registerService($name,$object){
		$this->services[$name]=$object;
	}

	/**
	 * Register snippet.
	 *
	 * @param string $name snippet name
	 * @param object $object snippet object
	 * @since 0.1.0
	 */
	public function registerSnippet($name,$object){
		$this->snippets[$name]=$object;
	}

	/**
	 * Register presenter.
	 *
	 * @param \ItePHP\Core\Presenter $presenter
	 * @since 0.1.0
	 */
	public function registerPresenter(Presenter $presenter){
		$this->presenter=$presenter;
	}

	/**
	 * Get presenter.
	 *
	 * @return \ItePHP\Core\Presenter
	 * @throws \ItePHP\Exception\ResourcesNotRegisteredException
	 * @since 0.1.0
	 */
	public function getPresenter(){
		if($this->presenter)
			return $this->presenter;
		else
			throw new ResourcesNotRegisteredException();
	}

	/**
	 * Register enviorment.
	 *
	 * @param \ItePHP\Core\Enviorment $enviorment
	 * @since 0.1.0
	 */
	public function registerEnviorment(Enviorment $enviorment){
		$this->enviorment=$enviorment;
	}

	/**
	 * Get enviorment
	 *
	 * @return \ItePHP\Core\Enviorment
	 * @since 0.1.0
	 */
	public function getEnviorment(){
		return $this->enviorment;
	}

	/**
	 * Register request
	 *
	 * @param \ItePHP\Core\RequestProvider $request
	 * @since 0.1.0
	 */
	public function registerRequest(RequestProvider $request){
		$this->request=$request;
	}

	/**
	 * Get request.
	 *
	 * @return \ItePHP\Core\RequestProvider
	 * @throws \ItePHP\Exception\ResourcesNotRegisteredException
	 * @since 0.1.0
	 */
	public function getRequest(){
		if($this->request)
			return $this->request;
		else
			throw new ResourcesNotRegisteredException();
	}

	/**
	 * Register response.
	 *
	 * @param \ItePHP\Provider\Response $response
	 * @since 0.1.0
	 */
	public function registerResponse(Response $response){
		$this->response=$response;
	}

	/**
	 * Get response.
	 *
	 * @return \ItePHP\Provider\Response
	 * @throws \ItePHP\Exception\ResourcesNotRegisteredException
	 * @since 0.1.0
	 */
	public function getResponse(){
		if($this->response)
			return $this->response;
		else
			throw new ResourcesNotRegisteredException();
	}

	/**
	 * Get all registered services.
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getServices(){
		return $this->services;
	}

	/**
	 * Get all registered snippets.
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function getSnippets(){
		return $this->snippets;
	}

	/**
	 * Register url.
	 *
	 * @param string $url
	 * @since 0.1.0
	 */
	public function registerUrl($url){
		$this->url=$url;
	}

	/**
	 * Get registered url.
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public function getUrl(){
		return $this->url;
	}

	/**
	 * Set debug flag
	 *
	 * @param boolean $debug
	 * @since 0.1.0
	 */
	public function setDebug($debug){
		$this->debug=$debug;
	}

	/**
	 * Check is debug mode enabled.
	 *
	 * @return boolean
	 * @since 0.1.0
	 */
	public function isDebug(){
		return $this->debug;
	}

	/**
	 * Set silent flag
	 *
	 * @param boolean $silent
	 * @since 0.1.0
	 */
	public function setSilent($silent){
		$this->silent=$silent;
	}

	/**
	 * Check is silent mode enabled.
	 *
	 * @return boolean
	 * @since 0.1.0
	 */
	public function isSilent(){
		return $this->silent;
	}

}