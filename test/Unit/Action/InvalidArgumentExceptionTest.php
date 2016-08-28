<?php

namespace Test\Action;

use ItePHP\Action\InvalidArgumentException;

class InvalidArgumentExceptionTest extends \PHPUnit_Framework_TestCase{

	private $exception;

	public function setUp(){
		$this->exception=new InvalidArgumentException(2,'field','error');

	}
	
	public function testGetCode(){
		$this->assertEquals(202,$this->exception->getCode());
	}

	public function testGetMessage(){
		$this->assertEquals('Invalid argument "field": error',$this->exception->getMessage());
	}

	public function testGetSafeMessage(){
		$this->assertEquals('Invalid argument "field": error',$this->exception->getSafeMessage());
	}

}