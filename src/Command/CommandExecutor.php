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

namespace ItePHP\Command;

/**
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.4.0
 */
class CommandExecutor{

	/**
	 *
	 * @var Command
	 */
	private $commandObject;

	/**
	 *
	 * @var array
	 */
	private $arguments=[];

	/**
	 *
	 * @param Command $commandObject
	 */
	public function __construct(Command $commandObject){
		$this->commandObject=$commandObject;
	}

	/**
	 *
	 * @param mixed $argument
	 */
	public function addArgument(mixed $argument){
		$this->arguments[]=$argument;
	}

	public function run(){
		//TODO veryfi config data
		call_user_func_array([$this->commandObject,'execute'], $this->arguments);
	}
}

?>
