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
use ItePHP\Core\Container;

/**
 * Main class for project controllers
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
abstract class Controller{

	/**
	 * RequestProvider
	 *
	 * @var RequestProvider $request
	 */
	private $request;

	/**
	 * SessionProvider
	 *
	 * @var SessionProvider $session
	 */
	private $session;

	/**
	 *
	 * @var Container
	 */
	private $container;

	/**
	 * Constructor.
	 *
	 * @param RequestProvider $request
	 * @param Container $container
	 */
	public function __construct(RequestProvider $request,Container $container){
		$this->request=$request;
		$this->session=$request->getSession();
		$this->container=$container;
	}

	/**
	 *
	 * @return RequestProvider
	 */
	public function getRequest(){
		return $this->request;
	}

	/**
	 *
	 * @return SessionProvider
	 */
	public function getSession(){
		return $this->session;
	}

	/**
	 * Get Event manager
	 *
	 * @return EventManager
	 */
	public function getEventManager(){
		return $this->container->getEventManager();
	}

	/**
	 * Get service
	 *
	 * @param string $name service name
	 * @return object
	 */
	public function getService($name){
		return $this->container->getService($name);
	}

	/**
	 * Execute snipper method.
	 *
	 * @param string $method
	 * @param array $args
	 * @return mixed
	 * @throws MethodNotFoundException
	 */
	public function __call($method, $args){
		$snippet=$this->container->getSnippet($method);
        return call_user_func_array([$snippet, $method],array_merge([$this->container],$args));
    }
}