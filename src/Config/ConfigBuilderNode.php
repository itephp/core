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
 * Config builder.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.4.0
 */
class ConfigBuilderNode{

	/**
	 *
	 * @var string
	 */	
	private $name;

	/**
	 *
	 * @var array
	 */	
	private $attributes=[];

	/**
	 *
	 * @var array
	 */	
	private $nodes=[];

	/**
	 *
	 * @param string $name
	 */	
	public function __construct($name){
		$this->name=$name;
	}

	/**
	 *
	 * @return string
	 */	
	public function getName(){
		return $this->name;
	}

	/**
	 *
	 * @param string $name
	 * @param boolean $required
	 * @param string default
	 */	
	public function addAttribute($name,$default=null){
		$this->attributes[$name]=new ConfigBuilderArgument($name,$default);
	}

	/**
	 *
	 * @return array
	 */	
	public function getAttributes(){
		return array_values($this->attributes);
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

	/**
	 *
	 * @param ConfigBuilderNode $node
	 */	
	public function addNode(ConfigBuilderNode $node){
		$this->nodes[$node->getName()]=$node;
	}

	/**
	 *
	 * @return array
	 */	
	public function getNodes(){
		return array_values($this->nodes);
	}

	/**
	 *
	 * @param string $name
	 * @return ConfigBuilderNode
	 * @throws ConfigException
	 */	
	public function getNode($name){
		if(!isset($this->nodes[$name])){
			throw new ConfigException('Node '.$name.' not found.');
		}
		return $this->nodes[$name];
	}

}