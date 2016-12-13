<?php

namespace Test\Core;

use ItePHP\Core\HTTPErrorHandler;
use ItePHP\Core\Environment;
use ItePHP\Provider\Session;
use ItePHP\Core\HTTPRequest;
use ItePHP\Core\EventManager;
use ItePHP\Structure\ErrorStructure;
use ItePHP\Structure\PresenterStructure;
use ItePHP\Presenter\HTML;
use ItePHP\Presenter\JSON;
use Onus\ClassLoader;
use Pactum\ConfigBuilder;
use Pactum\Reader\XMLReader;

class HTTPErrorHandlerTest extends \PHPUnit_Framework_TestCase{

	private function createConfig(){
		$configBuilder=new ConfigBuilder();

		$structure=new ErrorStructure();
		$structure->doConfig($configBuilder);

		$structure=new PresenterStructure();
		$structure->doConfig($configBuilder);

		$xmlFileReader=new XMLReader(__DIR__.'/../../Asset/Core/HTTPErrorHandler/config.xml');
		$configBuilder->addReader($xmlFileReader);

		return $configBuilder->getClass();
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
		$environment=new Environment(true,true,'test',__DIR__);

		$_SERVER=[];
		$_SERVER['REMOTE_ADDR']='127.0.0.1';

		$session=new Session($environment);
		$request=new HTTPRequest($url,$session);

		$dependencyInjection=new ClassLoader();
		$dependencyInjection->addInstance('environment',$environment);
		$dependencyInjection->addInstance('config',$this->createConfig());
		$dependencyInjection->addInstance('eventManager',new EventManager());
		$dependencyInjection->addInstance('presenter.html',new HTML($environment));
		$dependencyInjection->addInstance('presenter.json',new JSON($environment));

		return new HTTPErrorHandler($dependencyInjection,$request);
	}
}