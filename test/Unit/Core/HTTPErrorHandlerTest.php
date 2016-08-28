<?php

namespace Test;

require_once(__DIR__.'/../../autoload.php');

use ItePHP\Core\HTTPErrorHandler;
use ItePHP\Core\Environment;
use ItePHP\Config\ConfigBuilder;
use ItePHP\Config\XmlFileReader;
use ItePHP\Provider\Session;
use ItePHP\Core\HTTPRequest;
use ItePHP\Core\EventManager;
use ItePHP\Core\Config;
use ItePHP\DependencyInjection\DependencyInjection;
use ItePHP\Structure\ErrorStructure;
use ItePHP\Structure\PresenterStructure;
use ItePHP\Presenter\HTML;
use ItePHP\Presenter\JSON;

class HTTPErrorHandlerTest extends \PHPUnit_Framework_TestCase{

	private function createConfigContainer(){
		$configBuilder=new ConfigBuilder();

		$structure=new ErrorStructure();
		$structure->doConfig($configBuilder);

		$structure=new PresenterStructure();
		$structure->doConfig($configBuilder);

		$xmlFileReader=new XmlFileReader(__DIR__.'/../../Asset/Core/HTTPErrorHandler/config.xml');
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
		$environment=new Environment(true,true,'test',__DIR__);

		$_SERVER=[];
		$_SERVER['REMOTE_ADDR']='127.0.0.1';

		$session=new Session($environment);
		$request=new HTTPRequest($url,$session);

		$dependencyInjection=new DependencyInjection();
		$dependencyInjection->addInstance('environment',$environment);
		$dependencyInjection->addInstance('config',$this->createConfigContainer());
		$dependencyInjection->addInstance('eventManager',new EventManager());
		$dependencyInjection->addInstance('presenter.html',new HTML($environment));
		$dependencyInjection->addInstance('presenter.json',new JSON($environment));

		return new HTTPErrorHandler($dependencyInjection,$request);
	}
}