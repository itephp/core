<?php

/**
 * ItePHP: Freamwork PHP (http://php.iteracja.com)
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the file LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) NewClass (http://newclass.pl)
 * @link          http://php.iteracja.com ItePHP Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace ItePHP\Core\Core;

use ItePHP\Core\Core\ExecuteResources;
use ItePHP\Core\Provider\Response;
use ItePHP\Core\Event\ExecuteActionEvent;
use ItePHP\Core\Event\ExecutedActionEvent;
use ItePHP\Core\Event\ExecutePresenterEvent;
use ItePHP\Core\Core\EventManager;
use ItePHP\Core\Exception\ActionNotFoundException;
use ItePHP\Core\Provider\Session;
use ItePHP\Core\Provider\Request;
use ItePHP\Core\Contener\CommandConfig;

/**
 * Dispatcher for commands
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
class CommandDispatcher  implements Dispatcher {

	/**
	 * Request
	 *
	 * @var \ItePHP\Core\Provider\Request $request
	 */
	private $request;

	/**
	 * ExecuteResources
	 *
	 * @var \ItePHP\Core\Core\ExecuteResources $resources
	 */
	private $resources;

	/**
	 * CommandCOnfig
	 *
	 * @var \ItePHP\Core\Contener\CommandConfig $config
	 */
	private $config;

	/**
	 * Constructor.
	 *
	 * @param \ItePHP\Core\Contener\CommandConfig $config
	 * @param array $arguments command arguments
	 * @since 0.1.0
	 */
	public function __construct(CommandConfig $config,$arguments){
		$this->config=$config;
		$this->arguments=$arguments;
	}

	/**
	 * Execute command
	 *
	 * @param \ItePHP\Core\Core\ExecuteResources $resource
	 * @param \ItePHP\Core\Core\EventManager $eventManager
	 * @since 0.1.0
	 */
	public function execute(ExecuteResources $resources,EventManager $eventManager){
		$commandName=$this->config->getClass();
		$command=new $commandName($resources,$eventManager);
	
		if(!is_callable(array($command,$this->config->getMethod()))){
			throw new CommandNotFoundException($commandName,$this->config->getMethod());
		}

		call_user_func_array(array($command, $this->config->getMethod()), $this->arguments);
	}

}