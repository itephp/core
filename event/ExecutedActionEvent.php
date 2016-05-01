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

namespace ItePHP\Core\Event;

use ItePHP\Core\Core\RequestProvider;
use ItePHP\Core\Provider\Response;

/**
 * Contener with data for event "executedAction"
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
class ExecutedActionEvent{

	/**
	 * Request provider.
	 *
	 * @var \ItePHP\Core\Core\RequestProvider $request
	 */
	private $request;

	/**
	 * Response.
	 *
	 * @var \ItePHP\Core\Provider\Response $response
	 */
	private $response;

	/**
	 * Constructor.
	 *
	 * @param \ItePHP\Core\Core\RequestProvider $request
	 * @param \ItePHP\Core\Provider\Response $response
	 * @since 0.1.0
	 */
	public function __construct(RequestProvider $request , Response $response){
		$this->request=$request;
		$this->response=$response;
	}

	/**
	 * Get response.
	 *
	 * @return \ItePHP\Core\Provider\Response
	 * @since 0.1.0
	 */
	public function getResponse(){
		return $this->response;
	}

	/**
	 * Get request
	 *
	 * @return \ItePHP\Core\Core\RequestProvider
	 * @since 0.1.0
	 */
	public function getRequest(){
		return $this->request;
	}
}