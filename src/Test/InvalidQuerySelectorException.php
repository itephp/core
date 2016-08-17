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
 * Throw when BrowserEmulator get invalid query selector in methods getElement or findElements.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class InvalidQuerySelectorException extends \Exception{
	
	/**
	 * Constructor.
	 *
	 * @param string $query
	 */
	public function __construct($query){
		parent::__construct('Invalid query selector: '.$query.'.');
	}
}
