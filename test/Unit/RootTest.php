<?php

namespace Test;

use ItePHP\Core\Environment;
use ItePHP\Root;

class RootTest extends \PHPUnit_Framework_TestCase{

	private $environment;
	
	public function setUp(){
		$this->environment=new Environment(true,true,'test',__DIR__.'/../Asset/Root');
	}

	public function testExecuteRequestTest(){
		$_SERVER=[];
		$_SERVER['REMOTE_ADDR']='127.0.0.1';

		$root=new Root($this->environment);

		ob_start();
		$root->executeRequest('/test');
		$result=ob_get_clean();
		ob_flush();

		$this->assertEquals('hello',$result);

	}

	public function testExecuteRequestError(){
		$_SERVER=[];
		$_SERVER['REMOTE_ADDR']='127.0.0.1';

		$root=new Root($this->environment);

		ob_start();
		$root->executeRequest('/error');
		$result=ob_get_clean();
		ob_flush();

		$this->assertRegExp('/error/',$result);

	}

	public function testExecuteRequestNotFound(){
		$_SERVER=[];
		$_SERVER['REMOTE_ADDR']='127.0.0.1';

		$root=new Root($this->environment);

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

		$root=new Root($this->environment);

		ob_start();
		$root->executeRequest('/argument');
		$result=ob_get_clean();
		ob_flush();

		$this->assertEquals('testValue',$result);

	}

	public function testExecuteCommand(){

		$root=new Root($this->environment);

		ob_start();
		$sigint=$root->executeCommand(['hello','--name','Michal']);
		$result=ob_get_clean();
		ob_flush();

		$this->assertEquals(0,$sigint);

		$this->assertEquals('hello Michal',$result);

	}

	public function testExecuteCommandCommandNotFoundException(){

		$root=new Root($this->environment);

		ob_start();
		$sigint=$root->executeCommand(['not:found','--name','Michal']);
		$result=ob_get_clean();
		ob_flush();

		$this->assertEquals(1,$sigint);

		$this->assertEquals('Command not:found not found.',$result);

	}

}