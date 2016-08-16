<?php

namespace Test;

require_once(__DIR__.'/../autoload.php');

define('ITE_ROOT',__DIR__.'/../Asset/Root');

use ItePHP\Core\Enviorment;
use ItePHP\Root;

class RootTest extends \PHPUnit_Framework_TestCase{
	
	public function testExecuteRequestTest(){
		$_SERVER=[];
		$_SERVER['REMOTE_ADDR']='127.0.0.1';
		$envioroment=new Enviorment(true,true,'test');

		$root=new Root($envioroment);

		ob_start();
		$root->executeRequest('/test');
		$result=ob_get_clean();
		ob_flush();

		$this->assertEquals('hello',$result);

	}

	public function testExecuteRequestError(){
		$_SERVER=[];
		$_SERVER['REMOTE_ADDR']='127.0.0.1';
		$envioroment=new Enviorment(true,true,'test');

		$root=new Root($envioroment);

		ob_start();
		$root->executeRequest('/error');
		$result=ob_get_clean();
		ob_flush();

		$this->assertRegExp('/error/',$result);

	}

	public function testExecuteRequestNotFound(){
		$_SERVER=[];
		$_SERVER['REMOTE_ADDR']='127.0.0.1';
		$envioroment=new Enviorment(true,true,'test');

		$root=new Root($envioroment);

		ob_start();
		$root->executeRequest('/notfound');
		$result=ob_get_clean();
		ob_flush();

		$this->assertRegExp('/Route not found for url/',$result);

	}

	public function testExecuteRequestArgument(){
		$_SERVER=[];
		$_SERVER['REMOTE_ADDR']='127.0.0.1';
		$_GET=[];
		$_GET['var']='testValue';
		$envioroment=new Enviorment(true,true,'test');

		$root=new Root($envioroment);

		ob_start();
		$root->executeRequest('/argument');
		$result=ob_get_clean();
		ob_flush();

		$this->assertEquals('testValue',$result);

	}

	public function testExecuteCommand(){
		$envioroment=new Enviorment(true,true,'test');

		$root=new Root($envioroment);

		ob_start();
		$sigint=$root->executeCommand(['hello','--name','Michal']);
		$result=ob_get_clean();
		ob_flush();

		$this->assertEquals(0,$sigint);

		$this->assertEquals('hello Michal',$result);

	}

	public function testExecuteCommandCommandNotFoundException(){
		$envioroment=new Enviorment(true,true,'test');

		$root=new Root($envioroment);

		ob_start();
		$sigint=$root->executeCommand(['not:found','--name','Michal']);
		$result=ob_get_clean();
		ob_flush();

		$this->assertEquals(1,$sigint);

		$this->assertEquals('Command not:found not found.',$result);

	}

}