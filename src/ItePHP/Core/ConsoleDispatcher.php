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

use Config\Config\Command;
use ItePHP\Command\CommandInterface;

use ItePHP\Command\OutputStreamConsole;
use ItePHP\Command\CommandExecutor;
use Onus\ClassLoader;
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
	 * @var ClassLoader
	 */
	protected $classLoader;

	/**
	 *
	 * @var Command
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
     * @param Command $config
     * @param ClassLoader $classLoader
     * @param mixed[] $arguments
     */
	public function __construct(Command $config, ClassLoader $classLoader, $arguments){
		$this->config=$config;
		$this->name=$config->getName();
		$this->classLoader=$classLoader;
		$this->arguments=$arguments;
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute(){
        /**
         * @var CommandInterface $command
         */
		$command=$this->classLoader->get('command.'.$this->name);
		$commandExecutor=new CommandExecutor($command);
		$commandExecutor->setOutputStream(new OutputStreamConsole());
		$commandExecutor->setArguments($this->arguments);
		$commandExecutor->run();

	}


}