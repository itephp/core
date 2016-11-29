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
 * Throw when response get not supported status code.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class InvalidStatusCodeException extends \Exception{
	
	/**
	 * Constructor.
	 *
	 * @param int $statusCode
	 */
	public function __construct($statusCode){
		parent::__construct('Invalid status code "'.$statusCode.'".');
	}
}