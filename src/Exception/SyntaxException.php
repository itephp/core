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

namespace ItePHP\Exception;
use ItePHP\Core\Exception;

/**
 * Throw when in php file is synax error.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
class SyntaxException extends Exception{
	
	/**
	 * Constructor.
	 *
	 * @param string $errfile
	 * @param int $errline
	 * @param string $errstr
	 * @since 0.1.0
	 */
	public function __construct($errfile,$errline,$errstr){
		parent::__construct(12,$errstr,"Internal server error.",$errfile,$errline);
	}
}