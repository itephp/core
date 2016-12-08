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
use Pactum\ConfigContainer;

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
		foreach($request->getConfig()->getArray('argument') as $argument){
			$this->validateArgument($request,$argument,$position++);				
		}
	}

	/**
	 * Validate argument.
	 *
	 * @param Request $request
	 * @param ConfigContainer $config argument
	 * @param int $position
	 * @throws InvalidConfigValueException
	 * @throws InvalidArgumentException
	 */
	private function validateArgument(Request $request , ConfigContainer $config , $position){
		$value=null;
		switch($config->getValue('storage')){
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
				throw new InvalidConfigValueException('storage',$config->getValue('storage'));

		}

		$validators=$config->getArray('validator');
		if($validators){
		    $validatorManager=new ValidatorManager();
		    foreach($validators as $validator){
		        $validatorManager->addValidator($this->getValidator($validator));
            }

            $result=$validatorManager->validate($value);
            if(!$result->isValid()){
                throw new InvalidArgumentException($position,$config->getValue('name'),implode(', ',$result->getErrors()));
            }
		}

		$mapperName=$config->getValue('mapper');
		if($mapperName!==''){
            /**
             * @var AbstractMapper $mapper
             */
			$mapper=new $mapperName($this->container);
			$value=$mapper->cast($value);
		}
		$request->setArgument($config->getValue('name'),$value);

	}

    /**
     * @param ConfigContainer $validatorConfig
     * @return AbstractValidator
     */
	private function getValidator($validatorConfig){
	    $className=$validatorConfig->getValue('class');
	    $localClassLoader=new ClassLoader();

	    $metadata=new MetadataClass('main',$className);
        $localClassLoader->register($metadata);
	    foreach($validatorConfig->getArray('method') as $method){
            /**
             * @var ConfigContainer $method
             */
            $metadataMethod=$metadata->addMethod($method->getValue('name'));

	        foreach($method->getArray('argument') as $argument){
                /**
                 * @var ConfigContainer $argument
                 */
	            $type=$argument->getValue('type');
	            $value=$argument->getValue('value');
	            if($type===MetadataMethod::REFERENCE_TYPE){
	                $value=$this->classLoader->get($value);
                }

	            $metadataMethod->addArgument($type,$value);
            }
        }

        return $localClassLoader->get('main');
    }

	/**
	 * Validate url.
	 *
	 * @param Request $request
	 * @param ConfigContainer $config argument
	 * @param int $position
	 * @return string
	 * @throws RequiredArgumentException
	 */
	private function validateUrl(Request $request , ConfigContainer $config , $position){
		$url=$request->getUrl();
		$default=$config->getValue('default');
		if(preg_match('/^'.$config->getValue('pattern').'$/',$url,$matches) && isset($matches[1])){
			return $matches[1];			
		}
		else if($default!==false){
			return $config->getValue('default');
		}
		else{
			throw new RequiredArgumentException($position,$config->getValue('name'));
		}
	}

	/**
	 * Validate GET.
	 *
	 * @param string[] $data http post/get data
	 * @param ConfigContainer $config argument
	 * @param int $position
	 * @return string
	 * @throws RequiredArgumentException
	 */
	private function validateGetPost($data , ConfigContainer $config , $position){
		$argumentName=$config->getValue('name');
		$default=$config->getValue('default');
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