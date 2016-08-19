<?php

namespace Test;

require_once(__DIR__.'/../../autoload.php');

use ItePHP\Action\RequiredArgumentException;

class RequiredArgumentExceptionTest extends \PHPUnit_Framework_TestCase{

	private $exception;

	public function setUp(){
		$this->exception=new RequiredArgumentException(2,'field');
	}
	
	public function testGetCode(){
		$this->assertEquals(102,$this->exception->getCode());
	}

	public function testGetMessage(){
		$this->assertEquals('Required argument "field".',$this->exception->getMessage());
	}

	public function testGetSafeMessage(){
		$this->assertEquals('Required argument "field".',$this->exception->getSafeMessage());
	}

}