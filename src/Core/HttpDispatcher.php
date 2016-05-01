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

use ItePHP\Core\ExecuteResources;
use ItePHP\Provider\Response;
use ItePHP\Event\ExecuteActionEvent;
use ItePHP\Event\ExecutedActionEvent;
use ItePHP\Event\ExecutePresenterEvent;
use ItePHP\Core\EventManager;
use ItePHP\Exception\ActionNotFoundException;
use ItePHP\Provider\Session;
use ItePHP\Core\RequestProvider;
use ItePHP\Provider\Request;
use ItePHP\Contener\RequestConfig;

/**
 * Dispatcher for http request
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
class HttpDispatcher  implements Dispatcher {

	/**
	 * Request
	 *
	 * @var \ItePHP\Core\RequestProvider $request
	 */
	protected $request;

	/**
	 * ExecuteResources
	 *
	 * @var \ItePHP\Core\ExecuteResources $resources
	 */
	protected $resources;

	/**
	 * ExecuteResources
	 *
	 * @var \ItePHP\Contener\RequestConfig $config
	 */
	protected $config;

	/**
	 * Constructor.
	 *
	 * @param \ItePHP\Contener\RequestConfig $config
	 * @since 0.1.0
	 */
	public function __construct(RequestConfig $config){
		$this->config=$config;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param \ItePHP\Core\ExecuteResources $resources
	 * @param \ItePHP\Core\EventManager $eventManager
	 * @since 0.1.0
	 */
	public function execute(ExecuteResources $resources,EventManager $eventManager){
		$this->resources=$resources;
		$this->eventManager=$eventManager;
		$session=new Session($this->resources->getEnviorment());
		$this->request=new Request($this->config,$this->resources->getUrl(),$session);
		$this->resources->registerRequest($this->request);

		$this->callMethod();

	}	

	/**
	 * Execute controller method
	 *
	 * @throws \ItePHP\Exception\ActionNotFoundException
	 * @since 0.1.0
	 */
	protected function callMethod(){

		$this->resources->registerPresenter($this->getPresenter());
		$response=new Response();
		$response->setPresenter($this->resources->getPresenter());
		$event=new ExecuteActionEvent($this->request);
		$this->eventManager->fire('executeAction',$event);
		if(!$event->getResponse()){

			$controllerName=$this->request->getClass();
			$controller=new $controllerName($this->request,$this->resources,$this->eventManager);

			if(!is_callable(array($controller,$this->request->getMethod()))){
				throw new ActionNotFoundException($controllerName,$this->request->getMethod());
			}

			$controllerData=call_user_func_array(array($controller, $this->request->getMethod()), $this->request->getArguments());
			if($controllerData instanceof Response){
				$response=$controllerData;
				if(!$response->getPresenter())
					$response->setPresenter($this->resources->getPresenter());
				else
					$this->resources->registerPresenter($response->getPresenter());
			}
			else
				$response->setContent($controllerData);
			$event=new ExecutedActionEvent($this->request,$response);
			$this->eventManager->fire('executedAction',$event);

		}
		else
			$response=$event->getResponse();			

		$this->resources->registerResponse($response);

		$this->prepareView($this->request , $this->resources->getPresenter() , $response);
	}

	/**
	 * Get presenter
	 *
	 * @return \ItePHP\Core\Presenter
	 * @since 0.1.0
	 */
	protected function getPresenter(){
		$presenterConfig=$this->config->getPresenter();
		return new $presenterConfig['class']($this->services);
	}

	/**
	 * Render view
	 *
	 * @param \ItePHP\Core\RequestProvider $request
	 * @param \ItePHP\Core\Presenter $presenter
	 * @param \ItePHP\Provider\Response $response
	 * @since 0.1.0
	 */
	protected function prepareView(RequestProvider $request , Presenter $presenter , Response $response){
		$event=new ExecutePresenterEvent($request,$response);
		$this->eventManager->fire('executePresenter',$event);

		$presenter->render($request->getConfig() , $response);
	}


}