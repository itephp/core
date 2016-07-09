<?php

/**
 * ItePHP: Framework PHP (http://itephp.com)
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the file LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) NewClass (http://newclass.pl)
 * @link          http://itephp.com ItePHP Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace ItePHP;

use ItePHP\Contener\GlobalConfig;
use ItePHP\Contener\RequestConfig;
use ItePHP\Contener\ServiceConfig;
use ItePHP\Contener\CommandConfig;
use ItePHP\Provider\Response;
use ItePHP\Core\RequestProvider;
use ItePHP\Provider\Request;
use ItePHP\Provider\Session;
use ItePHP\Core\Presenter;
use ItePHP\Core\ErrorHandler;
use ItePHP\Event\ExecuteActionEvent;
use ItePHP\Event\ExecutedActionEvent;
use ItePHP\Event\ExecutePresenterEvent;
use ItePHP\Exception\ActionNotFoundException;
use ItePHP\Exception\CommandNotFoundException;
use ItePHP\Core\ExecuteResources;
use ItePHP\Core\Enviorment;
use ItePHP\Test\Request as RequestTest;
use ItePHP\Core\Router;
use ItePHP\Exception\ServiceNotFoundException;
use ItePHP\DependencyInjection\DependencyInjection;
use ItePHP\DependencyInjection\MetadataClass;
use ItePHP\DependencyInjection\MetadataMethod;

use ItePHP\Config\ConfigBuilder;
use ItePHP\Config\ConfigBuilderNode;
use ItePHP\Config\XmlFileReader;


/**
 * Main class of project
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 * @version 0.3.0
 */
class Root{
	
	private $errorHandler;
	private $executeResources;
	private $router;
	private $dependencyInjection;
	private $config;
	private $enviorment;

	public function __construct($enviorment){
		$this->enviorment=$enviorment;
		$this->executeResources=new ExecuteResources();
		$this->executeResources->registerEnviorment($enviorment);

		$this->router=new Router();
		$this->dependencyInjection=new DependencyInjection();
		$this->registerEventManager();
		$this->errorHandler=new ErrorHandler($this->executeResources,$this->dependencyInjection->get('ite.eventManager'));

		$this->initConfig();
		// $this->executeResources->registerGlobalConfig(new GlobalConfig(__DIR__.'/../../../../config',$enviorment));
		$this->registerServices($this->executeResources);
		$this->registerEvents($this->executeResources);
		$this->registerSnippets($this->executeResources);
	}

	private function initConfig(){
		//config structure
		$xmlReader=new XmlFileReader(ITE_ROOT.'/config/structure.xml');
		$structureConfig=new ConfigBuilder($xmlReader);

		$structureNode=new ConfigBuilderNode('structure');
		$structureNode->addAttribute('class');

		$structureConfig->addNode($structureNode);

		$structureContainer=$structureConfig->parse();

		$xmlReader=new XmlFileReader(ITE_ROOT.'/config/'.$this->enviorment->getName().'.xml');
		$mainConfig=new ConfigBuilder($xmlReader);
		foreach($structureContainer->getNodes('structure') as $structureNode){
			$className=$structureNode->getAttribute('class');
			$structureObj=$className();
			$structureObj->config($mainConfig);
		}

		$this->config=$mainConfig->parse();
	}

	private function registerEventManager(){
		$metadataClass=new MetadataClass('ite.eventManager','ItePHP\Core\EventManager');
		$metadataMethod=new MetadataMethod('__construct');
		$metadataMethod->addArgument(MetadataMethod::PRIMITIVE_TYPE,$this->executeResources);
		$metadataClass->registerInvoke($metadataMethod);
		$this->dependencyInjection->register($metadataClass);
	}


	public function executeCommand($command){
		$sigint=0;
		try{
			$this->executeResources->registerUrl($command[0]);
			array_shift($command);
			$dispatcher=$this->router->createCommandDispatcher($this->executeResources->getEnviorment(),$this->executeResources->getGlobalConfig(),$this->executeResources->getUrl(),$command);
			$dispatcher->execute($this->executeResources,$this->dependencyInjection->get('ite.eventManager'));

		}
		catch(\Exception $e){
			echo $e->getMessage();
			$sigint=1;
		}

		return $sigint;

	}

	public function executeRequest(){
		try{
			$url=strstr($_SERVER['REQUEST_URI'],'?',true);
			if(!$url)
				$url=$_SERVER['REQUEST_URI'];
			$this->executeResources->registerUrl($url);

			$dispatcher=$this->router->createHttpDispatcher($this->executeResources->getEnviorment(),$this->executeResources->getGlobalConfig(),$this->executeResources->getUrl());
			$dispatcher->execute($this->executeResources,$this->dependencyInjection->get('ite.eventManager'));
		}
		catch(\Exception $e){
			$this->errorHandler->exception($e);
		}

	}

	public function executeRequestTest(RequestTest $request){
		$url=$request->getUrl();
		$this->executeResources->registerUrl($url);
		ob_start();
		try{
			$dispatcher=$this->router->createHttpTestDispatcher($this->executeResources->getEnviorment(),$this->executeResources->getGlobalConfig(),$this->executeResources->getUrl());
			$dispatcher->setRequest($request);
			$dispatcher->execute($this->executeResources,$this->dependencyInjection->get('ite.eventManager'));

		}
		catch(\Exception $e){
			$this->errorHandler->exception($e);
		}
		$content=ob_get_clean();
		ob_flush();

		$this->executeResources->getResponse()->setContent($content);
		return $this->executeResources->getResponse();

	}

	public function getService($name){
		$services=$this->executeResources->getServices();
		if(!isset($services[$name]))
			throw new ServiceNotFoundException($name);

		return $services[$name];
	}

	private function registerEvents(ExecuteResources $executeResources){
		foreach($executeResources->getGlobalConfig()->getEvents() as $bind=>$configs){
			foreach($configs as $config){
				$this->dependencyInjection->get('ite.eventManager')->register($bind,$config);
			}
		}

	}

	private function registerServices(ExecuteResources $executeResources){
		foreach($executeResources->getGlobalConfig()->getServices() as $service){
			$executeResources->registerService($service['name'] , new $service['class'](new ServiceConfig($service['config']),$this->dependencyInjection->get('ite.eventManager')));
		}
	}

	private function registerSnippets(ExecuteResources $executeResources){
		foreach($executeResources->getGlobalConfig()->getSnippets() as $snippet=>$class){
			$executeResources->registerSnippet($snippet,new $class());
		}
	}

}