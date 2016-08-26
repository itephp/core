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
 * Manager for dependency injection.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class DependencyInjection{

	/**
	 *
	 * @var MetadataClass[]
	 */
	private $metadataClasses=[];

	/**
	 *
	 * @var object[]
	 */
	private $instances=[];

    /**
     *
     * @param MetadataClass $metadataClass
     * @throws MetadataAlreadyRegisteredException
     */
	public function register(MetadataClass $metadataClass){
	    if(isset($this->metadataClasses[$metadataClass->getName()])){
	        throw new MetadataAlreadyRegisteredException($metadataClass->getName());
        }

		$this->metadataClasses[$metadataClass->getName()]=$metadataClass;
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
     * @return object
     * @throws InstanceNotFoundException
     */
	public function get($name){

        if(isset($this->instances[$name]) && !isset($this->metadataClasses[$name])){
            return $this->instances[$name];
        }

        if(!isset($this->metadataClasses[$name])){
            throw new InstanceNotFoundException($name);
        }

        $metaData=$this->metadataClasses[$name];
		if(!$metaData->isSingleton() || !isset($this->instances[$name])){
			$this->instances[$name]=$this->createInstance($metaData);
		}

		return $this->instances[$name];

	}

    /**
     *
     * @param MetadataClass $metadataClass
     * @return object
     * @throws InstanceNotFoundException
     */
	private function createInstance(MetadataClass $metadataClass){

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
     * @return \mixed[]
     * @throws InvalidTypeException
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
					throw new InvalidTypeException($argument['type']);
			}
			$arguments[]=$value;
		}
		return $arguments;
	}
}