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

use Config\Config;
use ItePHP\Error\ErrorHandler;
use ItePHP\Presenter\HTMLResponse;
use Onus\ClassLoader;

/**
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class HTTPErrorHandler implements ErrorHandler{
	
	/**
	 *
	 * @var ClassLoader
	 */ 
	private $classLoader;

	/**
	 *
	 * @var Request
	 */ 
	private $request;

	/**
	 *
	 * @param ClassLoader $classLoader
	 * @param Request $request
	 */
	public function __construct(ClassLoader $classLoader, Request $request){
		$this->classLoader=$classLoader;
		$this->request=$request;
	}

    /**
     * {@inheritdoc}
     */
	public function execute(\Exception $exception){
		if(!$this->classLoader->get('environment')->isSilent()){
			error_log($exception->getMessage()." ".$exception->getFile()."(".$exception->getLine().")");
		}

 		$presenter=$this->getResponse($this->request->getUrl());

		$presenter->setStatusCode(500);
        $presenter->setContent($exception);
		if($exception instanceof HTTPException){
            $presenter->setStatusCode($exception->getStatusCode());
		}

		$event=new ExecuteRenderEvent($this->request,$presenter);
		$this->classLoader->get('eventManager')->fire('executePresenter',$event);

		$presenter->render();

	}

	/**
	 *
	 * @param string $url
	 * @return AbstractResponse
	 */
	private function getResponse($url){
        /**
         * @var Config $config
         */
        $config=$this->classLoader->get('config');
		foreach($config->getError() as $error){
			if(!preg_match('/^'.$error->getPattern().'$/',$url)){
				continue;
			}
			$responseName=$error->getResponse();
            /**
             * @var AbstractResponse $responseObject
             */
            $responseObject=$this->classLoader->get('response.'.$responseName);
			return $responseObject;
		}

		return new HTMLResponse();
	}

}