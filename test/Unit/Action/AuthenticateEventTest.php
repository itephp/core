<?php

namespace Test\Action;

use ItePHP\Core\ExecuteActionEvent;
use ItePHP\Action\AuthenticateEvent;
use ItePHP\Structure\ActionStructure;
use ItePHP\Structure\ActionAuthenticateStructure;
use ItePHP\Action\PermissionDeniedException;
use Pactum\ConfigBuilder;
use Pactum\Reader\XMLReader;
use Test\Asset\RequestTest;

class AuthenticateEventTest extends \PHPUnit_Framework_TestCase{
	
	public function testOnExecuteActionAutheticate(){
        
		$request=new RequestTest('/test/1','GET');
		$request->getSession()->set('authenticate.user_id',1);
		$request->getSession()->set('authenticate.epoch',time()+10);

		$config=$this->getConfig();
		$request->setConfig($config->getArray('action')[0]);

		$event=new AuthenticateEvent($config);
		$executeActionEvent=new ExecuteActionEvent($request);

		$event->onExecuteAction($executeActionEvent);
		$response=$executeActionEvent->getResponse();

		$this->assertNull($response);

	}

	public function testOnExecuteActionAutheticateRedirect(){


		$request=new RequestTest('/test/1','GET');
		$request->getSession()->set('authenticate.user_id',1);
		$request->getSession()->set('authenticate.epoch',time()+10);

		$config=$this->getConfig();
		$request->setConfig($config->getArray('action')[1]);

		$event=new AuthenticateEvent($config);
		$executeActionEvent=new ExecuteActionEvent($request);
		$event->onExecuteAction($executeActionEvent);
		$response=$executeActionEvent->getResponse();

		$this->assertEquals(302,$response->getStatusCode());
		$this->assertEquals('/logout',$response->getHeader('location'));

	}

	public function testOnExecuteActionUnautheticate(){

		$request=new RequestTest('/test/1','GET');

		$config=$this->getConfig();
		$request->setConfig($config->getArray('action')[1]);

		$event=new AuthenticateEvent($config);
		$executeActionEvent=new ExecuteActionEvent($request);
		$event->onExecuteAction($executeActionEvent);
		$response=$executeActionEvent->getResponse();

		$this->assertNull($response);

	}

	public function testOnExecuteActionUnautheticateRedirect(){

		$request=new RequestTest('/test/1','GET');

		$config=$this->getConfig();
		$request->setConfig($config->getArray('action')[0]);

		$event=new AuthenticateEvent($config);
		$executeActionEvent=new ExecuteActionEvent($request);
		$event->onExecuteAction($executeActionEvent);
		$response=$executeActionEvent->getResponse();

		$this->assertEquals(302,$response->getStatusCode());
		$this->assertEquals('/login',$response->getHeader('location'));

	}

	public function testOnExecuteActionPermissionDeniedException(){

		$request=new RequestTest('/test/1','GET');

		$config=$this->getConfig();
		$request->setConfig($config->getArray('action')[2]);

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
		$configBuilder->addReader(new XMLReader(__DIR__.'/../../Asset/Action/AuthenticateEvent/config.xml'));
		return $configBuilder->parse();
	}

}