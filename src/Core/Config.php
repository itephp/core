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

use ItePHP\Config\ConfigContainer;
use ItePHP\Config\ConfigException;

/**
 * Framework config.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class Config{

	/**
	 *
	 * @var ConfigContainer
	 */	
	private $container;

	/**
	 *
	 * @var string[]
	 */	
	private static $variables=[];

	/**
	 *
	 * @param ConfigContainer $configContainer
	 * @param boolean $root
	 */	
	public function __construct(ConfigContainer $configContainer,$root=true){
		$this->container=$configContainer;
		if(!$root){
			return;
		}

		try{
			$variables=$this->container->getNodes('variable');
		}
		catch(ConfigException $e){
			//variable not found. Break
			return;
		}

		foreach($variables as $variable){
			static::$variables[$variable->getAttribute('name')]=$variable->getAttribute('value');
		}
	}

	/**
	 *
	 * @param string $name
	 * @return Config[]
	 * @throws ConfigException
	 */	
	public function getNodes($name){
		$nodes=[];
		foreach($this->container->getNodes($name) as $node){
			$nodes[]=new Config($node,false);
		}

		return $nodes;
	}

	/**
	 *
	 * @param string $name
	 * @return string
	 * @throws ConfigException
	 */	
	public function getAttribute($name){
		$value=$this->container->getAttribute($name);
        switch (substr($value,0,1)){
            case '@':
                return $this->getAttributeVariable($value);
            case '!':
                return $this->getAttributePrimitive($value);
            default:
                return $value;
        }

	}

    /**
     * @param string $value
     * @return string
     * @throws ConfigException
     */
    private function getAttributeVariable($value){
        $variableName=substr($value, 1);
        if(!isset(static::$variables[$variableName])){
            throw new ConfigException('Variable '.$variableName.' not found.');
        }

        return static::$variables[$variableName];
    }

    /**
     * @param string $value
     * @return mixed
     * @throws ConfigException
     */
    private function getAttributePrimitive($value){
        $primitiveValue=substr($value, 1);
        if(preg_match('/^true|false$/',$primitiveValue)){
            return $primitiveValue==='true';
        }

        if(preg_match('/^[0-9]+$/',$primitiveValue)){
            return (int)$primitiveValue;
        }

        if(preg_match('/^[0-9]+\.[0-9]+$/',$primitiveValue)){
            return (float)$primitiveValue;
        }

        if($primitiveValue==='null'){
            return null;
        }

        throw new ConfigException('Invalid primitive data value '.$primitiveValue.'.');

    }

}