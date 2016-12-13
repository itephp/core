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

use Config\Config\Action;
use Via\Dispatcher;

/**
 * Dispatcher for http request
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class HTTPDispatcher  implements Dispatcher {

	/**
	 * Request
	 *
	 * @var Request
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
	 * @var Environment
	 */
	protected $environment;

	/**
	 *
	 * @var Action
	 */
	protected $config;

	/**
	 *
	 * @var Presenter[]
	 */
	protected $presenters;

    /**
     * Constructor.
     *
     * @param Action $config
     * @param Container $container
     * @param Request $request
     * @param Environment $environment
     * @param Presenter[] $presenters
     */
	public function __construct(Action $config, Container $container, Request $request, Environment $environment, array $presenters){
		$this->config=$config;
		$this->className=$config->getClass();
		$this->methodName=$config->getMethod();
		$this->presenterName=$config->getPresenter();
		$this->presenters=$presenters;
		$this->request=$request;
		$this->container=$container;
		$this->environment=$environment;
	}


	/**
	 * {@inheritDoc}
	 */
	public function execute(){
		$this->request->setConfig($this->config);
		$eventManager=$this->container->getEventManager();
		$presenter=$this->getPresenter();

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
	 * @return Presenter
	 * @throws PresenterNotFoundException
	 */
	private function getPresenter(){
		if(!isset($this->presenters[$this->presenterName])){
			throw new PresenterNotFoundException($this->presenterName);
		}
		return $this->presenters[$this->presenterName];
	}

    /**
     * @return Response
     * @throws ActionNotFoundException
     */
	private function invokeController(){
		$eventManager=$this->container->getEventManager();

		$controller=new $this->className($this->request,$this->container);

		if(!is_callable([$controller,$this->methodName])){
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
			$response->setPresenter($this->getPresenter());				
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

		$presenter->render($this->request,$response);
	}


}