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
 * Throw when Request or Response can not find http header.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class HeaderNotFoundException extends Exception{
	
	/**
	 * Constructor.
	 *
	 * @param string $headerName
	 */	
	public function __construct($headerName){
		parent::__construct(11,"Header '".$headerName."' not found.","Internal server error.");
	}
}