<?php

namespace Test\Command;

use ItePHP\Command\CommandExecutor;
use ItePHP\Command\CommandArgumentRequiredException;
use ItePHP\Command\CommandInvalidArgumentLengthException;
use Test\Asset\Command\OutputStreamTest;
use Test\Asset\Command\TestCommand;

class CommandExecutorTest extends \PHPUnit_Framework_TestCase{

    /**
     * @var CommandExecutor
     */
	private $commandExecutor;

	protected function  setUp(){
		$testCommand=new TestCommand();
		$this->commandExecutor=new CommandExecutor($testCommand);
	}

	public function testRun(){
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
		$outputStream=new OutputStreamTest();
		$this->commandExecutor->setOutputStream($outputStream);

		$this->commandExecutor->addArgument('string');
		$this->commandExecutor->addArgument('value');
		$this->commandExecutor->run();
		$this->assertEquals('0value',$outputStream->getBuffer());

	}

	public function testRunRequired(){
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

	public function testSetArguments(){
		$args=['1','z'];
		$this->commandExecutor->setArguments($args);
		$this->assertEquals($args,$this->commandExecutor->getArguments());
	}

}