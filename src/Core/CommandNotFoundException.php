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
 * Throw when executed url not configured in config/actions.xml
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
class CommandNotFoundException extends \Exception{
	
	/**
	 * Constructor.
	 *
	 * @param string $commandName
	 * @since 0.4.0
	 */
	public function __construct($commandName){
		parent::__construct('Command '.$commandName.' not found.');
	}
}