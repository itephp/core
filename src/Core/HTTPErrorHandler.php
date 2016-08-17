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

use ItePHP\Error\ErrorHandler;
use ItePHP\Core\Enviorment;
use ItePHP\Core\HTTPException;
use ItePHP\Core\EventManager;
use ItePHP\Presenter\HTML as HTMLPresenter;
use ItePHP\Core\ExecutePresenterEvent;
use ItePHP\Core\RequestProvider;
use ItePHP\Core\Config;

/**
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class HTTPErrorHandler implements ErrorHandler{
	
	/**
	 *
	 * @var Enviorment
	 */ 
	private $enviorment;

	/**
	 *
	 * @var Config
	 */ 
	private $config;

	/**
	 *
	 * @var EventManager
	 */ 
	private $eventManager;

	/**
	 *
	 * @var RequestProvider
	 */ 
	private $request;

	/**
	 *
	 * @param Enviorment $enviorment
	 * @param Config $config
	 * @param EventManager $eventManager
	 * @param RequestProvider $request
	 */
	public function __construct(Enviorment $enviorment,Config $config,EventManager $eventManager,RequestProvider $request){
		$this->enviorment=$enviorment;
		$this->config=$config;
		$this->eventManager=$eventManager;
		$this->request=$request;
	}

    /**
     * {@inheritdoc}
     */
	public function execute(\Exception $exception){
		if(!$this->enviorment->isSilent()){
			error_log($exception->getMessage()." ".$exception->getFile()."(".$exception->getLine().")");
		}

		$presenter=$this->getPresenter($this->request->getUrl());

		$response=new Response();
		$response->setStatusCode(500);
		$response->setContent($exception);
		if($exception instanceof HTTPException){
			$response->setStatusCode($exception->getStatusCode());
		}

		$event=new ExecutePresenterEvent($this->request,$response);
		$this->eventManager->fire('executePresenter',$event);

		$presenter->render($this->enviorment,$response);

	}

	/**
	 *
	 * @param string $url
	 * @return string
	 */
	private function getPresenter($url){
		foreach($this->config->getNodes('error') as $error){
			if(!preg_match('/^'.$error->getAttribute('pattern').'$/',$url)){
				continue;
			}
			$presenterName=$error->getAttribute('presenter');
			return new $presenterName();
		}

		return new HTMLPresenter();

	}

}