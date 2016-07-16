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

use ItePHP\Core\Exception;

/**
 * Throw when event argument can not parse http param.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
class InvalidArgumentException extends Exception{

	/**
	 * Constructor.
	 *
	 * @param int $position
	 * @param string $name
	 * @param string $message
	 * @since 0.1.0
	 */	
	public function __construct($position,$name,$message){
		parent::__construct(200+$position,'Invalid argument "'.$name.'": '.$message);
	}
}
