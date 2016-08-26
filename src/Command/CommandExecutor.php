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

namespace ItePHP\Command;

/**
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class CommandExecutor{

	/**
	 *
	 * @var CommandInterface
	 */
	private $commandObject;

	/**
	 *
	 * @var mixed[]
	 */
	private $arguments=[];

	/**
	 *
	 * @var OutputStream
	 */
	private $outputStream;

	/**
	 *
	 * @param CommandInterface $commandObject
	 */
	public function __construct(CommandInterface $commandObject){
		$this->commandObject=$commandObject;
		$this->setOutputStream(new OutputStreamConsole());
	}

	/**
	 *
	 * @param OutputStream $outputStream
	 */
	public function setOutputStream(OutputStream $outputStream){
		$this->outputStream=$outputStream;
	}

	/**
	 *
	 * @param mixed $argument
	 */
	public function addArgument($argument){
		$this->arguments[]=$argument;
	}

	/**
	 *
	 * @param mixed[] $arguments
	 */
	public function setArguments($arguments){
		$this->arguments=$arguments;
	}

	/**
	 *
	 * @return mixed[]
	 */
	public function getArguments(){
		return $this->arguments;
	}

	/**
	 *
	 */
	public final function run(){
		$inputStream=$this->getInputStream();

		call_user_func_array([$this->commandObject,'execute'], [$inputStream,$this->outputStream]);
	}

	/**
	 *
	 * @return InputStream
	 */
	private function getInputStream(){
		$config=new CommandConfig();
		$this->commandObject->doConfig($config);

		$commandArguments=[];
		foreach($config->getArguments() as $argument){
			$this->parseArgument($argument,$commandArguments);
		}

		return new InputStream($commandArguments);
	}

	/**
	 *
	 * @param CommandArgument $argument
	 * @param array $commandArguments
	 * @throws CommandArgumentRequiredException
	 */
	private function parseArgument(CommandArgument $argument,&$commandArguments){
		$name=$argument->getName();
		$index=array_search($name,$this->arguments,true);
		$value=null;
		if($index!==false){
			$value=$this->getValue($argument,$index);
		}
		else{
			if($argument->isRequired()){
				throw new CommandArgumentRequiredException($argument->getName());
			}
			$value=$argument->getDefault();

		}

		$commandArguments[$name]=$value;

	}

    /**
     *
     * @param CommandArgument $argument
     * @param int $index
     * @return mixed
     * @throws CommandInvalidArgumentLengthException
     */
	private function getValue(CommandArgument $argument,$index){
		$length=$argument->getLength();

		if($length==0){
			return true;
		}
		$value=[];
		for($i=$index+1,$j=$length; $i<count($this->arguments) && $j>0; $i++,$j--){
			$value[]=$this->arguments[$i];
		}

		if($j!=0){
			throw new CommandInvalidArgumentLengthException($argument->getName(),$argument->getLength(),$argument->getLength()-$j);
		}

		if(count($value)==1){
			$value=$value[0];
		}

		return $value;
	}
}