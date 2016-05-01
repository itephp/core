<?php

/**
 * ItePHP: Freamwork PHP (http://php.iteracja.com)
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the file LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) NewClass (http://newclass.pl)
 * @link          http://php.iteracja.com ItePHP Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace ItePHP\Core\Event;

use ItePHP\Core\Core\Event;
use ItePHP\Core\Provider\Response;
use ItePHP\Core\Event\ExecuteActionEvent;
use ItePHP\Core\Exception\ValueNotFoundException;
use ItePHP\Core\Exception\InvalidConfigValueException;
use ItePHP\Core\Exception\RequiredArgumentException;
use ItePHP\Core\Exception\InvalidArgumentException;
use ItePHP\Core\Core\RequestProvider;

/**
 * Event to foward http param ($_POST[],$_GET[],url) to controllr method.
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
class Argument extends Event{
	
	/**
	 * Detect config argument.
	 *
	 * @param \ItePHP\Core\Event\ExecuteActionEvent $event
	 * @since 0.1.0
	 */
	public function onExecuteAction(ExecuteActionEvent $event){

		$request=$event->getRequest();
		$position=1;
		foreach($request->getExtra() as $extra){
			foreach($extra as $parameter=>$config){
				if($parameter=='argument')
					$this->validateArgument($request,$config,$position++);				
			}
		}
	}

	/**
	 * Validate argument.
	 *
	 * @param \ItePHP\Core\Core\RequestProvider $request
	 * @param array $config argument
	 * @param int $position
	 * @throws \ItePHP\Core\Exception\InvalidConfigValueException
	 * @throws \ItePHP\Core\Exception\InvalidArgumentException
	 * @since 0.1.0
	 */
	private function validateArgument(RequestProvider $request , $config , $position){
		$value=null;
		switch($config['storage']){
			case 'url':
				$value=$this->validateUrl($request , $config , $position);
			break;
			case 'post':
				$value=$this->validatePost($request , $config , $position);
			break;
			case 'get':
				$value=$this->validateGet($request , $config , $position);
			break;
			default:
				throw new InvalidConfigValueException('storage',$config['storage']);

		}

		if(isset($config['validator'])){
			$validator=$this->getService('validator');

			$error=$validator->validate(new $config['validator'](),$value);
			if($error)
				throw new InvalidArgumentException($position,$config['name'],$error);
		}

		if(isset($config['mapper'])){
			$mapper=new $config['mapper']($this);
			$value=$mapper->cast($value);
		}
		$request->setArgument($config['name'],$value);

	}

	/**
	 * Validate url.
	 *
	 * @param \ItePHP\Core\Core\RequestProvider $request
	 * @param array $config argument
	 * @param int $position
	 * @return string
	 * @throws \ItePHP\Core\Exception\RequiredArgumentException
	 * @since 0.1.0
	 */
	private function validateUrl(RequestProvider $request , $config , $position){
		$url=$request->getUrl();

		if(preg_match('/^'.$config['pattern'].'$/',$url,$matches) && isset($matches[1]))
			return $matches[1];
		else if(isset($config['default']))
			return $config['default'];
		else
			throw new RequiredArgumentException($position,$config['name']);
	}

	/**
	 * Validate POST.
	 *
	 * @param \ItePHP\Core\Core\RequestProvider $request
	 * @param array $config argument
	 * @param int $position
	 * @return string
	 * @throws \ItePHP\Core\Exception\RequiredArgumentException
	 * @since 0.1.0
	 */
	private function validatePost(RequestProvider $request , $config , $position){
		$postData=$request->getData();
		$argumentName=$config['name'];
		if(!isset($postData[$argumentName])){
			if(isset($config['default']))
				return $config['default'];
			else
				throw new RequiredArgumentException($position,$argumentName);

		}

		return $postData[$argumentName];
	}

	/**
	 * Validate GET.
	 *
	 * @param \ItePHP\Core\Core\RequestProvider $request
	 * @param array $config argument
	 * @param int $position
	 * @return string
	 * @throws \ItePHP\Core\Exception\RequiredArgumentException
	 * @since 0.22.0
	 */
	private function validateGet(RequestProvider $request , $config , $position){
		$getData=$request->getQuery();
		$argumentName=$config['name'];
		if(!isset($getData[$argumentName])){
			if(isset($config['default']))
				return $config['default'];
			else
				throw new RequiredArgumentException($position,$argumentName);

		}

		return $getData[$argumentName];
	}


}