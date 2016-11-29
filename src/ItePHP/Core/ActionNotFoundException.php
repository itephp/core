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
 * Throw when action in config/actions.xml not found
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class ActionNotFoundException extends Exception{
	
	/**
	 * Constructor
	 *
	 * @param string $controller
	 * @param string $method
	 */	
	public function __construct($controller , $method){
		parent::__construct(4,'Action "'.$controller.'" for action "'.$method.'" not found.','Internal server error.');
	}
}