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

namespace ItePHP\Config;

/**
 * Config container.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.4.0
 */
class ConfigContainer{

	/**
	 *
	 * @var array
	 */	
	private $nodes=[];

	/**
	 *
	 * @param array $nodes
	 */	
	public function __construct($nodes){
		$this->nodes=$nodes;
	}

	/**
	 *
	 * @param string $name
	 * @return array
	 * @throws ConfigException
	 */	
	public function getNodes($name){
		if(!isset($this->nodes[$name])){
			throw new ConfigException('Node '.$name.' not found.');
		}
		return $this->nodes[$name];
	}
}