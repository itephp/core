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

use ItePHP\Root;
use ItePHP\Provider\Response;
use ItePHP\Presenter\HTML as HTMLPresenter;
use ItePHP\Exception\SyntaxException;
use ItePHP\Exception\RuntimeException;
use ItePHP\Core\ExecuteResources;
use ItePHP\Exception\ResourcesNotRegisteredException;
use ItePHP\Contener\RequestConfig;
use ItePHP\Presenter\HTML;
use ItePHP\Event\ExecutePresenterEvent;
use ItePHP\Provider\Request;
use ItePHP\Test\Request as RequestTest;
use ItePHP\Provider\Session;
use ItePHP\Core\EventManager;

/**
 * Handler for errors.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
class ErrorHandler{

	/**
	 * ExecuteResources.
	 *
	 * @var \ItePHP\Core\ExecuteResources $resources
	 */	
	private $resources;

	/**
	 * Flag block propagation error events
	 *
	 * @var boolean $isStopPropagation
	 */	
	private $isStopPropagation;

	/**
	 * Runtime path
	 *
	 * @var string $runtimePath
	 */	
	private $runtimePath;

	/**
	 * Event manager
	 *
	 * @var \ItePHP\Core\EventManager $eventManager
	 */	
	private $eventManager;

	/**
	 * Constructor.
	 *
	 * @param \ItePHP\Core\ExecuteResources $resource
	 * @param \ItePHP\Core\EventManager $eventManager
	 * @since 0.1.0
	 */	
	public function __construct(ExecuteResources $resources,EventManager $eventManager){
		$this->resources=$resources;
		$this->eventManager=$eventManager;
		$this->isStopPropagation=false;
		$this->runtimePath=getcwd();

		ini_set('display_errors', 'Off');
		register_shutdown_function(array($this, 'shutdown'));
		set_error_handler(array($this, 'error'));
		set_exception_handler(array($this,'exception'));
	}

	/**
	 * Shutdown callback
	 *
	 * @since 0.1.0
	 */	
	public function shutdown(){
		if($this->isStopPropagation)
			return;
		$error = error_get_last();
		if( $error !== NULL) {
			chdir($this->runtimePath);
			$errno   = $error["type"];
			$errfile = $error["file"];
			$errline = $error["line"];
			$errstr  = $error["message"];


			$this->parseView(new SyntaxException($errfile,$errline,$errstr));
		}
	}

	/**
	 * Exception callback.
	 *
	 * @param \Exception $exception
	 * @return boolean
	 * @since 0.1.0
	 */	
	public function exception($exception){
		if($this->isStopPropagation)
			return;
		$this->parseView($exception);

		$this->isStopPropagation=true;
		return false;
	}
		
	/**
	 * Error callback.
	 *
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param int $errline
	 * @throws \ItePHP\Exception\RuntimeException
	 * @since 0.1.0
	 */	
	public function error($errno, $errstr, $errfile, $errline){
		throw new RuntimeException($errfile,$errline,$errstr);
	}

	/**
	 * Parse view for exception.
	 *
	 * @param \Exception $exception
	 * @since 0.1.0
	 */	
	private function parseView($exception){
		if(!$this->resources->getEnviorment()->isSilent()){
			error_log($exception->getMessage()." ".$exception->getFile()."(".$exception->getLine().")");
		}
		$response=new Response();
		$response->setStatusCode(500);
		$response->setContent($exception);
		$this->resources->registerResponse($response);
		$presenter=null;

		try{
			$presenter=$this->resources->getPresenter();
		}
		catch(ResourcesNotRegisteredException $e){
			$presenter=$this->findPresenter();
		}

		try{
			$request=$this->resources->getRequest();
		}
		catch(ResourcesNotRegisteredException $e){
			$requestConfig=new RequestConfig('','',$this->resources->getEnviorment(),
					array(
						'route'=>''
						,'presenter'=>array('class' =>'')
						,'class'=>''
						));

			if($this->resources->getEnviorment()->isSilent()){
				$request=new RequestTest($this->resources->getUrl(),$this->resources->getEnviorment());
				$request->setConfig($requestConfig);
			}
			else{
				$session=new Session($this->resources->getEnviorment());
				$request=new Request($requestConfig,$this->resources->getUrl(),$session);

			}
			$this->resources->registerRequest($request);
		}

		if($presenter){
			$event=new ExecutePresenterEvent($this->resources->getRequest(),$response);
			$this->eventManager->fire('executePresenter',$event);
			$presenter->render($request->getConfig() , $response);
		}

	}

	/**
	 * Find presenter for error action.
	 *
	 * @return \ItePHP\Core\Presenter
	 * @since 0.1.0
	 */	
	private function findPresenter(){
		$url=$this->resources->getUrl();
		if($this->resources->getGlobalConfig()){
			foreach($this->resources->getGlobalConfig()->getErrors() as $pattern=>$presenterName){
				if(preg_match('/^'.$pattern.'$/',$url)){
					return new $presenterName();
				}
			}

		}

		return new HTML();

	}

}