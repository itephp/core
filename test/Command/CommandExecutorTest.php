<?php

namespace Test;

require_once(__DIR__.'/../autoload.php');

use ItePHP\Command\CommandExecutor;
use Asset\Command\TestCommand;
use Asset\Command\OutputStreamTest;
use ItePHP\Command\CommandArgumentRequiredException;
use ItePHP\Command\CommandInvalidArgumentLengthException;

class CommandExecutorTest extends \PHPUnit_Framework_TestCase{

	private $commandExecutor;

	protected function  setUp(){
		$testCommand=new TestCommand();
		$this->commandExecutor=new CommandExecutor($testCommand);
	}

	public function testRun(){
		$args=[];
		$outputStream=new OutputStreamTest();
		$this->commandExecutor->setOutputStream($outputStream);

		$this->commandExecutor->addArgument('string');
		$this->commandExecutor->addArgument('value');
		$this->commandExecutor->addArgument('--boolean');
		$this->commandExecutor->addArgument('array');
		$this->commandExecutor->addArgument('a1');
		$this->commandExecutor->addArgument('a2');
		$this->commandExecutor->run();
		$this->assertEquals('1valuea1-a2',$outputStream->getBuffer());

	}

	public function testRunDefault(){
		$args=[];
		$outputStream=new OutputStreamTest();
		$this->commandExecutor->setOutputStream($outputStream);

		$this->commandExecutor->addArgument('string');
		$this->commandExecutor->addArgument('value');
		$this->commandExecutor->run();
		$this->assertEquals('0value',$outputStream->getBuffer());

	}

	public function testRunRequired(){
		$args=[];
		$outputStream=new OutputStreamTest();
		$this->commandExecutor->setOutputStream($outputStream);
		try{
			$this->commandExecutor->run();
			$this->assertTrue(false);

		}
		catch(\Exception $e){
			$this->assertInstanceOf(CommandArgumentRequiredException::class,$e);
		}

	}

	public function testRunLength(){
		$args=[];
		$outputStream=new OutputStreamTest();
		$this->commandExecutor->setOutputStream($outputStream);
		try{
			$this->commandExecutor->addArgument('string');
			$this->commandExecutor->addArgument('value');
			$this->commandExecutor->addArgument('array');
			$this->commandExecutor->addArgument('a1');
			$this->commandExecutor->run();
			$this->assertTrue(false);

		}
		catch(\Exception $e){
			$this->assertInstanceOf(CommandInvalidArgumentLengthException::class,$e);
		}

	}

}