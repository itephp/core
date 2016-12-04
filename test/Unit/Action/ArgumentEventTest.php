<?php

namespace Test\Action;

use ItePHP\Core\Container;
use ItePHP\Core\ExecuteActionEvent;
use ItePHP\Action\ArgumentEvent;
use ItePHP\Structure\ActionStructure;
use ItePHP\Structure\ActionArgumentStructure;
use ItePHP\Action\InvalidArgumentException;
use Onus\ClassLoader;
use Pactum\ConfigBuilder;
use Pactum\Reader\XMLReader;
use Test\Asset\RequestTest;

class ArgumentEventTest extends \PHPUnit_Framework_TestCase{
	
	public function testOnExecuteAction(){
        $classLoader=new ClassLoader();
		$container=new Container($classLoader,[]);

		$request=new RequestTest('/test/1','POST');
		$request->setQuery(['var'=>'3']);
		$request->setData(['data2'=>'2']);

		$config=$this->getConfig();
		$request->setConfig($config->getArray('action')[0]);

		$event=new ArgumentEvent($container,$classLoader);
		$executeActionEvent=new ExecuteActionEvent($request);
		$event->onExecuteAction($executeActionEvent);
		$arguments=$request->getArguments();
		$this->assertEquals('3',$arguments['var']);
		$this->assertEquals('2',$arguments['data2']);
		$this->assertEquals('1',$arguments['id']);

	}

	public function testOnExecuteActionDefault(){
        $classLoader=new ClassLoader();
		$container=new Container($classLoader,[]);

		$request=new RequestTest('/test','POST');

		$config=$this->getConfig();
		$request->setConfig($config->getArray('action')[1]);

		$event=new ArgumentEvent($container,$classLoader);
		$executeActionEvent=new ExecuteActionEvent($request);
		$event->onExecuteAction($executeActionEvent);
		$arguments=$request->getArguments();
		$this->assertEquals('3',$arguments['var']);
		$this->assertEquals('2',$arguments['data2']);
		$this->assertEquals('1',$arguments['id']);

	}

	public function testOnExecuteActionValidator(){

	    $classLoader=new ClassLoader();
		$container=new Container($classLoader);

		$request=new RequestTest('/test/999999999','POST');
		$request->setData(['data2'=>'123321123']);

		$config=$this->getConfig();
		$request->setConfig($config->getArray('action')[2]);

		$event=new ArgumentEvent($container,$classLoader);
		$executeActionEvent=new ExecuteActionEvent($request);
		$event->onExecuteAction($executeActionEvent);
		$arguments=$request->getArguments();
		$this->assertEquals('123321123',$arguments['data2']);
		$this->assertEquals('999999999',$arguments['id']);

	}

	public function testOnExecuteActionValidatorInvalidArgumentException(){
        $classLoader=new ClassLoader();
		$container=new Container($classLoader);

		$request=new RequestTest('/test/1','POST');
		$request->setData(['data2'=>'123321123']);

		$config=$this->getConfig();
		$request->setConfig($config->getArray('action')[2]);

		$event=new ArgumentEvent($container,$classLoader);
		$executeActionEvent=new ExecuteActionEvent($request);
		$responseMessage='';
		try{
			$event->onExecuteAction($executeActionEvent);

		}
		catch(InvalidArgumentException $e){
			$responseMessage=$e->getMessage();
		}

		$this->assertEquals('Invalid argument "id": Value is not valid format phone number 000000000.',$responseMessage);
	}

	public function testOnExecuteActionMapper(){
        $classLoader=new ClassLoader();
		$container=new Container($classLoader);

		$request=new RequestTest('/test/1','POST');
		$request->setQuery(['var'=>'3']);
		$request->setData(['data2'=>'2']);

		$config=$this->getConfig();
		$request->setConfig($config->getArray('action')[3]);

		$event=new ArgumentEvent($container,$classLoader);
		$executeActionEvent=new ExecuteActionEvent($request);

		$event->onExecuteAction($executeActionEvent);

		$arguments=$request->getArguments();
		$this->assertEquals(4,$arguments['var']);
		$this->assertEquals(3,$arguments['data2']);
		$this->assertEquals(2,$arguments['id']);

	}

	public function testOnExecuteActionMapperException(){
        $classLoader=new ClassLoader();
		$container=new Container($classLoader,[]);

		$request=new RequestTest('/test/s','POST');
		$request->setQuery(['var'=>'3']);
		$request->setData(['data2'=>'2']);

		$config=$this->getConfig();
		$request->setConfig($config->getArray('action')[3]);

		$event=new ArgumentEvent($container,$classLoader);
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
		$configBuilder->addReader(new XMLReader(__DIR__.'/../../Asset/Action/ArgumentEvent/config.xml'));
		return $configBuilder->parse();
	}

}