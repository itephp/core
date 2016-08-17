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

namespace ItePHP\DependencyInjection;

use \ReflectionClass;

/**
 * Manager for dependency injeciton
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class DependencyInjection{

	/**
	 *
	 * @var array
	 */
	private $metadataClasses=[];

	/**
	 *
	 * @var array
	 */
	private $instances=[];

	/**
	 *
	 * @param MetadataClass $metadaDataClass
	 */
	public function register(MetadataClass $metadaDataClass){
		//TODO exception already registered
		$this->metadataClasses[$metadaDataClass->getName()]=$metadaDataClass;
	}

	/**
	 *
	 * @param string $name
	 * @param object $object
	 */
	public function addInstance($name, $object){
		$this->instances[$name]=$object;
	}

	/**
	 *
	 * @param string $name
	 */
	public function get($name){
		if(!isset($this->instances[$name])){
			$this->instances[$name]=$this->createInstance($name);
		}

		return $this->instances[$name];

	}

	/**
	 *
	 * @param string $name
	 * @return object
	 * @throws InstanceNotFoundException
	 */
	private function createInstance($name){
		if(!isset($this->metadataClasses[$name])){
			throw new InstanceNotFoundException($name);
		}

		$metadataClass=$this->metadataClasses[$name];

		$className=$metadataClass->getClassName();
		$metadataConstructor=$this->getMetadataConstructor($metadataClass);
		$arguments=[];
		if($metadataConstructor){
			$arguments=$this->getMethodArguments($metadataConstructor);
		}

		$reflectionClass=new ReflectionClass($metadataClass->getClassName());
		$instance=$reflectionClass->newInstanceArgs($arguments);
		$this->invokeOtherMethods($instance,$metadataClass);

		return $instance;
	}

	/**
	 *
	 * @param MetadataClass $metadataClass
	 * @return MetadataMethod
	 */
	private function getMetadataConstructor(MetadataClass $metadataClass){
		foreach($metadataClass->getMethods() as $method){
			if($method->getName()==='__construct'){
				return $method;
			}
		}

		return null;
	}

	/**
	 *
	 * @param object $instance
	 * @param MetadataClass $metadataClass
	 */
	private function invokeOtherMethods($instance,MetadataClass $metadataClass){
		foreach($metadataClass->getMethods() as $method){
			if($method->getName()==='__construct'){
				continue;
			}

			call_user_func_array([$instance,$method->getName()], $this->getMethodArguments($method));
		}

	}

	/**
	 *
	 * @param MetadataMethod $metadata
	 * @return array
	 */
	private function getMethodArguments(MetadataMethod $metadata){
		$arguments=[];
		foreach ($metadata->getArguments() as $argument) {
			$value=null;

			switch($argument['type']){
				case MetadataMethod::PRIMITIVE_TYPE:
					$value=$argument['value'];
				break;
				case MetadataMethod::STATIC_TYPE:
					$value=constant($argument['value']);
				break;
				case MetadataMethod::REFERENCE_TYPE:
					$value=$this->get($argument['value']);
				break;
				default:
					//TODO throw exception type invalid
			}
			$arguments[]=$value;
		}
		return $arguments;
	}
}