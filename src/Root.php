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
use ItePHP\Core\RequestProvider;
use ItePHP\Provider\Request;
use ItePHP\Provider\Session;
use ItePHP\Core\Presenter;
use ItePHP\Event\ExecuteActionEvent;
use ItePHP\Event\ExecutedActionEvent;
use ItePHP\Event\ExecutePresenterEvent;
use ItePHP\Exception\ActionNotFoundException;
use ItePHP\Exception\CommandNotFoundException;
use ItePHP\Core\ExecuteResources;
use ItePHP\Core\Enviorment;
use ItePHP\Test\Request as RequestTest;
use ItePHP\Exception\ServiceNotFoundException;
use ItePHP\DependencyInjection\DependencyInjection;
use ItePHP\DependencyInjection\MetadataClass;
use ItePHP\DependencyInjection\MetadataMethod;

use ItePHP\Config\ConfigBuilder;
use ItePHP\Config\ConfigBuilderNode;
use ItePHP\Config\XmlFileReader;
use ItePHP\Config\ConfigContainer;
use ItePHP\Config\ConfigContainerNode;

use ItePHP\Core\HttpDispatcher;
use ItePHP\Core\Response;

use ItePHP\Route\Router;

use ItePHP\Error\ErrorManager;
use ItePHP\Core\CriticalErrorHandler;

/**
 * Main class of project
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 * @version 0.3.0
 */
class Root{
	
	private $executeResources;

	/**
	 *
	 * @var DependencyInjection
	 */
	private $dependencyInjection;

	/**
	 *
	 * @var array
	 */
	private $snippets=[];

	/**
	 *
	 * @var ConfigContainer
	 */
	private $config;

	/**
	 *
	 * @var Enviorment
	 */
	private $enviorment;

	/**
	 *
	 * @var ErrorManager
	 */
	private $errorManager;

	public function __construct(Enviorment $enviorment){
		$this->enviorment=$enviorment;
		$this->executeResources=new ExecuteResources();
		$this->executeResources->registerEnviorment($enviorment);

		$this->dependencyInjection=new DependencyInjection();
		$this->registerEventManager();
		$this->errorManager=new ErrorManager();
		$this->errorManager->addHandler(new CriticalErrorHandler($enviorment));

		$this->initConfig();
		$this->registerServices();
		$this->registerEvents();
		$this->registerSnippets();
	}

	private function initConfig(){
		//config structure
		$structureConfig=new ConfigBuilder();

		$xmlReader=new XmlFileReader(ITE_ROOT.'/config/structure.xml');
		$structureConfig->addReader($xmlReader);

		$structureNode=new ConfigBuilderNode('structure');
		$structureNode->addAttribute('class');
		$structureConfig->addNode($structureNode);

		$structureContainer=$structureConfig->parse();

		$xmlReader=new XmlFileReader(ITE_ROOT.'/config/'.$this->enviorment->getName().'.xml');
		$importConfig=new ConfigBuilder();
		$importConfig->addReader($xmlReader);

		//config import
		$importNode=new ConfigBuilderNode('import');
		$importNode->addAttribute('file');
		$importConfig->addNode($importNode);

		$importContainer=$importConfig->parse();

		//config main
		$mainConfig=new ConfigBuilder();

		foreach($importContainer->getNodes('import') as $importNode){
			$xmlReader=new XmlFileReader(ITE_ROOT.'/config/'.$importNode->getAttribute('file'));
			$mainConfig->addReader($xmlReader);
		}

		foreach($structureContainer->getNodes('structure') as $structureNode){
			$className=$structureNode->getAttribute('class');
			$structureObj=new $className();
			$structureObj->doConfig($mainConfig);
		}

		$this->config=$mainConfig->parse();
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

	public function executeRequest($url){

		$this->reconfigureErrorManager();

		$session=new Session($this->enviorment);
		$request=new Request($url,$session);

		try{
			$dispatcher=$this->createHttpRouter($request)->createDispatcher($url);
			$dispatcher->execute();
		}
		catch(\Exception $e){//FIXME check route not found exception (then set 404 status code)
			$this->errorManager->exception($e);
		}

	}

	private function reconfigureErrorManager(){
		$removeHandlers=$this->errorManager->getHandlers();

		$this->errorManager->addHandler(new HTTPErrorHandler($this->enviorment,$this->config));

		foreach($removeHandlers as $handlers){
			$this->errorManager->removeHandler($handler);
		}

	}

	private function createHttpRouter(RequestProvider $request){
		$router=new Router();

		foreach($this->config->getNodes('action') as $actionNodes){
			$router->addAction($actionNodes->getAttribute('route'),
				new HttpDispatcher($actionNodes->getAttribute('class'),$actionNodes->getAttribute('method'),
					$actionNodes->getAttribute('presenter'),
					$this->dependencyInjection,
					$request,
					$this->enviorment,$this->snippets
				)
			);
		}

		return $router;
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
			$this->errorManager->exception($e);
		}
		$content=ob_get_clean();
		ob_flush();

		$this->executeResources->getResponse()->setContent($content);
		return $this->executeResources->getResponse();

	}

	public function getService($name){
		return $this->dependencyInjection->get($name);
	}

	private function registerEventManager(){
		$metadataClass=new MetadataClass('ite.eventManager','ItePHP\Core\EventManager');
		$this->dependencyInjection->register($metadataClass);
	}

	private function registerEvents(){
		foreach($this->config->getNodes('event') as $eventNode){
			$name=$eventNode->getAttribute('class');
			$metadataClass=$this->getMetadataClass($name,$eventNode);
			$this->dependencyInjection->register($metadataClass);

			$this->eventManagerBind($eventNode);
		}

	}

	private function registerServices(){

		foreach($this->config->getNodes('service') as $serviceNode){
			$metadataClass=$this->getMetadataClass($serviceNode->getAttribute('name'),$serviceNode);
			$this->dependencyInjection->register($metadataClass);
		}
	}

	private function registerSnippets(){
		foreach($this->config->getNodes('snippet') as $snippetNode){
			$className=$snippetNode->getAttribute('class');
			$this->snippets[$snippetNode->getAttribute('method')]=new $className();
		}
	}

	private function eventManagerBind(ConfigContainerNode $eventNode){
		$eventManager=$this->dependencyInjection->get('ite.eventManager');
		foreach($eventNode->getNodes('bind') as $bindNode){
			$eventManager->register(
				$bindNode->getAttribute('name'),
				$this->dependencyInjection->get($eventNode->getAttribute('class')),
				$bindNode->getAttribute('method')
			);
		}

	}

	private function getMetadataClass($name,ConfigContainerNode $classNode){
		$metadataClass=new MetadataClass($name,$classNode->getAttribute('class'));
		foreach($classNode->getNodes('method') as $methodNode){
			$metadataMethod=$this->getMetadataDependencyMethod($methodNode);
			$metadataClass->registerInvoke($metadataMethod);
		}

		return $metadataClass;
	}

	private function getMetadataDependencyMethod(ConfigContainerNode $methodNode){
		$metadataMethod=new MetadataMethod($methodNode->getAttribute('name'));
		foreach($methodNode->getNodes('argument') as $argumentNode){
			$metadataMethod->addArgument($argumentNode->getAttribute('type'),$argumentNode->getAttribute('value'));
		}

		return $metadataMethod;

	}

}