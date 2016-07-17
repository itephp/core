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

use ItePHP\Route\Dispatcher;

use ItePHP\DependencyInjection\DependencyInjection;

use ItePHP\Core\Response;
use ItePHP\Core\ActionNotFoundException;
use ItePHP\Core\RequestProvider;
use ItePHP\Core\Enviorment;
use ItePHP\Core\Container;

use ItePHP\Event\ExecuteActionEvent;
use ItePHP\Event\ExecutedActionEvent;
use ItePHP\Event\ExecutePresenterEvent;

use ItePHP\Core\Config;

/**
 * Dispatcher for http request
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class HTTPDispatcher  implements Dispatcher {

	/**
	 * Request
	 *
	 * @var RequestProvider
	 */
	protected $request;

	/**
	 *
	 * @var string
	 */
	protected $className;

	/**
	 *
	 * @var string
	 */
	protected $methodName;

	/**
	 *
	 * @var string
	 */
	protected $presenterName;

	/**
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 *
	 * @var Enviorment
	 */
	protected $enviorment;

	/**
	 *
	 * @var Config
	 */
	protected $config;

	/**
	 * Constructor.
	 *
	 * @param Config $config
	 * @param Container $container
	 * @param RequestProvider $request
	 * @param Enviorment $enviorment
	 */
	public function __construct(Config $config,Container $container,RequestProvider $request,Enviorment $enviorment){
		$this->config=$config;
		$this->className=$config->getAttribute('class');
		$this->methodName=$config->getAttribute('method');
		$this->presenterName=$config->getAttribute('presenter');
		$this->request=$request;
		$this->container=$container;
		$this->enviorment=$enviorment;
	}


	/**
	 * {@inheritDoc}
	 */
	public function execute(){
		$this->request->setConfig($this->config);
		$eventManager=$this->container->getEventManager();
		$presenter=new $this->presenterName();

		$event=new ExecuteActionEvent($this->request);
		$eventManager->fire('executeAction',$event);
		if($event->getResponse()){
			$response=$event->getResponse();
		}
		else{
			$response=$this->invokeController();
		}

		$this->prepareView($presenter , $response);
	}

	/**
	 * 
	 * @return Response
	 */
	private function invokeController(){
		$eventManager=$this->container->getEventManager();

		$controller=new $this->className($this->request,$this->container);

		if(!is_callable(array($controller,$this->methodName))){
			throw new ActionNotFoundException($this->className,$this->methodName);
		}
		$response=null;
		$controllerData=call_user_func_array([$controller, $this->methodName], $this->request->getArguments());
		if($controllerData instanceof Response){
			$response=$controllerData;
		}
		else{
			$response=new Response();
			$response->setContent($controllerData);
		}

		if(!$response->getPresenter()){
			$response->setPresenter(new $this->presenterName());				
		}

		$event=new ExecutedActionEvent($this->request,$response);
		$eventManager->fire('executedAction',$event);

		return $response;
	}

	/**
	 * Render view
	 *
	 * @param Presenter $presenter
	 * @param Response $response
	 */
	protected function prepareView(Presenter $presenter , Response $response){
		$eventManager=$this->container->getEventManager();
		$event=new ExecutePresenterEvent($this->request,$response);
		$eventManager->fire('executePresenter',$event);

		$presenter->render($this->enviorment , $response);
	}


}