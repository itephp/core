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

use ItePHP\Command\CommandInterface;

use ItePHP\DependencyInjection\DependencyInjection;
use ItePHP\Command\OutputStreamConsole;
use ItePHP\Command\CommandExecutor;
use Pactum\ConfigContainer;
use Via\Dispatcher;

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
	protected $name;

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
	 * @var mixed[]
	 */
	protected $arguments;

	/**
	 * Constructor.
	 *
	 * @param ConfigContainer $config
	 * @param DependencyInjection $dependencyInjection
	 * @param mixed[] $arguments
	 */
	public function __construct(ConfigContainer $config,DependencyInjection $dependencyInjection,$arguments){
		$this->config=$config;
		$this->name=$config->getValue('name');
		$this->dependencyInjection=$dependencyInjection;
		$this->arguments=$arguments;
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute(){
        /**
         * @var CommandInterface $command
         */
		$command=$this->dependencyInjection->get('command.'.$this->name);
		$commandExecutor=new CommandExecutor($command);
		$commandExecutor->setOutputStream(new OutputStreamConsole());
		$commandExecutor->setArguments($this->arguments);
		$commandExecutor->run();

	}


}