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

use ItePHP\Route\Dispatcher;

use ItePHP\DependencyInjection\DependencyInjection;

use ItePHP\Core\Config;

use ItePHP\Command\OutputStreamConsole;
use ItePHP\Command\CommandExecutor;

/**
 * Dispatcher for console
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class ConsoleDispatcher  implements Dispatcher {

	/**
	 *
	 * @var string
	 */
	protected $className;

	/**
	 *
	 * @var DependencyInjection
	 */
	protected $dependencyInjection;

	/**
	 *
	 * @var Config
	 */
	protected $config;

	/**
	 *
	 * @var array
	 */
	protected $arguments;

	/**
	 * Constructor.
	 *
	 * @param Config $config
	 * @param DependencyInjection $dependencyInjection
	 * @param array $arguments
	 */
	public function __construct(Config $config,DependencyInjection $dependencyInjection,$arguments){
		$this->config=$config;
		$this->className=$config->getAttribute('class');
		$this->dependencyInjection=$dependencyInjection;
		$this->arguments=$arguments;
	}


	/**
	 * {@inheritDoc}
	 */
	public function execute(){
		$command=$this->dependencyInjection->get('command.'.$this->className);

		$commandExecutor=new CommandExecutor($command);
		$commandExecutor->setOutputStream(new OutputStreamConsole());
		$commandExecutor->setArguments($this->arguments);
		$commandExecutor->run();

	}


}