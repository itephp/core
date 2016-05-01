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

namespace ItePHP\Core\Exception;
use ItePHP\Core\Core\Exception;

/**
 * Throw when controller doesn't have executed method.
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
class MethodNotFoundException extends Exception{
	
	/**
	 * Constructor.
	 *
	 * @param string $className
	 * @param string $methodName
	 * @since 0.1.0
	 */
	public function __construct($className,$methodName){
		parent::__construct(9,"Method '".$className."::".$methodName."' not found.","Internal server error.");
	}
}