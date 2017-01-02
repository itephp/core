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
 * Container with data for event "executeRender"
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class ExecuteRenderEvent{

	/**
	 * Request provider.
	 *
	 * @var Request $request
	 */
	private $request;

	/**
	 * Response.
	 *
	 * @var AbstractResponse $response
	 */
	private $response;

	/**
	 * Constructor.
	 *
	 * @param Request $request
	 * @param AbstractResponse $response
	 */
	public function __construct(Request $request, AbstractResponse $response){
		$this->request=$request;
		$this->response=$response;
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
	 * @param AbstractResponse $response
	 */
	public function setResponse(AbstractResponse $response){
		$this->response=$response;
	}

	/**
	 * Get response.
	 *
	 * @return AbstractResponse
	 */
	public function getResponse(){
		return $this->response;
	}
}