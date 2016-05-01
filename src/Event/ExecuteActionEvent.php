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

namespace ItePHP\Event;

use ItePHP\Core\RequestProvider;
use ItePHP\Provider\Response;

/**
 * Contener with data for event "executeAction"
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
class ExecuteActionEvent{

	/**
	 * Request provider.
	 *
	 * @var \ItePHP\Core\RequestProvider $request
	 */
	private $request;

	/**
	 * Response.
	 *
	 * @var \ItePHP\Provider\Response $response
	 */
	private $response;

	/**
	 * Constructor.
	 *
	 * @param \ItePHP\Core\RequestProvider $request
	 * @since 0.1.0
	 */
	public function __construct(RequestProvider $request){
		$this->request=$request;
	}

	/**
	 * Get request.
	 *
	 * @return \ItePHP\Core\RequestProvider
	 * @since 0.1.0
	 */
	public function getRequest(){
		return $this->request;
	}

	/**
	 * Set response.
	 *
	 * @param \ItePHP\Provider\Response $response
	 * @since 0.1.0
	 */
	public function setResponse(Response $response){
		$this->response=$response;
	}

	/**
	 * Get response.
	 *
	 * @return \ItePHP\Provider\Response
	 * @since 0.1.0
	 */
	public function getResponse(){
		return $this->response;
	}
}