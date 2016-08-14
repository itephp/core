<?php

namespace Test;

require_once(__DIR__.'/../autoload.php');

define('ITE_ROOT',__DIR__.'/Asset/Migrate');

use ItePHP\Command\CommandExecutor;
use Asset\Command\TestCommand;
use Asset\Command\OutputStreamTest;
use ItePHP\Command\CommandArgumentRequiredException;
use ItePHP\Command\CommandInvalidArgumentLengthException;

class MigrateCommandTest extends \PHPUnit_Framework_TestCase{

	public function testCreate(){
		$envioroment=new Enviorment(true,true,'test');

		$root=new Root($envioroment);

		ob_start();
		$sigint=$root->executeCommand(['migrate','-o','create']);
		$result=ob_get_clean();
		ob_flush();

		$this->assertEquals(1,$sigint);

		$this->assertEquals('Command not:found not found.',$result);

	}

}