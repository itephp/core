<?php

namespace Test;

require_once(__DIR__.'/../autoload.php');

use ItePHP\Core\Enviorment;
use ItePHP\Contener\GlobalConfig;

class GlobalConfigTest extends \PHPUnit_Framework_TestCase{
	
	public function testGetResources(){
		$enviorment=new Enviorment(true,true,'test');

		$config=new GlobalConfig(__DIR__.'/../Asset/Config',$enviorment);

		$data=$config->getResources();

		$this->assertCount(2,$data);
		$record=$data[0];
		$this->assertEquals('\/css\/(.*)\.[0-9]+\.css',$record['pattern']);
		$this->assertEquals('31536000',$record['expire']);
		$this->assertEquals('/css/{1}.css',$record['path']);

		$record=$data[1];
		$this->assertEquals('\/fonts\/(.*)',$record['pattern']);
		$this->assertEquals('0',$record['expire']);
		$this->assertEquals('/fonts/{1}',$record['path']);

	}

	public function testGetMethods(){
		$enviorment=new Enviorment(true,true,'test');

		$config=new GlobalConfig(__DIR__.'/../Asset/Config',$enviorment);

		$data=$config->getMethods();
		$this->assertCount(2,$data);
		$record=$data['TestController:index'];
		$this->assertEquals('\/',$record['route']['pattern']);
		$this->assertEquals('/login',$record['extra'][0]['authenticate']['redirect']);
		$this->assertEquals('ItePHP\Twig\Presenter',$record['presenter']['class']);

		$record=$data['Test2Controller:index2'];
		$this->assertEquals('\/test2(|\.html)',$record['route']['pattern']);
		$this->assertEquals('ItePHP\Twig\Presenter',$record['presenter']['class']);
		$this->assertEquals('post',$record['extra'][0]['argument']['storage']);
		$this->assertEquals('text',$record['extra'][0]['argument']['name']);
		$this->assertEquals('Validator\Text',$record['extra'][0]['argument']['validator']);

	}

	public function testGetCommands(){
		$enviorment=new Enviorment(true,true,'test');

		$config=new GlobalConfig(__DIR__.'/../Asset/Config',$enviorment);

		$data=$config->getCommands();

		$this->assertCount(3,$data);
		$record=$data['migrate:update'];
		$this->assertEquals('ItePHP\Command\Migrate',$record['class']);
		$this->assertEquals('update',$record['method']);

		$record=$data['migrate:downgrade'];
		$this->assertEquals('ItePHP\Command\Migrate',$record['class']);
		$this->assertEquals('downgrade',$record['method']);

		$record=$data['migrate:create'];
		$this->assertEquals('ItePHP\Command\Migrate',$record['class']);
		$this->assertEquals('create',$record['method']);

	}

	public function testGetErrors(){
		$enviorment=new Enviorment(true,true,'test');

		$config=new GlobalConfig(__DIR__.'/../Asset/Config',$enviorment);

		$data=$config->getErrors();
		$this->assertCount(2,$data);
		$record=$data['.*\.json'];
		$this->assertEquals('Presenter\JSA',$record);

		$record=$data['.*'];
		$this->assertEquals('ItePHP\Twig\Presenter',$record);

	}

	public function testGetServices(){
		$enviorment=new Enviorment(true,true,'test');

		$config=new GlobalConfig(__DIR__.'/../Asset/Config',$enviorment);

		$data=$config->getServices();
		$this->assertCount(2,$data);
		$record=$data['validator'];
		$this->assertEquals('validator',$record['name']);
		$this->assertEquals('ItePHP\Service\Validator',$record['class']);

		$methods=$record['methods'];
		$this->assertCount(2,$methods);

		$method=$methods[0];
		$this->assertEquals('__constructor',$method['name']);
		$arguments=$method['arguments'];
		$this->assertCount(2,$arguments);
		$argument=$arguments[0];
		$this->assertEquals('primitive',$argument['type']);
		$this->assertEquals('data1',$argument['value']);
		$argument=$arguments[1];
		$this->assertEquals('reference',$argument['type']);
		$this->assertEquals('eventManager',$argument['value']);

		$method=$methods[1];
		$this->assertEquals('setData',$method['name']);
		$arguments=$method['arguments'];
		$this->assertCount(1,$arguments);
		$argument=$arguments[0];
		$this->assertEquals('static',$argument['type']);
		$this->assertEquals('Asset\StaticClass::DATA',$argument['value']);


		$record=$data['mapper'];
		$this->assertEquals('mapper',$record['name']);
		$this->assertEquals('ItePHP\Service\Mapper',$record['class']);

		$methods=$record['methods'];
		$this->assertCount(0,$methods);

	}
	

	public function testGetEvents(){
		$enviorment=new Enviorment(true,true,'test');

		$config=new GlobalConfig(__DIR__.'/../Asset/Config',$enviorment);

		$data=$config->getEvents();
		$this->assertCount(2,$data);
		$record=$data['executeAction'];
		$this->assertCount(1,$record);
		$event=$record[0];

		$this->assertEquals('ItePHP\Event\Authenticate',$event['class']);
		$this->assertEquals('method1',$event['method']);
		$this->assertCount(0,$event['config']);

		$record=$data['executedAction'];
		$this->assertCount(1,$record);
		$event=$record[0];

		$this->assertEquals('ItePHP\Event\Argument',$event['class']);
		$this->assertEquals('method2',$event['method']);
		$this->assertCount(0,$event['config']);


	}

	public function testGetSnippets(){
		$enviorment=new Enviorment(true,true,'test');

		$config=new GlobalConfig(__DIR__.'/../Asset/Config',$enviorment);

		$data=$config->getSnippets();
		$this->assertCount(1,$data);
		$record=$data['cast'];

		$this->assertEquals('ItePHP\Snippet\Mapper',$record);

	}

}