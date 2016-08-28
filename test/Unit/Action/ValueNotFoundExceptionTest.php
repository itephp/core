<?php

namespace Test\Action;

use ItePHP\Action\ValueNotFoundException;

class ValueNotFoundExceptionTest extends \PHPUnit_Framework_TestCase{

	private $exception;

	public function setUp(){
		$this->exception=new ValueNotFoundException('field');
	}
	
	public function testGetCode(){
		$this->assertEquals(6,$this->exception->getCode());
	}

	public function testGetMessage(){
		$this->assertEquals('Value "field" not found.',$this->exception->getMessage());
	}

	public function testGetSafeMessage(){
		$this->assertEquals('Internal server error.',$this->exception->getSafeMessage());
	}

}