<?php

namespace Test;

require_once(__DIR__.'/../../autoload.php');

use ItePHP\Action\PermissionDeniedException;

class PermissionDeniedExceptionTest extends \PHPUnit_Framework_TestCase{

	private $exception;

	public function setUp(){
		$this->exception=new PermissionDeniedException();
	}
	
	public function testGetCode(){
		$this->assertEquals(311,$this->exception->getCode());
	}

	public function testGetMessage(){
		$this->assertEquals('Permission denied',$this->exception->getMessage());
	}

	public function testGetSafeMessage(){
		$this->assertEquals('Permission denied',$this->exception->getSafeMessage());
	}

}