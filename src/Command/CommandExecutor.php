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
 * @since 0.4.0
 */
class CommandExecutor{

	/**
	 *
	 * @var Command
	 */
	private $commandObject;

	/**
	 *
	 * @var array
	 */
	private $arguments=[];

	/**
	 *
	 * @param CommandInterface $commandObject
	 */
	public function __construct(CommandInterface $commandObject){
		$this->commandObject=$commandObject;
	}

	/**
	 *
	 * @param mixed $argument
	 */
	public function addArgument($argument){
		$this->arguments[]=$argument;
	}

	public final function run(){
		$inputStream=$this->getInputStream();
		$outputStream=new OutputStream();

		call_user_func_array([$this->commandObject,'execute'], [$inputStream,$outputStream]);
	}

	private function getInputStream(){
		$config=new CommandConfig();
		$this->commandObject->doConfig($config);

		$commandArguments=[];
		foreach($config->getArguments() as $argument){
			$this->parseArgument($argument,$commandArguments);
		}

		return new InputStream($commandArguments);
	}

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

	private function getValue(CommandArgument $argument,$index){
		$length=$argument->getLength();
		if($index==0){
			return true;
		}
		$value=[];
		for($i=$index+1,$j=$length; $i<count($this->argument) && $j>0; $i++,$j--){
			$value[]=$this->argument[$i];
		}

		if($j!=0){
			throw new CommandInvalidArgumentLengthException($argument->getName(),$argument->getLength(),$argument->getLength()-$j);
		}

		return $value;
	}
}

?>
