<?php

namespace Test;

require_once(__DIR__.'/../../autoload.php');

use ItePHP\Core\Container;
use ItePHP\Service\Validator;
use ItePHP\Core\ExecuteActionEvent;
use ItePHP\Provider\Request;
use ItePHP\Action\ArgumentEvent;
use ItePHP\DependencyInjection\DependencyInjection;
use ItePHP\Provider\Session;
use ItePHP\Core\Enviorment;
use Asset\RequestTest;
use ItePHP\Structure\ActionStructure;
use ItePHP\Structure\ActionArgumentStructure;
use ItePHP\Config\ConfigBuilder;
use ItePHP\Config\XmlFileReader;
use ItePHP\Core\Config;

class ArgumentEventTest extends \PHPUnit_Framework_TestCase{
	
	public function testOnExecuteAction(){

		$container=new Container(new DependencyInjection(),[]);
		$validator=new Validator();

		$enviorment=new Enviorment(true,true,'test',__DIR__);

		$request=new RequestTest('/test/1','POST');
		$request->setQuery(['var'=>'3']);
		$request->setData(['data2'=>'2']);

		$config=$this->getConfig();
		$request->setConfig($config->getNodes('action')[0]);

		$event=new ArgumentEvent($container,$validator);
		$executeActionEvent=new ExecuteActionEvent($request);
		$event->onExecuteAction($executeActionEvent);
		$arguments=$request->getArguments();
		$this->assertEquals('3',$arguments['var']);
		$this->assertEquals('2',$arguments['data2']);
		$this->assertEquals('1',$arguments['id']);

	}

	public function testOnExecuteDefaultAction(){

		$container=new Container(new DependencyInjection(),[]);
		$validator=new Validator();

		$enviorment=new Enviorment(true,true,'test',__DIR__);

		$request=new RequestTest('/test','POST');

		$config=$this->getConfig();
		$request->setConfig($config->getNodes('action')[1]);

		$event=new ArgumentEvent($container,$validator);
		$executeActionEvent=new ExecuteActionEvent($request);
		$event->onExecuteAction($executeActionEvent);
		$arguments=$request->getArguments();
		$this->assertEquals('3',$arguments['var']);
		$this->assertEquals('2',$arguments['data2']);
		$this->assertEquals('1',$arguments['id']);

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