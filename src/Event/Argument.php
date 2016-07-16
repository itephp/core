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

namespace ItePHP\Event;

use ItePHP\Provider\Response;
use ItePHP\Event\ExecuteActionEvent;
use ItePHP\Core\InvalidConfigValueException;
use ItePHP\Core\RequiredArgumentException;
use ItePHP\Core\InvalidArgumentException;
use ItePHP\Core\RequestProvider;
use ItePHP\Config\ConfigContainerNode;
use ItePHP\Service\Validator;

/**
 * Event to foward http param ($_POST[],$_GET[],url) to controllr method.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
class Argument{

	/**
	 *
	 * @var Validator
	 */
	private $validator;

	/**
	 *
	 * @param Validator $validator 
	 */
	public function __construct(Validator $validator){
		$this->validator=$validator;
	}

	/**
	 * Detect config argument.
	 *
	 * @param \ItePHP\Event\ExecuteActionEvent $event
	 * @since 0.1.0
	 */
	public function onExecuteAction(ExecuteActionEvent $event){
		$request=$event->getRequest();
		$position=1;
		foreach($request->getConfig()->getNodes('argument') as $argument){
			$this->validateArgument($request,$argument,$position++);				
		}
	}

	/**
	 * Validate argument.
	 *
	 * @param \ItePHP\Core\RequestProvider $request
	 * @param ConfigContainerNode $config argument
	 * @param int $position
	 * @throws \ItePHP\Exception\InvalidConfigValueException
	 * @throws \ItePHP\Exception\InvalidArgumentException
	 * @since 0.1.0
	 */
	private function validateArgument(RequestProvider $request , $config , $position){
		$value=null;
		switch($config->getAttribute('storage')){
			case 'url':
				$value=$this->validateUrl($request , $config , $position);
			break;
			case 'post':
				$value=$this->validatePost($request->getData() , $config , $position);
			break;
			case 'get':
				$value=$this->validateGetPost($request->getQuery() , $config , $position);
			break;
			default:
				throw new InvalidConfigValueException('storage',$config->getAttribute('storage'));

		}

		$validatorName=$config->getAttribute('validator');
		if($validatorName!==''){

			$error=$this->validator->validate(new $validatorName(),$value);
			if($error){
				throw new InvalidArgumentException($position,$config->getAttribute('name'),$error);				
			}
		}

		$mapperName=$config->getAttribute('mapper');
		if($mapperName!==''){
			$mapper=new $mapperName();
			$value=$mapper->cast($value);
		}
		$request->setArgument($config->getAttribute('name'),$value);

	}

	/**
	 * Validate url.
	 *
	 * @param \ItePHP\Core\RequestProvider $request
	 * @param ConfigContainerNode $config argument
	 * @param int $position
	 * @return string
	 * @throws \ItePHP\Exception\RequiredArgumentException
	 * @since 0.1.0
	 */
	private function validateUrl(RequestProvider $request , $config , $position){
		$url=$request->getUrl();
		$default=$config->getAttribute('default');
		if(preg_match('/^'.$config->getAttribute('pattern').'$/',$url,$matches) && isset($matches[1])){
			return $matches[1];			
		}
		else if($default!==false){
			return $config['default'];			
		}
		else{
			throw new RequiredArgumentException($position,$config->getAttribute('name'));			
		}
	}

	/**
	 * Validate GET.
	 *
	 * @param array $data http post/get data
	 * @param ConfigContainerNode $config argument
	 * @param int $position
	 * @return string
	 * @throws \ItePHP\Exception\RequiredArgumentException
	 * @since 0.22.0
	 */
	private function validateGetPost($data , $config , $position){
		$argumentName=$config->getAttribute('name');
		$default=$config->getAttribute('default');
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