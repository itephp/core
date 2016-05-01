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

use ItePHP\Core\Core\ContenerServices;
use ItePHP\Core\Core\RequestProvider;
use ItePHP\Core\Provider\Response;
use ItePHP\Core\Provider\Session;
use ItePHP\Core\Exception\ServiceNotFoundException;
use ItePHP\Core\Exception\MethodNotFoundException;
use ItePHP\Core\Core\ExecuteResources;
use ItePHP\Core\Core\EventManager;

/**
 * Main class for project controllers
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
abstract class Controller extends Container{

	/**
	 * RequestProvider
	 *
	 * @var \ItePHP\Core\Core\RequestProvider $request
	 */
	private $request;

	/**
	 * SessionProvider
	 *
	 * @var \ItePHP\Core\Core\SessionProvider $session
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
	 * @param \ItePHP\Core\Core\RequestProvider $request
	 * @param \ItePHP\Core\Core\ExecuteResources $executeResources
	 * @param \ItePHP\Core\Core\EventManager $eventManager
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
	 * @return \ItePHP\Core\Core\RequestProvider
	 * @since 0.1.0
	 */
	public function getRequest(){
		return $this->request;
	}

	/**
	 * Get session provider object.
	 *
	 * @return \ItePHP\Core\Core\SessionProvider
	 * @since 0.1.0
	 */
	public function getSession(){
		return $this->session;
	}
}