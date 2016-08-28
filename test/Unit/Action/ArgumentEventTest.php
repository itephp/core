<?php

namespace Test\Action;

use ItePHP\Core\Container;
use ItePHP\Service\Validator;
use ItePHP\Core\ExecuteActionEvent;
use ItePHP\Core\HTTPRequest;
use ItePHP\Action\ArgumentEvent;
use ItePHP\DependencyInjection\DependencyInjection;
use ItePHP\Provider\Session;
use ItePHP\Core\Environment;
use Asset\RequestTest;
use ItePHP\Structure\ActionStructure;
use ItePHP\Structure\ActionArgumentStructure;
use ItePHP\Config\ConfigBuilder;
use ItePHP\Config\XmlFileReader;
use ItePHP\Core\Config;
use ItePHP\Action\InvalidArgumentException;

class ArgumentEventTest extends \PHPUnit_Framework_TestCase{
	
	public function testOnExecuteAction(){

		$container=new Container(new DependencyInjection(),[]);

		$enviorment=new Environment(true,true,'test',__DIR__);

		$request=new RequestTest('/test/1','POST');
		$request->setQuery(['var'=>'3']);
		$request->setData(['data2'=>'2']);

		$config=$this->getConfig();
		$request->setConfig($config->getNodes('action')[0]);

		$event=new ArgumentEvent($container);
		$executeActionEvent=new ExecuteActionEvent($request);
		$event->onExecuteAction($executeActionEvent);
		$arguments=$request->getArguments();
		$this->assertEquals('3',$arguments['var']);
		$this->assertEquals('2',$arguments['data2']);
		$this->assertEquals('1',$arguments['id']);

	}

	public function testOnExecuteActionDefault(){

		$container=new Container(new DependencyInjection(),[]);

		$enviorment=new Environment(true,true,'test',__DIR__);

		$request=new RequestTest('/test','POST');

		$config=$this->getConfig();
		$request->setConfig($config->getNodes('action')[1]);

		$event=new ArgumentEvent($container);
		$executeActionEvent=new ExecuteActionEvent($request);
		$event->onExecuteAction($executeActionEvent);
		$arguments=$request->getArguments();
		$this->assertEquals('3',$arguments['var']);
		$this->assertEquals('2',$arguments['data2']);
		$this->assertEquals('1',$arguments['id']);

	}

	public function testOnExecuteActionValidator(){

		$container=new Container(new DependencyInjection(),[]);

		$enviorment=new Environment(true,true,'test',__DIR__);

		$request=new RequestTest('/test/999999999','POST');
		$request->setData(['data2'=>'123321123']);

		$config=$this->getConfig();
		$request->setConfig($config->getNodes('action')[2]);

		$event=new ArgumentEvent($container);
		$executeActionEvent=new ExecuteActionEvent($request);
		$responseMessage='';
		$event->onExecuteAction($executeActionEvent);
		$arguments=$request->getArguments();
		$this->assertEquals('123321123',$arguments['data2']);
		$this->assertEquals('999999999',$arguments['id']);

	}

	public function testOnExecuteActionValidatorInvalidArgumentException(){

		$container=new Container(new DependencyInjection(),[]);

		$enviorment=new Environment(true,true,'test',__DIR__);

		$request=new RequestTest('/test/1','POST');
		$request->setData(['data2'=>'123321123']);

		$config=$this->getConfig();
		$request->setConfig($config->getNodes('action')[2]);

		$event=new ArgumentEvent($container);
		$executeActionEvent=new ExecuteActionEvent($request);
		$responseMessage='';
		try{
			$event->onExecuteAction($executeActionEvent);

		}
		catch(InvalidArgumentException $e){
			$responseMessage=$e->getMessage();
		}

		$this->assertEquals('Invalid argument "id": Invalid telephone format.',$responseMessage);
	}

	public function testOnExecuteActionMapper(){

		$container=new Container(new DependencyInjection(),[]);

		$enviorment=new Environment(true,true,'test',__DIR__);

		$request=new RequestTest('/test/1','POST');
		$request->setQuery(['var'=>'3']);
		$request->setData(['data2'=>'2']);

		$config=$this->getConfig();
		$request->setConfig($config->getNodes('action')[3]);

		$event=new ArgumentEvent($container);
		$executeActionEvent=new ExecuteActionEvent($request);

		$event->onExecuteAction($executeActionEvent);

		$arguments=$request->getArguments();
		$this->assertEquals(4,$arguments['var']);
		$this->assertEquals(3,$arguments['data2']);
		$this->assertEquals(2,$arguments['id']);

	}

	public function testOnExecuteActionMapperException(){

		$container=new Container(new DependencyInjection(),[]);

		$enviorment=new Environment(true,true,'test',__DIR__);

		$request=new RequestTest('/test/s','POST');
		$request->setQuery(['var'=>'3']);
		$request->setData(['data2'=>'2']);

		$config=$this->getConfig();
		$request->setConfig($config->getNodes('action')[3]);

		$event=new ArgumentEvent($container);
		$executeActionEvent=new ExecuteActionEvent($request);
		$responseMessage='';
		try{
			$event->onExecuteAction($executeActionEvent);
		}
		catch(\Exception $e){
			$responseMessage=$e->getMessage();
		}

		$this->assertEquals('Invalid value s.',$responseMessage);
	}

	private function getConfig(){
		$configBuilder=new ConfigBuilder();
		$structure=new ActionStructure();
		$structure->doConfig($configBuilder);
		$structure=new ActionArgumentStructure();
		$structure->doConfig($configBuilder);
		$configBuilder->addReader(new XmlFileReader(__DIR__.'/../../Asset/Action/ArgumentEvent/config.xml'));
		return new Config($configBuilder->parse());
	}

}