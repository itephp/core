<?php

namespace Test\Core;

use ItePHP\Core\Environment;
use ItePHP\Core\EventManager;
<<<<<<< Updated upstream
use ItePHP\Structure\ErrorStructure;
use ItePHP\Structure\ResponseStructure;
use ItePHP\Presenter\HTMLResponse;
use ItePHP\Presenter\JSONResponse;
=======
use ItePHP\Core\HTTPErrorHandler;
use ItePHP\Core\HTTPRequest;
use ItePHP\Presenter\HTML;
use ItePHP\Presenter\JSON;
use ItePHP\Provider\Session;
use ItePHP\Structure\ErrorStructure;
use ItePHP\Structure\PresenterStructure;
>>>>>>> Stashed changes
use Onus\ClassLoader;
use Pactum\ConfigBuilder;
use Pactum\Reader\XMLReader;

class HTTPErrorHandlerTest extends \PHPUnit_Framework_TestCase{

	private function createConfig(){
		$configBuilder=new ConfigBuilder();

		$structure=new ErrorStructure();
		$structure->doConfig($configBuilder);

		$structure=new ResponseStructure();
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
		$dependencyInjection->addInstance('presenter.html',new HTMLResponse($environment));
		$dependencyInjection->addInstance('presenter.json',new JSONResponse($environment));

		return new HTTPErrorHandler($dependencyInjection,$request);
	}
}