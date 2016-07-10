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

use ItePHP\Core\ContenerServices;
use ItePHP\Core\RequestProvider;
use ItePHP\Provider\Response;
use ItePHP\Provider\Session;
use ItePHP\Core\ExecuteResources;
use ItePHP\Core\EventManager;

use ItePHP\DependencyInjection\DependencyInjection;
use ItePHP\Core\MethodNotFoundException;

/**
 * Main class for project controllers
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
abstract class Controller{

	/**
	 * RequestProvider
	 *
	 * @var \ItePHP\Core\RequestProvider $request
	 */
	private $request;

	/**
	 * SessionProvider
	 *
	 * @var \ItePHP\Core\SessionProvider $session
	 */
	private $session;

	/**
	 * Snippets
	 *
	 * @var array $snippets
	 */
	private $snippets=[];	

	/**
	 *
	 * @var DependencyInjection
	 */
	private $dependencyInjection;

	/**
	 * Constructor.
	 *
	 * @param RequestProvider $request
	 * @param DependencyInjection $dependencyInjection
	 * @param array $snippets
	 */
	public function __construct(RequestProvider $request,DependencyInjection $dependencyInjection,$snippets){
		$this->request=$request;
		$this->session=$request->getSession();
		$this->dependencyInjection=$dependencyInjection;
		$this->snippets=$snippets;
	}

	/**
	 * Get request provider object
	 *
	 * @return \ItePHP\Core\RequestProvider
	 */
	public function getRequest(){
		return $this->request;
	}

	/**
	 * Get session provider object.
	 *
	 * @return \ItePHP\Core\SessionProvider
	 */
	public function getSession(){
		return $this->session;
	}

	/**
	 * Get Event manager
	 *
	 * @return \ItePHP\Core\EventManager
	 */
	public function getEventManager(){
		return $this->dependencyInjection->get('ite.eventManager');
	}

	/**
	 * Get service
	 *
	 * @param string $name service name
	 * @return object
	 */
	public function getService($name){
		return $this->dependencyInjection->get($name);
	}

	/**
	 * Execute snipper method.
	 *
	 * @param string $method
	 * @param array $args
	 * @return mixed
	 * @throws \ItePHP\Exception\MethodNotFoundException
	 */
	public function __call($method, $args){
		if(isset($snippets[$method])){
	        return call_user_func_array(array($snippets[$method], $method),
            array_merge([$this],$args)
			);

		}
		else{
			throw new MethodNotFoundException(get_class($this),$method);
		}

    }
}