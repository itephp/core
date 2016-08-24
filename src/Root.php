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

use ItePHP\Core\Request;
use ItePHP\Core\HTTPRequest;
use ItePHP\Provider\Session;
use ItePHP\Core\Enviorment;
use ItePHP\DependencyInjection\DependencyInjection;
use ItePHP\DependencyInjection\MetadataClass;
use ItePHP\DependencyInjection\MetadataMethod;

use ItePHP\Config\ConfigBuilder;
use ItePHP\Config\ConfigBuilderNode;
use ItePHP\Config\XmlFileReader;

use ItePHP\Route\Router;

use ItePHP\Error\ErrorManager;

use ItePHP\Route\RouteNotFoundException;

use ItePHP\Core\Config;
use ItePHP\Core\HTTPException;
use ItePHP\Core\CriticalErrorHandler;
use ItePHP\Core\HTTPErrorHandler;
use ItePHP\Core\HTTPDispatcher;
use ItePHP\Core\Response;
use ItePHP\Core\Container;
use ItePHP\Core\ConsoleErrorHandler;
use ItePHP\Core\CommandNotFoundException;
use ItePHP\Core\ConsoleDispatcher;

/**
 * Main class of project
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @version 0.4.0
 */
class Root{
	
	/**
	 *
	 * @var Container
	 */
	private $container;

	/**
	 *
	 * @var Config
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

	/**
	 *
	 * @param Enviorment $enviorment
	 */
	public function __construct(Enviorment $enviorment){
		$this->enviorment=$enviorment;
	}
	
	/**
	 *
	 * @param array $command
	 * @return int
	 */
	public function executeCommand($command){
		//config
		$this->errorManager=new ErrorManager();
		$this->errorManager->addHandler(new ConsoleErrorHandler());
		$dependencyInjection=new DependencyInjection();

		$this->initConfig();

		$snippets=$this->getSnippets($dependencyInjection);
		$this->container=new Container($dependencyInjection,$snippets);
		$dependencyInjection->addInstance('container',$this->container);
		$dependencyInjection->addInstance('enviorment',$this->enviorment);
		$dependencyInjection->addInstance('config',$this->config);

		$this->registerEventManager($dependencyInjection);
		$this->registerServices($dependencyInjection);
		$this->registerEvents($dependencyInjection);
		$this->registerCommands($dependencyInjection);

		//command
		$sigint=1;
		$commandName=$command[0];
		array_shift($command);
		$arguments=$command;
		try{
			$dispatcher=$this->createConsoleRouter($dependencyInjection,$arguments)->createDispatcher($commandName);
			$dispatcher->execute();
			$sigint=0;
		}
		catch(RouteNotFoundException $e){
			$this->errorManager->exception(new CommandNotFoundException($commandName));
		}
		catch(\Exception $e){
			$this->errorManager->exception($e);
		}

		return $sigint;

	}

	/**
	 *
	 * @param string $url
	 */
	public function executeRequest($url){

		//config
		$this->errorManager=new ErrorManager();
		$this->errorManager->addHandler(new CriticalErrorHandler($this->enviorment));
		$dependencyInjection=new DependencyInjection();

		$this->initConfig();

		$snippets=$this->getSnippets($dependencyInjection);
		$this->container=new Container($dependencyInjection,$snippets);
		$dependencyInjection->addInstance('container',$this->container);
		$dependencyInjection->addInstance('enviorment',$this->enviorment);
		$dependencyInjection->addInstance('config',$this->config);

		$this->registerServices($dependencyInjection);
		$this->registerEventManager($dependencyInjection);
		$this->registerEvents($dependencyInjection);
		$this->registerPresenters($dependencyInjection);

		//request
		$session=new Session($this->enviorment);
		$request=new HTTPRequest($url,$session);

	        $dependencyInjection->addInstance('request',$request);

		$this->reconfigureErrorManager($dependencyInjection,$request);

		try{
			$dispatcher=$this->createHttpRouter($dependencyInjection,$request)->createDispatcher($url);
			$dispatcher->execute();
		}
		catch(RouteNotFoundException $e){
			$this->errorManager->exception(new HTTPException(404,$e->getMessage()));
		}
		catch(\Exception $e){
			$this->errorManager->exception($e);
		}

	}

	/**
	 *
	 * @param string $name
	 * @return object
	 */
	public function getService($name){
		return $this->$this->container->getService($name);
	}

	/**
	 * Init framework config
	 */
	private function initConfig(){
		//config structure
		$structureConfig=new ConfigBuilder();

		$xmlReader=new XmlFileReader($this->enviorment->getConfigPath().'/structure.xml');
		$structureConfig->addReader($xmlReader);

		$structureNode=new ConfigBuilderNode('structure');
		$structureNode->addAttribute('class');
		$structureConfig->addNode($structureNode);

		$structureContainer=$structureConfig->parse();

		$xmlReader=new XmlFileReader($this->enviorment->getConfigPath().'/'.$this->enviorment->getName().'.xml');
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
			$xmlReader=new XmlFileReader($this->enviorment->getConfigPath().'/'.$importNode->getAttribute('file'));
			$mainConfig->addReader($xmlReader);
		}

		foreach($structureContainer->getNodes('structure') as $structureNode){
			$className=$structureNode->getAttribute('class');
			$structureObj=new $className();
			$structureObj->doConfig($mainConfig);
		}

		$this->config=new Config($mainConfig->parse());
	}

	/**
	 *
	 * @param DependencyInjection $dependencyInjection
	 * @param Request $request
	 */
	private function reconfigureErrorManager(DependencyInjection $dependencyInjection,Request $request){
		$removeHandlers=$this->errorManager->getHandlers();

		$this->errorManager->addHandler(
			new HTTPErrorHandler(
				$dependencyInjection,$request
			)
		);

		foreach($removeHandlers as $handler){
			$this->errorManager->removeHandler($handler);
		}

	}

	/**
	 *
	 * @param DependencyInjection $dependencyInjection
	 * @param Request $request
	 * @return Router
	 */
	private function createHttpRouter(DependencyInjection $dependencyInjection,Request $request){
		$router=new Router();
		$presenters=$this->getPresenters($dependencyInjection);
		foreach($this->config->getNodes('action') as $actionNode){
			$router->addAction($actionNode->getAttribute('route'),
				new HTTPDispatcher($actionNode,
					$this->container,
					$request,
					$this->enviorment,
					$presenters
				)
			);
		}

		return $router;
	}

	/**
	 *
	 * @param DependencyInjection $dependencyInjection
	 * @return array
	 */
	private function getPresenters(DependencyInjection $dependencyInjection){
		$presenters=[];
		foreach($this->config->getNodes('presenter') as $presenterNode){
			$presenters[$presenterNode->getAttribute('name')]=$dependencyInjection->get('presenter.'.$presenterNode->getAttribute('name'));
		}

		return $presenters;
	}

	/**
	 *
	 * @param DependencyInjection $dependencyInjection
	 * @param array $commandArguments
	 * @return Router
	 */
	private function createConsoleRouter(DependencyInjection $dependencyInjection,$commandArguments){
		$router=new Router();

		foreach($this->config->getNodes('command') as $commandNode){
			$router->addAction($commandNode->getAttribute('name'),
				new ConsoleDispatcher($commandNode,
					$dependencyInjection,
					$commandArguments
				)
			);
		}

		return $router;
	}

	/**
	 *
	 * @param DependencyInjection $dependencyInjection
	 */
	private function registerEventManager(DependencyInjection $dependencyInjection){
		$metadataClass=new MetadataClass('eventManager','ItePHP\Core\EventManager');
		$dependencyInjection->register($metadataClass);
	}

	/**
	 *
	 * @param DependencyInjection $dependencyInjection
	 */
	private function registerEvents(DependencyInjection $dependencyInjection){
		foreach($this->config->getNodes('event') as $eventNode){
			$name=$eventNode->getAttribute('class');
			$metadataClass=$this->getMetadataClass($name,$eventNode);
			$dependencyInjection->register($metadataClass);

			$this->eventManagerBind($eventNode,$dependencyInjection);
		}
	}

	/**
	 *
	 * @param DependencyInjection $dependencyInjection
	 */
	private function registerServices(DependencyInjection $dependencyInjection){
		foreach($this->config->getNodes('service') as $serviceNode){
			$metadataClass=$this->getMetadataClass('service.'.$serviceNode->getAttribute('name'),$serviceNode);
			$dependencyInjection->register($metadataClass);
		}
	}

	/**
	 *
	 * @param DependencyInjection $dependencyInjection
	 */
	private function registerPresenters(DependencyInjection $dependencyInjection){
		foreach($this->config->getNodes('presenter') as $presenterNode){
			$metadataClass=$this->getMetadataClass('presenter.'.$presenterNode->getAttribute('name'),$presenterNode);
			$dependencyInjection->register($metadataClass);
		}
	}

	/**
	 *
	 * @param DependencyInjection $dependencyInjection
	 */
	private function registerCommands(DependencyInjection $dependencyInjection){
		foreach($this->config->getNodes('command') as $commandNode){
			$metadataClass=$this->getMetadataClass('command.'.$commandNode->getAttribute('class'),$commandNode);
			$dependencyInjection->register($metadataClass);
		}
	}

	/**
	 *
	 * @param DependencyInjection $dependencyInjection
	 * @return array
	 */
	private function getSnippets(DependencyInjection $dependencyInjection){
		$snippets=[];
		foreach($this->config->getNodes('snippet') as $snippetNode){
			$className=$snippetNode->getAttribute('class');
			$snippets[$snippetNode->getAttribute('method')]=new $className();
		}

		return $snippets;
	}

	/**
	 *
	 * @param Config $eventNode
	 * @param DependencyInjection $dependencyInjection
	 */
	private function eventManagerBind(Config $eventNode,DependencyInjection $dependencyInjection){
		$eventManager=$this->container->getEventManager();
		foreach($eventNode->getNodes('bind') as $bindNode){
			$eventManager->register(
				$bindNode->getAttribute('name'),
				$dependencyInjection->get($eventNode->getAttribute('class')),
				$bindNode->getAttribute('method')
			);
		}
	}

	/**
	 *
	 * @param string $name
	 * @param Config $classNode
	 * @return MetadataClass
	 */
	private function getMetadataClass($name,Config $classNode){
		$metadataClass=new MetadataClass($name,$classNode->getAttribute('class'));
		foreach($classNode->getNodes('method') as $methodNode){
			$metadataMethod=$this->getMetadataDependencyMethod($methodNode);
			$metadataClass->registerInvoke($metadataMethod);
		}

		return $metadataClass;
	}

	/**
	 *
	 * @param Config $methodNode
	 * @return MetadataMethod
	 */
	private function getMetadataDependencyMethod(Config $methodNode){
		$metadataMethod=new MetadataMethod($methodNode->getAttribute('name'));
		foreach($methodNode->getNodes('argument') as $argumentNode){
			$metadataMethod->addArgument($argumentNode->getAttribute('type'),$argumentNode->getAttribute('value'));
		}

		return $metadataMethod;
	}

}
