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

/**
 * Contener with data for event "executeAction"
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class ExecuteActionEvent{

	/**
	 * Request provider.
	 *
	 * @var Request $request
	 */
	private $request;

	/**
	 * Response.
	 *
	 * @var Response $response
	 */
	private $response;

	/**
	 * Constructor.
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request){
		$this->request=$request;
	}

	/**
	 * Get request.
	 *
	 * @return Request
	 */
	public function getRequest(){
		return $this->request;
	}

	/**
	 * Set response.
	 *
	 * @param Response $response
	 */
	public function setResponse(Response $response){
		$this->response=$response;
	}

	/**
	 * Get response.
	 *
	 * @return Response
	 */
	public function getResponse(){
		return $this->response;
	}
}