<?php

namespace Test\Action;

use ItePHP\Action\RequiredArgumentException;

class RequiredArgumentExceptionTest extends \PHPUnit_Framework_TestCase{

    /**
     * @var RequiredArgumentException
     */
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