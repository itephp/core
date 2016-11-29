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
 */
class ConfigContainer{
	
	/**
	 *
	 * @var string[]
	 */
	private $attributes=[];

	/**
	 *
	 * @var ConfigContainer[][]
	 */
	private $nodes=[];

	/**
	 *
	 * @param ConfigContainer[] $nodes
	 * @param string[] $attributes
	 */
	public function __construct($nodes, $attributes){
		$this->nodes=$nodes;
		$this->attributes=$attributes;
	}

	/**
	 *
	 * @param string $name
	 * @return ConfigContainer[]
	 * @throws ConfigException
	 */	
	public function getNodes($name){
		if(!isset($this->nodes[$name])){
			throw new ConfigException('Node '.$name.' not found.');
		}
		return $this->nodes[$name];
	}

	/**
	 *
	 * @param string $name
	 * @return string
	 * @throws ConfigException
	 */	
	public function getAttribute($name){
		if(!isset($this->attributes[$name])){
			throw new ConfigException('Argument '.$name.' not found.');
		}
		return $this->attributes[$name];
	}

}