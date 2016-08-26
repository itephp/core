<?php

namespace Test;

require_once(__DIR__.'/../../autoload.php');

use ItePHP\Core\Container;
use ItePHP\Service\Validator;
use ItePHP\Core\ExecuteActionEvent;
use ItePHP\Core\HTTPRequest;
use ItePHP\Action\AuthenticateEvent;
use ItePHP\DependencyInjection\DependencyInjection;
use ItePHP\Provider\Session;
use ItePHP\Core\Environment;
use Asset\RequestTest;
use ItePHP\Structure\ActionStructure;
use ItePHP\Structure\ActionAuthenticateStructure;
use ItePHP\Config\ConfigBuilder;
use ItePHP\Config\XmlFileReader;
use ItePHP\Core\Config;
use ItePHP\Action\InvalidArgumentException;
use ItePHP\Action\PermissionDeniedException;

class AuthenticateEventTest extends \PHPUnit_Framework_TestCase{
	
	public function testOnExecuteActionAutheticate(){

		$enviorment=new Environment(true,true,'test',__DIR__);

		$request=new RequestTest('/test/1','GET');
		$request->getSession()->set('authenticate.user_id',1);
		$request->getSession()->set('authenticate.epoch',time()+10);

		$config=$this->getConfig();
		$request->setConfig($config->getNodes('action')[0]);

		$event=new AuthenticateEvent($config);
		$executeActionEvent=new ExecuteActionEvent($request);

		$event->onExecuteAction($executeActionEvent);
		$response=$executeActionEvent->getResponse();

		$this->assertNull($response);

	}

	public function testOnExecuteActionAutheticateRedirect(){

		$enviorment=new Environment(true,true,'test',__DIR__);

		$request=new RequestTest('/test/1','GET');
		$request->getSession()->set('authenticate.user_id',1);
		$request->getSession()->set('authenticate.epoch',time()+10);

		$config=$this->getConfig();
		$request->setConfig($config->getNodes('action')[1]);

		$event=new AuthenticateEvent($config);
		$executeActionEvent=new ExecuteActionEvent($request);
		$event->onExecuteAction($executeActionEvent);
		$response=$executeActionEvent->getResponse();

		$this->assertEquals(302,$response->getStatusCode());
		$this->assertEquals('/logout',$response->getHeader('location'));

	}

	public function testOnExecuteActionUnautheticate(){

		$enviorment=new Environment(true,true,'test',__DIR__);

		$request=new RequestTest('/test/1','GET');

		$config=$this->getConfig();
		$request->setConfig($config->getNodes('action')[1]);

		$event=new AuthenticateEvent($config);
		$executeActionEvent=new ExecuteActionEvent($request);
		$event->onExecuteAction($executeActionEvent);
		$response=$executeActionEvent->getResponse();

		$this->assertNull($response);

	}

	public function testOnExecuteActionUnautheticateRedirect(){

		$enviorment=new Environment(true,true,'test',__DIR__);

		$request=new RequestTest('/test/1','GET');

		$config=$this->getConfig();
		$request->setConfig($config->getNodes('action')[0]);

		$event=new AuthenticateEvent($config);
		$executeActionEvent=new ExecuteActionEvent($request);
		$event->onExecuteAction($executeActionEvent);
		$response=$executeActionEvent->getResponse();

		$this->assertEquals(302,$response->getStatusCode());
		$this->assertEquals('/login',$response->getHeader('location'));

	}

	public function testOnExecuteActionPermissionDeniedException(){

		$enviorment=new Environment(true,true,'test',__DIR__);

		$request=new RequestTest('/test/1','GET');

		$config=$this->getConfig();
		$request->setConfig($config->getNodes('action')[2]);

		$event=new AuthenticateEvent($config);
		$executeActionEvent=new ExecuteActionEvent($request);
		$exception=null;
		try{
			$event->onExecuteAction($executeActionEvent);
		}
		catch(\Exception $e){
			$exception=$e;
		}

		$this->assertInstanceOf(PermissionDeniedException::class,$exception);

	}

	private function getConfig(){
		$configBuilder=new ConfigBuilder();
		$structure=new ActionStructure();
		$structure->doConfig($configBuilder);
		$structure=new ActionAuthenticateStructure();
		$structure->doConfig($configBuilder);
		$configBuilder->addReader(new XmlFileReader(__DIR__.'/../../Asset/Action/AuthenticateEvent/config.xml'));
		return new Config($configBuilder->parse());
	}

}