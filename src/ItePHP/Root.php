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

use ItePHP\Core\EventManager;
use ItePHP\Core\Presenter;
use ItePHP\Core\Request;
use ItePHP\Core\HTTPRequest;
use ItePHP\Provider\Session;
use ItePHP\Core\Environment;

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
use Onus\ClassLoader;
use Onus\MetadataClass;
use Onus\MetadataMethod;
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
		$classLoader=new ClassLoader();

		$this->initConfig();

		$this->container=new Container($classLoader);
		$classLoader->addInstance('container',$this->container);
		$classLoader->addInstance('environment',$this->environment);
        $classLoader->addInstance('classLoader',$classLoader);
		$classLoader->addInstance('config',$this->config);

		$this->registerEventManager($classLoader);
		$this->registerServices($classLoader);
		$this->registerEvents($classLoader);
		$this->registerCommands($classLoader);

		//command
		$sigint=1;
		$commandName=$command[0];
		array_shift($command);
		$arguments=$command;
		try{
			$dispatcher=$this->createConsoleRouter($classLoader,$arguments)->createDispatcher($commandName);
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
		$classLoader=new ClassLoader();

		$this->initConfig();

		$this->container=new Container($classLoader);
		$classLoader->addInstance('container',$this->container);
		$classLoader->addInstance('environment',$this->environment);
        $classLoader->addInstance('classLoader',$classLoader);
		$classLoader->addInstance('config',$this->config);

		$this->registerServices($classLoader);
		$this->registerEventManager($classLoader);
		$this->registerEvents($classLoader);
		$this->registerPresenters($classLoader);

		//request
		$session=new Session($this->environment);
		$request=new HTTPRequest($url,$session);

        $classLoader->addInstance('request',$request);

		$this->reconfigureErrorManager($classLoader,$request);

		try{
			$dispatcher=$this->createHttpRouter($classLoader,$request)
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
	 * @param ClassLoader $classLoader
	 * @param Request $request
	 */
	private function reconfigureErrorManager(ClassLoader $classLoader, Request $request){
		$removeHandlers=$this->errorManager->getHandlers();

		$this->errorManager->addHandler(
			new HTTPErrorHandler(
				$classLoader,$request
			)
		);

		foreach($removeHandlers as $handler){
			$this->errorManager->removeHandler($handler);
		}

	}

	/**
	 *
	 * @param ClassLoader $classLoader
	 * @param Request $request
	 * @return Router
	 */
	private function createHttpRouter(ClassLoader $classLoader, Request $request){
		$router=new Router();
		$presenters=$this->getPresenters($classLoader);
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
	 * @param ClassLoader $classLoader
	 * @return array
	 */
	private function getPresenters(ClassLoader $classLoader){
		$presenters=[];
		foreach($this->config->getArray('presenter') as $presenterNode){
            /**
             * @var ConfigContainer $presenterNode
             */
			$presenters[$presenterNode->getValue('name')]=$classLoader->get('presenter.'.$presenterNode->getValue('name'));
		}

		return $presenters;
	}

	/**
	 *
	 * @param ClassLoader $classLoader
	 * @param array $commandArguments
	 * @return Router
	 */
	private function createConsoleRouter(ClassLoader $classLoader, $commandArguments){
		$router=new Router();

		foreach($this->config->getArray('command') as $commandNode){
            /**
             * @var ConfigContainer $commandNode
             */
			$router->addAction(new StringAction($commandNode->getValue('name')),
				new ConsoleDispatcher($commandNode,
					$classLoader,
					$commandArguments
				)
			);
		}

		return $router;
	}

	/**
	 *
	 * @param ClassLoader $classLoader
	 */
	private function registerEventManager(ClassLoader $classLoader){
		$metadataClass=new MetadataClass('eventManager',EventManager::class);
		$classLoader->register($metadataClass);
	}

    /**
     *
     * @param ClassLoader $classLoader
     */
	private function registerEvents(ClassLoader $classLoader){
		foreach($this->config->getArray('event') as $eventNode){
            /**
             * @var ConfigContainer $eventNode
             */

			$name=$eventNode->getValue('class');
			$metadataClass=$this->getMetadataClass($name,$eventNode);
			$classLoader->register($metadataClass);

			$this->eventManagerBind($eventNode,$classLoader);
		}
	}

	/**
	 *
	 * @param ClassLoader $classLoader
	 */
	private function registerServices(ClassLoader $classLoader){
		foreach($this->config->getArray('service') as $serviceNode){
            /**
             * @var ConfigContainer $serviceNode
             */
			$metadataClass=$this->getMetadataClass('service.'.$serviceNode->getValue('name'),$serviceNode,$serviceNode->getValue('singleton')==="true");
			$classLoader->register($metadataClass);
		}
	}

	/**
	 *
	 * @param ClassLoader $classLoader
	 */
	private function registerPresenters(ClassLoader $classLoader){
		foreach($this->config->getArray('presenter') as $presenterNode){
            /**
             * @var ConfigContainer $presenterNode
             */
			$metadataClass=$this->getMetadataClass('presenter.'.$presenterNode->getValue('name'),$presenterNode);
			$classLoader->register($metadataClass);
		}
	}

	/**
	 *
	 * @param ClassLoader $classLoader
	 */
	private function registerCommands(ClassLoader $classLoader){
		foreach($this->config->getArray('command') as $commandNode){
            /**
             * @var ConfigContainer $commandNode
             */
			$metadataClass=$this->getMetadataClass('command.'.$commandNode->getValue('name'),$commandNode);
			$classLoader->register($metadataClass);
		}
	}

	/**
	 *
	 * @param ConfigContainer $eventNode
	 * @param ClassLoader $classLoader
	 */
	private function eventManagerBind(ConfigContainer $eventNode,ClassLoader $classLoader){
		$eventManager=$this->container->getEventManager();
		foreach($eventNode->getArray('bind') as $bindNode){
            /**
             * @var ConfigContainer $bindNode
             */
			$eventManager->register(
				$bindNode->getValue('name'),
				$classLoader->get($eventNode->getValue('class')),
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
			$metadataClass->register($metadataMethod);
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
