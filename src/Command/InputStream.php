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
class InputStream{

	/**
	 *
	 * @var array
	 */
	private $arguments=[];

	/**
	 *
	 * @param array $arguments
	 */
	public function __construct($arguments){
		$this->arguments=$arguments;
	}

	/**
	 *
	 * @param string $name
	 */
	public function getArgument($name){
		//TODO check exist argument
		return $this->arguments[$name];
	}

}