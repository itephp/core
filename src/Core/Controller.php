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
use ItePHP\Exception\ServiceNotFoundException;
use ItePHP\Exception\MethodNotFoundException;
use ItePHP\Core\ExecuteResources;
use ItePHP\Core\EventManager;

/**
 * Main class for project controllers
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
abstract class Controller extends Container{

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
	 * Services
	 *
	 * @var array $services
	 */
	private $services=array();

	/**
	 * Snippets
	 *
	 * @var array $snippets
	 */
	private $snippets=array();	

	/**
	 * Constructor.
	 *
	 * @param \ItePHP\Core\RequestProvider $request
	 * @param \ItePHP\Core\ExecuteResources $executeResources
	 * @param \ItePHP\Core\EventManager $eventManager
	 * @since 0.1.0
	 */
	public function __construct(RequestProvider $request, ExecuteResources $executeResources,EventManager $eventManager){
		$this->request=$request;
		$this->session=$request->getSession();
		parent::__construct($executeResources,$eventManager);
	}

	/**
	 * Get request provider object
	 *
	 * @return \ItePHP\Core\RequestProvider
	 * @since 0.1.0
	 */
	public function getRequest(){
		return $this->request;
	}

	/**
	 * Get session provider object.
	 *
	 * @return \ItePHP\Core\SessionProvider
	 * @since 0.1.0
	 */
	public function getSession(){
		return $this->session;
	}
}