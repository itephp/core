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

use ItePHP\Core\Event;
use ItePHP\Provider\Response;
use ItePHP\Core\ExecuteActionEvent;
use ItePHP\Action\ValueNotFoundException;
use ItePHP\Action\PermissionDeniedException;
use ItePHP\Core\RequestProvider;

/**
 * Event for support authenticate user
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
class Authenticate extends Event{
	
	/**
	 * Detect config authenticate.
	 *
	 * @param \ItePHP\Event\ExecuteActionEvent $event
	 * @param array $eventConfig
	 * @since 0.1.0
	 */
	public function onExecuteAction(ExecuteActionEvent $event,$eventConfig){
		$request=$event->getRequest();
		foreach($request->getExtra() as $extra){
			foreach($extra as $parameter=>$config){
				if($parameter=='authenticate'){
					$this->execute($event,$config,$eventConfig);
				}
			}
		}

	}

	/**
	 * Check authenticate.
	 *
	 * @param \ItePHP\Event\ExecuteActionEvent $event
	 * @param array $config
	 * @param array $eventConfig
	 * @throws \ItePHP\Exception\ValueNotFoundException
	 * @throws PermissionDeniedException
	 * @since 0.1.0
	 */
	private function execute(ExecuteActionEvent $event,$config,$eventConfig){
		$request=$event->getRequest();
		$session=$request->getSession();
		$maxTime=(isset($eventConfig['maxTime'])?$eventConfig['maxTime']:0);

		try{
			$session->get('user.id');
			if($maxTime>0){
				
				if($session->get('session.epoch')<time()){ //deprecated session
					$session->clear();
					throw new ValueNotFoundException('session.epoch');
				}

				$session->set('session.epoch',time()+$maxTime);
			}

			if(isset($config['session-redirect'])){
				$response=$this->createResponseRedirect($config['session-redirect'],$request);
				$event->setResponse($response);					

			}
		}
		catch(ValueNotFoundException $e){
			if(isset($config['incognito']) && $config['incognito']=='true'){
				//IGNORED
			}
			else if(isset($config['redirect'])){
				$response=$this->createResponseRedirect($config['redirect'],$request);
				$event->setResponse($response);					
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
	 * @param \ItePHP\Core\RequestProvider $request
	 * @return \ItePHP\Provider\Response
	 * @since 0.1.0
	 */
	private function createResponseRedirect($redirect,RequestProvider $request){
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