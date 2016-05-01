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

use ItePHP\Core\Exception\ServiceNotFoundException;
use ItePHP\Core\Exception\MethodNotFoundException;
use ItePHP\Core\Core\ExecuteResources;
use ItePHP\Core\Core\EventManager;

/**
 * Base class for Event, Command and Controller.
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
abstract class Container{

	/**
	 * Services.
	 *
	 * @var array $services
	 */
	private $services=array();

	/**
	 * Snippets.
	 *
	 * @var array $snippets
	 */
	private $snippets=array();	

	/**
	 * Constructor.
	 *
	 * @param \ItePHP\Core\Core\ExecuteResources $executeResources
	 * @since 0.1.0
	 */
	public function __construct(ExecuteResources $executeResources,EventManager $eventManager){
		$this->executeResources=$executeResources;
		$this->eventManager=$eventManager;
	}

	/**
	 * Get Event manager
	 *
	 * @return \ItePHP\Core\Core\EventManager
	 */
	public function getEventManager(){
		return $this->eventManager;
	}

	/**
	 * Get enviorment
	 *
	 * @return \ItePHP\Core\Core\Enviorment
	 * @since 0.1.0
	 */
	public function getEnviorment(){
		return $this->executeResources->getEnviorment();
	}

	/**
	 * Get service
	 *
	 * @param string $name service name
	 * @return object
	 * @throws \ItePHP\Core\Exception\ServiceNotFoundException
	 * @since 0.1.0
	 */
	public function getService($name){
		$services=$this->executeResources->getServices();
		if(!isset($services[$name]))
			throw new ServiceNotFoundException($name);

		return $services[$name];
	}

	/**
	 * Execute snipper method.
	 *
	 * @param string $method
	 * @param array $args
	 * @return mixed
	 * @throws \ItePHP\Core\Exception\MethodNotFoundException
	 * @since 0.1.0
	 */
	public function __call($method, $args){
		$snippets=$this->executeResources->getSnippets();
		if(isset($snippets[$method])){
	        return call_user_func_array(array($snippets[$method], $method),
            array_merge(array($this),$args)
			);

		}
		else{
			throw new MethodNotFoundException(get_class($this),$method);
		}

    }
}