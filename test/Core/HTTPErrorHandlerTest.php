<?php

namespace Test;

require_once(__DIR__.'/../autoload.php');

use ItePHP\Core\HTTPErrorHandler;
use ItePHP\Core\Enviorment;
use ItePHP\Config\ConfigBuilder;
use ItePHP\Config\ConfigBuilderNode;
use ItePHP\Config\XmlFileReader;
use ItePHP\Provider\Session;
use ItePHP\Provider\Request;
use ItePHP\Core\EventManager;
use ItePHP\Core\Config;

class HTTPErrorHandlerTest extends \PHPUnit_Framework_TestCase{
	
	private $handler;

	private function createConfigContainer(){
		$configBuilder=new ConfigBuilder();

		$errorNode=new ConfigBuilderNode('error');
		$errorNode->addAttribute('pattern');
		$errorNode->addAttribute('presenter');

		$configBuilder->addNode($errorNode);
		$xmlFileReader=new XmlFileReader(__DIR__.'/../Asset/Core/HTTPErrorHandler/error.xml');
		$configBuilder->addReader($xmlFileReader);

		return new Config($configBuilder->parse());
	}

	public function testExecuteHTML(){

		$handler=$this->createHandler('test.html');
		ob_start();
		$handler->execute(new \Exception('test error'));
		$result=ob_get_clean();
		ob_flush();

		$this->assertRegExp('/test error/',$result);
	}

	public function testExecuteJSON(){

		$handler=$this->createHandler('test.json');
		ob_start();
		$handler->execute(new \Exception('test error'));
		$result=ob_get_clean();
		ob_flush();

		$this->assertEquals('{}',$result);
	}

	private function createHandler($url){
		$enviorment=new Enviorment(true,true,'test');

		$_SERVER=[];
		$_SERVER['REMOTE_ADDR']='127.0.0.1';

		$session=new Session($enviorment);
		$request=new Request($url,$session);

		return new HTTPErrorHandler($enviorment,$this->createConfigContainer(),new EventManager(),$request);
	}
}