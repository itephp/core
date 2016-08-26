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
use ItePHP\Presenter\HTML as HTMLPresenter;
use ItePHP\DependencyInjection\DependencyInjection;

/**
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class HTTPErrorHandler implements ErrorHandler{
	
	/**
	 *
	 * @var DependencyInjection
	 */ 
	private $dependencyInjection;

	/**
	 *
	 * @var Request
	 */ 
	private $request;

	/**
	 *
	 * @param DependencyInjection $dependencyInjection
	 * @param Request $request
	 */
	public function __construct(DependencyInjection $dependencyInjection,Request $request){
		$this->dependencyInjection=$dependencyInjection;
		$this->request=$request;
	}

    /**
     * {@inheritdoc}
     */
	public function execute(\Exception $exception){
		if(!$this->dependencyInjection->get('environment')->isSilent()){
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
		$this->dependencyInjection->get('eventManager')->fire('executePresenter',$event);

		$presenter->render($this->request,$response);

	}

	/**
	 *
	 * @param string $url
	 * @return Presenter
	 */
	private function getPresenter($url){
        /**
         * @var Config $config
         */
        $config=$this->dependencyInjection->get('config');
		foreach($config->getNodes('error') as $error){
			if(!preg_match('/^'.$error->getAttribute('pattern').'$/',$url)){
				continue;
			}
			$presenterName=$error->getAttribute('presenter');
            /**
             * @var Presenter $presenterObject
             */
            $presenterObject=$this->dependencyInjection->get('presenter.'.$presenterName);
			return $presenterObject;
		}

		return new HTMLPresenter($this->dependencyInjection->get('enviorment'));

	}

}