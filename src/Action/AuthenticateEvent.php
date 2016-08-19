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

namespace ItePHP\Action;

use ItePHP\Core\Response;
use ItePHP\Core\ExecuteActionEvent;
use ItePHP\Action\ValueNotFoundException;
use ItePHP\Action\PermissionDeniedException;
use ItePHP\Core\Request;
use ItePHP\Core\Config;

/**
 * Event for support authenticate user
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class AuthenticateEvent{
	
	/**
	 *
	 * @var Config
	 */
	private $config;

	/**
	 *
	 * @var int
	 */
	private $maxTime=0;

	public function __construct(Config $config){
		$config=$config->getNodes('authenticate');
		if($config){
			$this->maxTime=$config[0]->getAttribute('max-time');
		}

	}

	/**
	 * Detect config authenticate.
	 *
	 * @param ExecuteActionEvent $event
	 * @param array $eventConfig
	 */
	public function onExecuteAction(ExecuteActionEvent $event){
		$request=$event->getRequest();
		$authenticates=$request->getConfig()->getNodes('authenticate');
		if($authenticates){
			$this->execute($event,$authenticates[0]);
		}
	}

	/**
	 * Check authenticate.
	 *
	 * @param ExecuteActionEvent $event
	 * @param array $config
	 * @param array $eventConfig
	 * @throws ValueNotFoundException
	 * @throws PermissionDeniedException
	 */
	private function execute(ExecuteActionEvent $event,$config){
		$request=$event->getRequest();
		$session=$request->getSession();

		try{
			$session->get('authenticate.user_id');
			if($this->maxTime>0){
				
				if($session->get('authenticate.epoch')<time()){ //deprecated session
					$session->clear();
					throw new ValueNotFoundException('authenticate.epoch');
				}
				$session->set('authenticate.epoch',time()+$this->maxTime);
			}

			if($config->getAttribute('auth-redirect')!==false){
				$response=$this->createResponseRedirect($config->getAttribute('auth-redirect'),$request);
				$event->setResponse($response);

			}
		}
		catch(ValueNotFoundException $e){
			if($config->getAttribute('unauth-redirect')!==false){
				$response=$this->createResponseRedirect($config->getAttribute('unauth-redirect'),$request);
				$event->setResponse($response);					
			}
			else if($config->getAttribute('auth-redirect')!==false){
				//ignore
			}
			else{
				throw new PermissionDeniedException();
			}
		}


	}

	/**
	 * Check authenticate.
	 *
	 * @param string $redirect
	 * @param Request $request
	 * @return Response
	 */
	private function createResponseRedirect($redirect,Request $request){
		$response=new Response();
		if($request->isAjax()){
			$response->setStatusCode(401);
			$response->setHeader('X-Location',$redirect);
		}
		else{
			$response->redirect($redirect);
		}
		return $response;
	}

}