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
use \Exception;
use ItePHP\Core\HTTPException;
use ItePHP\Core\EventManager;
use ItePHP\Presenter\HTML as HTMLPresenter;
use ItePHP\Event\ExecutePresenterEvent;

/**
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.4.0
 */
class HTTPErrorHandler implements ErrorHandler{
	
	/**
	 *
	 * @var Enviorment
	 */ 
	private $enviorment;

	/**
	 *
	 * @var ConfigContainer
	 */ 
	private $config;

	/**
	 *
	 * @var EventManager
	 */ 
	private $eventManager;

	/**
	 *
	 * @param Enviorment $enviorment
	 * @param ConfigContainer $config
	 */
	public function __construct(Enviorment $enviorment,ConfigContainer $config,EventManager $eventManager){
		$this->enviorment=$enviorment;
		$this->config=$config;
		$this->eventManager=$eventManager;
	}

    /**
     * {@inheritdoc}
     */
	public function execute(Exception $exception){
		error_log($exception->getMessage()." ".$exception->getFile()."(".$exception->getLine().")");

		$presenter=$this->getPresenter();

		$response=new Response();
		$response->setStatusCode(500);
		$response->setContent($exception);
		if($exception instanceof HTTPException){
			$response->setStatusCode($exception->getStatusCode());
		}

		$event=new ExecutePresenterEvent($this->resources->getRequest(),$response);
		$this->eventManager->fire('executePresenter',$event);
		$this->execute($this->enviorment,$response);

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