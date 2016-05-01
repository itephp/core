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

namespace ItePHP\Core\Core;

use ItePHP\Core\Core\ContenerServices;
use ItePHP\Core\Provider\Request;
use ItePHP\Core\Provider\Response;
use ItePHP\Core\Provider\Session;
use ItePHP\Core\Exception\ServiceNotFoundException;
use ItePHP\Core\Exception\MethodNotFoundException;
use ItePHP\Core\Core\ExecuteResources;

/**
 * Main class for project commands. Configured in config/commands.xml
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
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