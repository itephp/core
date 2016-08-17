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

namespace ItePHP\Test;

/**
 * Response provider for functionalit test
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class Response{
	private $response;
	private $content;

	public function __construct(\ItePHP\Provider\Response $response,$content){
		$this->response=$response;
		$this->content=$content;
	}

	public function getContent(){
		return $this->content;
	}

	public function getStatusCode(){
		return $this->response->getStatusCode();
	}

}