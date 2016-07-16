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

use \Exception;

/**
 * Throw when in php file is synax error.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.4.0
 */
class HTTPException extends Exception{
	
	/**
	 * Constructor.
	 *
	 * @param int $statusCode
	 * @param string $message
	 * @since 0.1.0
	 */
	public function __construct($statusCode,$message){
		$this->statusCode=$statusCode;
		parent::__construct($message);
	}

	/**
	 *
	 * @return int
	 */
	public function getStatusCode(){
		return $this->statusCode;
	}
}