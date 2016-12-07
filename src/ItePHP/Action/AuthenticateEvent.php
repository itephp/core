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
use ItePHP\Core\Request;
use Pactum\ConfigContainer;

/**
 * Event for support authenticate user
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class AuthenticateEvent{

	/**
	 *
	 * @var int
	 */
	private $maxTime=0;

    /**
     * AuthenticateEvent constructor.
     * @param ConfigContainer $config
     */
	public function __construct(ConfigContainer $config){
        /**
         * @var ConfigContainer[] $authenticate
         */
		$authenticate=$config->getObject('authenticate');
		if($authenticate){
            $this->maxTime=$authenticate->getValue('max-time');
        }

	}

	/**
	 * Detect config authenticate.
	 *
	 * @param ExecuteActionEvent $event
	 */
	public function onExecuteAction(ExecuteActionEvent $event){
		$request=$event->getRequest();
		$authenticate=$request->getConfig()->getObject('authenticate');
		if($authenticate){
			$this->execute($event,$authenticate);
		}
	}

	/**
	 * Check authenticate.
	 *
	 * @param ExecuteActionEvent $event
	 * @param ConfigContainer $config
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

			if($config->getValue('auth-redirect')!==false){
				$response=$this->createResponseRedirect($config->getValue('auth-redirect'),$request);
				$event->setResponse($response);

			}
		}
		catch(ValueNotFoundException $e){
			if($config->getValue('unauth-redirect')!==false){
				$response=$this->createResponseRedirect($config->getValue('unauth-redirect'),$request);
				$event->setResponse($response);					
			}
			else if($config->getValue('auth-redirect')!==false){
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