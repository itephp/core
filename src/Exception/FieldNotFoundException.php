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
 * Throw when file not found in request provider.
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.16.0
 */
class FieldNotFoundException extends Exception{
	
	/**
	 * Constructor.
	 *
	 * @param string $name field name
	 * @since 0.16.0
	 */
	public function __construct($name){
		parent::__construct(4,'Field "'.$name.'" not found.','Internal server error.');
	}
}