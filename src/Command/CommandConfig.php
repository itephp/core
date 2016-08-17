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
 */
class CommandConfig{

	/**
	 *
	 * @var array
	 */
	private $arguments=[];

	/**
	 *
	 * @param string $name
	 * @param int $length
	 * @param mixed $default
	 */
	public function addArgument($name,$length=1,$default=null){
		//TODO check unique name
		$argument=new CommandArgument($name,$length);
		if($default!==null){
			$argument->setDefault($default);
		}
		$this->arguments[]=$argument;
	}

	/**
	 *
	 * @return array
	 */
	public function getArguments(){
		return $this->arguments;
	}
}