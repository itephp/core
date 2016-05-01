<?php

/**
 * ItePHP: Freamwork PHP (http://php.iteracja.com)
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the file LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) NewClass (http://newclass.pl)
 * @link          http://php.iteracja.com ItePHP Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace ItePHP\Core\Core;

use ItePHP\Core\Contener\GlobalConfig;
use ItePHP\Core\Core\Enviorment;
use ItePHP\Core\Contener\RequestConfig;
use ItePHP\Core\Contener\CommandConfig;
use ItePHP\Core\Exception\RouteNotFoundException;
use ItePHP\Core\Exception\CommandNotFoundException;
use ItePHP\Core\Core\HttpDispatcher;
use ItePHP\Core\Core\HttpTestDispatcher;
use ItePHP\Core\Core\CommandDispatcher;

/**
 * Factory for dispatcher. 
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
class Router{
	

	/**
	 * Create http dispatcher.
	 *
	 * @param \ItePHP\Core\Core\Enviorment $enviorment
	 * @param \ItePHP\Core\Contener\GlobalConfig $config
	 * @param string $url
	 * @return \ItePHP\Core\Core\HttpDispatcher
	 * @throws \ItePHP\Core\Exception\RouteNotFoundException
	 * @since 0.1.0
	 */
	public function createHttpDispatcher(Enviorment $enviorment,GlobalConfig $config,$url){

		$requestConfig=$this->findRecources($enviorment,$config,$url);
		if(!$requestConfig){
			$requestConfig=$this->findAction($enviorment,$config,$url);
		}


		if($requestConfig){
			return new HttpDispatcher($requestConfig);
		}

		throw new RouteNotFoundException($url);

	}

	/**
	 * Create http test dispatcher.
	 *
	 * @param \ItePHP\Core\Core\Enviorment $enviorment
	 * @param \ItePHP\Core\Contener\GlobalConfig $config
	 * @param string $url
	 * @return \ItePHP\Core\Core\HttpTestDispatcher
	 * @throws \ItePHP\Core\Exception\RouteNotFoundException
	 * @since 0.1.0
	 */
	public function createHttpTestDispatcher(Enviorment $enviorment,GlobalConfig $config,$url){

		$requestConfig=$this->findRecources($enviorment,$config,$url);
		if(!$requestConfig){
			$requestConfig=$this->findAction($enviorment,$config,$url);
		}


		if($requestConfig){
			return new HttpTestDispatcher($requestConfig);
		}

		throw new RouteNotFoundException($url);

	}

	/**
	 * Create command dispatcher.
	 *
	 * @param \ItePHP\Core\Core\Enviorment $enviorment
	 * @param \ItePHP\Core\Contener\GlobalConfig $config
	 * @param string $commandName
	 * @param array $arguments
	 * @return \ItePHP\Core\Core\CommandDispatcher
	 * @throws \ItePHP\Core\Exception\CommandNotFoundException
	 * @since 0.1.0
	 */
	public function createCommandDispatcher(Enviorment $enviorment,GlobalConfig $config,$commandName,$arguments){

		$commandConfig=$this->findCommand($config,$commandName);


		if($commandConfig){
			return new CommandDispatcher($commandConfig,$arguments);
		}

		throw new CommandNotFoundException($commandName);

	}

	/**
	 * Find resource routing
	 *
	 * @param \ItePHP\Core\Core\Enviorment $enviorment
	 * @param \ItePHP\Core\Contener\GlobalConfig $config
	 * @param string $url
	 * @return \ItePHP\Core\Contener\RequestConfig
	 * @since 0.1.0
	 */
	private function findRecources(Enviorment $enviorment,GlobalConfig $config,$url){
		foreach($config->getResources() as $resource){
			if(preg_match('/^'.$resource['pattern'].'$/',$url)){
				return new RequestConfig('Resource','download',$enviorment,
					array(
						'route'=>$url
						,'presenter'=>array('class' => 'ItePHP\Core\\Presenter\\File')
						,'class'=>'ItePHP\Core\\Controller\\Resource'
						,'extra'=>array(array('expire'=>$resource['expire'],'pattern'=>$resource['pattern'],'path'=>$resource['path']))
						));
			}
		}

	}

	/**
	 * Find action routing
	 *
	 * @param \ItePHP\Core\Core\Enviorment $enviorment
	 * @param \ItePHP\Core\Contener\GlobalConfig $config
	 * @param string $url
	 * @return \ItePHP\Core\Contener\RequestConfig
	 * @since 0.1.0
	 */
	private function findAction(Enviorment $enviorment,GlobalConfig $config,$url){
		foreach($config->getMethods() as $methodName=>$method){
			if(preg_match('/^'.$method['route']['pattern'].'$/',$url)){
				$actionPart=explode(':',$methodName);
				$method['class']='Controller\\'.$actionPart[0];
				return new RequestConfig($actionPart[0],$actionPart[1],$enviorment,$method);
			}
		}

	}

	/**
	 * Find resource routing
	 *
	 * @param \ItePHP\Core\Contener\GlobalConfig $config
	 * @param string $commandRequestName
	 * @return \ItePHP\Core\Contener\CommandConfig
	 * @since 0.1.0
	 */
	private function findCommand(GlobalConfig $config,$commandRequestName){

		foreach($config->getCommands() as $commandName=>$command){
			if(preg_match('/^'.$commandRequestName.'$/',$commandName)){
				$method['class']=$command['class'];
				return new CommandConfig($commandName,$command['class'],$command['method']);
			}
		}
	}

}