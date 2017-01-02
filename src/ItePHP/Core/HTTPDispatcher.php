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

namespace ItePHP\Core;

use Config\Config\Action;
use Onus\ClassLoader;
use Via\Dispatcher;

/**
 * Dispatcher for http request
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class HTTPDispatcher implements Dispatcher
{
    /**
     * Request
     *
     * @var Request
     */
    protected $request;
    /**
     *
     * @var string
     */
    protected $className;
    /**
     *
     * @var string
     */
    protected $methodName;
    /**
     *
     * @var string
     */
    protected $responseName;
    /**
     *
     * @var Container
     */
    protected $container;
    /**
     *
     * @var Environment
     */
    protected $environment;
    /**
     *
     * @var Action
     */
    protected $config;
    /**
     * @var ClassLoader
     */
    private $classLoader;

    /**
     * Constructor.
     *
     * @param Action $config
     * @param Container $container
     * @param Request $request
     * @param Environment $environment
     * @param ClassLoader $classLoader
     */
    public function __construct(Action $config, Container $container, Request $request, Environment $environment,
                                ClassLoader $classLoader)
    {
        $this->config = $config;
        $this->className = $config->getClass();
        $this->methodName = $config->getMethod();
        $this->responseName = $config->getResponse();
        $this->classLoader = $classLoader;
        $this->request = $request;
        $this->container = $container;
        $this->environment = $environment;
    }


    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $this->request->setConfig($this->config);
        $eventManager = $this->container->getEventManager();

        $event = new ExecuteActionEvent($this->request);
        $eventManager->fire('executeAction', $event);
        if ($event->getResponse()) {
            $response = $event->getResponse();
        } else {
            $response = $this->invokeController();
        }
        $this->prepareView($response);
    }

    /**
     *
     * @return AbstractResponse
     * @throws ResponseNotFoundException
     */
    private function getResponse()
    {
        /**
         * @var AbstractResponse $response
         */
        $response=$this->classLoader->get('response.'.$this->responseName);
        return $response;
    }

    /**
     * @return AbstractResponse
     * @throws ActionNotFoundException
     */
    private function invokeController()
    {
        $eventManager = $this->container->getEventManager();

        $controller = new $this->className($this->request, $this->container);

        if (!is_callable([
            $controller,
            $this->methodName
        ])
        ) {
            throw new ActionNotFoundException($this->className, $this->methodName);
        }
        $response = null;
        $controllerData = call_user_func_array([
            $controller,
            $this->methodName
        ], $this->request->getArguments());
        if ($controllerData instanceof AbstractResponse) { //TODO move to event?
            $response = $controllerData;
        } else {
            $response = $this->getResponse();
            $response->setContent($controllerData);
        }

        $event = new ExecutedActionEvent($this->request, $response);
        $eventManager->fire('executedAction', $event);

        return $response;
    }

    /**
     * Render view
     *
     * @param AbstractResponse $response
     */
    protected function prepareView(AbstractResponse $response)
    {
        $eventManager = $this->container->getEventManager();
        $event = new ExecuteRenderEvent($this->request, $response);
        $eventManager->fire('executeRender', $event);

        $response->render();
    }


}