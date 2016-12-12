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

namespace ItePHP\Action;

use Config\Config\Action\Argument;
use Config\Config\Action\Argument\Validator;
use ItePHP\Mapper\AbstractMapper;
use ItePHP\Core\ExecuteActionEvent;
use ItePHP\Core\InvalidConfigValueException;
use ItePHP\Core\Request;
use ItePHP\Core\Container;
use Judex\AbstractValidator;
use Judex\ValidatorManager;
use Onus\ClassLoader;
use Onus\MetadataClass;
use Onus\MetadataMethod;

/**
 * Event to forward http param ($_POST[],$_GET[],url) to controller method.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class ArgumentEvent{

	/**
	 *
	 * @var Container
	 */
	private $container;
    /**
     * @var ClassLoader
     */
    private $classLoader;

    /**
     *
     * @param Container $container
     * @param ClassLoader $classLoader
     */
	public function __construct(Container $container,ClassLoader $classLoader){
		$this->container=$container;
		$this->classLoader=$classLoader;
	}

	/**
	 * Detect config argument.
	 *
	 * @param ExecuteActionEvent $event
	 */
	public function onExecuteAction(ExecuteActionEvent $event){
		$request=$event->getRequest();
		$position=1;
		foreach($request->getConfig()->getArgument() as $argument){
			$this->validateArgument($request,$argument,$position++);				
		}
	}

	/**
	 * Validate argument.
	 *
	 * @param Request $request
	 * @param Argument $config argument
	 * @param int $position
	 * @throws InvalidConfigValueException
	 * @throws InvalidArgumentException
	 */
	private function validateArgument(Request $request , Argument $config , $position){
		$value=null;
		switch($config->getStorage()){
			case 'url':
				$value=$this->validateUrl($request , $config , $position);
			break;
			case 'post':
				$value=$this->validateGetPost($request->getData() , $config , $position);
			break;
			case 'get':
				$value=$this->validateGetPost($request->getQuery() , $config , $position);
			break;
			default:
				throw new InvalidConfigValueException('storage',$config->getStorage());

		}

		$validators=$config->getValidator();
		if($validators){
		    $validatorManager=new ValidatorManager();
		    foreach($validators as $validator){
		        $validatorManager->addValidator($this->getValidator($validator));
            }

            $result=$validatorManager->validate($value);
            if(!$result->isValid()){
                throw new InvalidArgumentException($position,$config->getName(),implode(', ',$result->getErrors()));
            }
		}

		$mapperName=$config->getMapper();
		if($mapperName!==''){
            /**
             * @var AbstractMapper $mapper
             */
			$mapper=new $mapperName($this->container);
			$value=$mapper->cast($value);
		}
		$request->setArgument($config->getName(),$value);

	}

    /**
     * @param Validator $validatorConfig
     * @return AbstractValidator
     */
	private function getValidator(Validator $validatorConfig){
	    $className=$validatorConfig->getClass();
	    $localClassLoader=new ClassLoader();

	    $metadata=new MetadataClass('main',$className);
        $localClassLoader->register($metadata);
	    foreach($validatorConfig->getMethod() as $method){
            $metadataMethod=$metadata->addMethod($method->getName());

	        foreach($method->getArgument() as $argument){
	            $type=$argument->getType();
	            $value=$argument->getValue();
	            if($type===MetadataMethod::REFERENCE_TYPE){
	                $value=$this->classLoader->get($value);
                }

	            $metadataMethod->addArgument($type,$value);
            }
        }

        /**
         * @var AbstractValidator $main
         */
        $main=$localClassLoader->get('main');
        return $main;
    }

	/**
	 * Validate url.
	 *
	 * @param Request $request
	 * @param Argument $config argument
	 * @param int $position
	 * @return string
	 * @throws RequiredArgumentException
	 */
	private function validateUrl(Request $request , Argument $config , $position){
		$url=$request->getUrl();
		$default=$config->getDefault();
		if(preg_match('/^'.$config->getPattern().'$/',$url,$matches) && isset($matches[1])){
			return $matches[1];			
		}
		else if($default!==false){
			return $config->getDefault();
		}
		else{
			throw new RequiredArgumentException($position,$config->getName());
		}
	}

	/**
	 * Validate GET.
	 *
	 * @param string[] $data http post/get data
	 * @param Argument $config argument
	 * @param int $position
	 * @return string
	 * @throws RequiredArgumentException
	 */
	private function validateGetPost($data , Argument $config , $position){
		$argumentName=$config->getName();
		$default=$config->getDefault();
		if(!isset($data[$argumentName])){
			if($default!==false){
				return $default;
			}
			else{
				throw new RequiredArgumentException($position,$argumentName);				
			}
		}

		return $data[$argumentName];
	}


}