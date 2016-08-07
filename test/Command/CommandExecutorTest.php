<?php

namespace Test;

require_once(__DIR__.'/../autoload.php');

use ItePHP\Command\CommandExecutor;
use Asset\Command\TestCommand;

class CommandExecutorTest extends \PHPUnit_Framework_TestCase{
	
	public function testRun(){
		$args=[];
		$testCommand=new TestCommand();
		$commandExecutor=new CommandExecutor($testCommand);
		$commandExecutor->addArgument('string','value');
		$commandExecutor->run();
		// $this->assertEquals('route2',$dispatcher->getName());

	}
}