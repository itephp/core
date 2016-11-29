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

namespace ItePHP\Action;

use ItePHP\Core\Exception;

/**
 * Throw when value not found in SessionProvider,ArrayList,Map.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class ValueNotFoundException extends Exception{
	
	/**
	 * Constructor.
	 *
	 * @param string $name
	 */
	public function __construct($name){
		parent::__construct(6,'Value "'.$name.'" not found.','Internal server error.');
	}
}