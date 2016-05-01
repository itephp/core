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

use ItePHP\Core\ExecuteResources;
use ItePHP\Provider\Response;
use ItePHP\Event\ExecuteActionEvent;
use ItePHP\Event\ExecutedActionEvent;
use ItePHP\Event\ExecutePresenterEvent;
use ItePHP\Core\EventManager;
use ItePHP\Exception\ActionNotFoundException;
use ItePHP\Provider\Session;
use ItePHP\Provider\Request;
use ItePHP\Contener\CommandConfig;

/**
 * Dispatcher for commands
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
class CommandDispatcher  implements Dispatcher {

	/**
	 * Request
	 *
	 * @var \ItePHP\Provider\Request $request
	 */
	private $request;

	/**
	 * ExecuteResources
	 *
	 * @var \ItePHP\Core\ExecuteResources $resources
	 */
	private $resources;

	/**
	 * CommandCOnfig
	 *
	 * @var \ItePHP\Contener\CommandConfig $config
	 */
	private $config;

	/**
	 * Constructor.
	 *
	 * @param \ItePHP\Contener\CommandConfig $config
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
	 * @param \ItePHP\Core\ExecuteResources $resource
	 * @param \ItePHP\Core\EventManager $eventManager
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