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

use ItePHP\Core\ContenerServices;
use ItePHP\Provider\Request;
use ItePHP\Provider\Response;
use ItePHP\Provider\Session;
use ItePHP\Exception\ServiceNotFoundException;
use ItePHP\Exception\MethodNotFoundException;
use ItePHP\Core\ExecuteResources;

/**
 * Main class for project commands. Configured in config/commands.xml
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
abstract class Command extends Container{

	/**
	 * Write data to console output and break line.
	 *
	 * @param string $data
	 * @since 0.1.0
	 */
    public function writeLn($data){
    	echo $data."\n\r";
    }

	/**
	 * Write data to console output
	 *
	 * @param string $data
	 * @since 0.1.0
	 */
    public function write($data){
    	echo $data;
    }

}