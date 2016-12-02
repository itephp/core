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

use ItePHP\Core\Presenter;
use ItePHP\Core\Request;
use ItePHP\Core\HTTPRequest;
use ItePHP\Provider\Session;
use ItePHP\Core\Environment;
use ItePHP\DependencyInjection\DependencyInjection;
use ItePHP\DependencyInjection\MetadataClass;
use ItePHP\DependencyInjection\MetadataMethod;

use ItePHP\Error\ErrorManager;

use ItePHP\Core\HTTPException;
use ItePHP\Core\CriticalErrorHandler;
use ItePHP\Core\HTTPErrorHandler;
use ItePHP\Core\HTTPDispatcher;
use ItePHP\Core\Container;
use ItePHP\Core\ConsoleErrorHandler;
use ItePHP\Core\CommandNotFoundException;
use ItePHP\Core\ConsoleDispatcher;
use ItePHP\Structure\ImportStructure;
use ItePHP\Structure\Structure;
use ItePHP\Structure\VariableStructure;
use Pactum\ConfigBuilder;
use Pactum\ConfigBuilderObject;
use Pactum\ConfigBuilderValue;
use Pactum\ConfigContainer;
use Pactum\ParserProcess;
use Pactum\Reader\XMLReader;
use Via\Action\HTTPAction;
use Via\Action\StringAction;
use Via\RouteNotFoundException;
use Via\Router;

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
	 * @var ConfigContainer
	 */
	private $config;

	/**
	 *
	 * @var Environment
	 */
	private $environment;

	/**
	 *
	 * @var ErrorManager
	 */
	private $errorManager;

	/**
	 *
	 * @param Environment $environment
	 */
	public function __construct(Environment $environment){
		$this->environment=$environment;
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

		$this->container=new Container($dependencyInjection);
		$dependencyInjection->addInstance('container',$this->container);
		$dependencyInjection->addInstance('environment',$this->environment);
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
		$this->errorManager->addHandler(new CriticalErrorHandler($this->environment));
		$dependencyInjection=new DependencyInjection();

		$this->initConfig();

		$this->container=new Container($dependencyInjection);
		$dependencyInjection->addInstance('container',$this->container);
		$dependencyInjection->addInstance('environment',$this->environment);
		$dependencyInjection->addInstance('config',$this->config);

		$this->registerServices($dependencyInjection);
		$this->registerEventManager($dependencyInjection);
		$this->registerEvents($dependencyInjection);
		$this->registerPresenters($dependencyInjection);

		//request
		$session=new Session($this->environment);
		$request=new HTTPRequest($url,$session);

        $dependencyInjection->addInstance('request',$request);

		$this->reconfigureErrorManager($dependencyInjection,$request);

		try{
			$dispatcher=$this->createHttpRouter($dependencyInjection,$request)
                ->createDispatcher(new \Via\Action\HTTPRequest($request->getUrl(),$request->getType()));
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

		$xmlReader=new XMLReader($this->environment->getConfigPath().'/structure.xml');
		$structureConfig->addReader($xmlReader);

        $structureConfig->addArray('structure',new ConfigBuilderValue("string"));

		$structureContainer=$structureConfig->parse();

		$xmlReader=new XMLReader($this->environment->getConfigPath().'/'.$this->environment->getName().'.xml');
		$mainConfig=new ConfigBuilder();
        $mainConfig->addReader($xmlReader);
        $mainConfig->addFilter(function (ParserProcess $process){//import
            if($process->getType()!=='string' || $process->getPath()!=='root->import'){
                return;
            }

            $xmlReader=new XMLReader($this->environment->getConfigPath().'/'.$process->getValue());
            $process->addReader($xmlReader);

        });

        $variables=[];
        $mainConfig->addFilter(function (ParserProcess $process) use (&$variables){

            if($process->getType()!=='array' || $process->getPath()!=='root->variable') {
                return;
            }
            /**
             * @var ConfigBuilderObject $value
             */
            foreach($process->getValue() as $value){
                $variables[$value->getValue('name')]=$value->getValue('value');
            }

        });

        $mainConfig->addFilter(function (ParserProcess $process) use(&$variables){
            if(in_array($process->getType(),['string','boolean','mixed','number']) && is_string($process->getValue())){

                $value=$process->getValue();
                preg_match_all('/@\{(.+?)\}/',$process->getValue(),$matches);
                for($i=0; $i<count($matches[0]); $i++){
                    if(is_string($variables[$matches[1][$i]]) && $value!==$matches[0][$i]){
                        $value=str_replace($matches[0][$i],$variables[$matches[1][$i]],$value);
                        continue;
                    }
                    $value=$variables[$matches[1][$i]];

                }
                $process->setValue($value);

            }
        });

        //config structure
        $importStructure=new ImportStructure();
        $importStructure->doConfig($mainConfig);

        $variableStructure=new VariableStructure();
        $variableStructure->doConfig($mainConfig);

        //$xmlReader=new XMLReader($this->environment->getConfigPath().'/'.$importValue);

        foreach($structureContainer->getArray('structure') as $structureValue){
            /**
             * @var Structure $structureObj
             */
			$structureObj=new $structureValue();
			$structureObj->doConfig($mainConfig);
		}
		$this->config=$mainConfig->parse();
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
		foreach($this->config->getArray('action') as $actionObject){
		    $this->addHttpMethodActions($router,$actionObject,$request,$presenters);
		}

		return $router;
	}

    /**
     * @param Router $router
     * @param ConfigContainer $actionNode
     * @param Request $request
     * @param Presenter[] $presenters
     */
	private function addHttpMethodActions($router,$actionNode,$request,$presenters){
        $path=$actionNode->getValue('path');
        $httpMethods=$actionNode->getValue('http-method');
	    foreach (explode(",",$httpMethods) as $httpMethod){
            $router->addAction(new HTTPAction($path,$httpMethod),
                new HTTPDispatcher($actionNode,
                    $this->container,
                    $request,
                    $this->environment,
                    $presenters
                )
            );
        }

    }

	/**
	 *
	 * @param DependencyInjection $dependencyInjection
	 * @return array
	 */
	private function getPresenters(DependencyInjection $dependencyInjection){
		$presenters=[];
		foreach($this->config->getArray('presenter') as $presenterNode){
            /**
             * @var ConfigContainer $presenterNode
             */
			$presenters[$presenterNode->getValue('name')]=$dependencyInjection->get('presenter.'.$presenterNode->getValue('name'));
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

		foreach($this->config->getArray('command') as $commandNode){
            /**
             * @var ConfigContainer $commandNode
             */
			$router->addAction(new StringAction($commandNode->getValue('name')),
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
		foreach($this->config->getArray('event') as $eventNode){
            /**
             * @var ConfigContainer $eventNode
             */

			$name=$eventNode->getValue('class');
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
		foreach($this->config->getArray('service') as $serviceNode){
            /**
             * @var ConfigContainer $serviceNode
             */
			$metadataClass=$this->getMetadataClass('service.'.$serviceNode->getValue('name'),$serviceNode,$serviceNode->getValue('singleton')==="true");
			$dependencyInjection->register($metadataClass);
		}
	}

	/**
	 *
	 * @param DependencyInjection $dependencyInjection
	 */
	private function registerPresenters(DependencyInjection $dependencyInjection){
		foreach($this->config->getArray('presenter') as $presenterNode){
            /**
             * @var ConfigContainer $presenterNode
             */
			$metadataClass=$this->getMetadataClass('presenter.'.$presenterNode->getValue('name'),$presenterNode);
			$dependencyInjection->register($metadataClass);
		}
	}

	/**
	 *
	 * @param DependencyInjection $dependencyInjection
	 */
	private function registerCommands(DependencyInjection $dependencyInjection){
		foreach($this->config->getArray('command') as $commandNode){
            /**
             * @var ConfigContainer $commandNode
             */
			$metadataClass=$this->getMetadataClass('command.'.$commandNode->getValue('name'),$commandNode);
			$dependencyInjection->register($metadataClass);
		}
	}

	/**
	 *
	 * @param ConfigContainer $eventNode
	 * @param DependencyInjection $dependencyInjection
	 */
	private function eventManagerBind(ConfigContainer $eventNode,DependencyInjection $dependencyInjection){
		$eventManager=$this->container->getEventManager();
		foreach($eventNode->getArray('bind') as $bindNode){
            /**
             * @var ConfigContainer $bindNode
             */
			$eventManager->register(
				$bindNode->getValue('name'),
				$dependencyInjection->get($eventNode->getValue('class')),
				$bindNode->getValue('method')
			);
		}
	}

    /**
     *
     * @param string $name
     * @param ConfigContainer $classNode
     * @param bool $singleton
     * @return MetadataClass
     */
	private function getMetadataClass($name,ConfigContainer $classNode,$singleton=true){
		$metadataClass=new MetadataClass($name,$classNode->getValue('class'),$singleton);
		foreach($classNode->getArray('method') as $methodNode){
			$metadataMethod=$this->getMetadataDependencyMethod($methodNode);
			$metadataClass->registerInvoke($metadataMethod);
		}

		return $metadataClass;
	}

	/**
	 *
	 * @param ConfigContainer $methodNode
	 * @return MetadataMethod
	 */
	private function getMetadataDependencyMethod(ConfigContainer $methodNode){
		$metadataMethod=new MetadataMethod($methodNode->getValue('name'));
		foreach($methodNode->getArray('argument') as $argumentNode){
            /**
             * @var ConfigContainer $argumentNode
             */
			$metadataMethod->addArgument($argumentNode->getValue('type'),$argumentNode->getValue('value'));
		}

		return $metadataMethod;
	}

}
