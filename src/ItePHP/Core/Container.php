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

use ItePHP\DependencyInjection\DependencyInjection;
use ItePHP\DependencyInjection\InstanceNotFoundException;


/**
 * Container for snippets and services.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class Container{

	/**
	 *
	 * @var DependencyInjection
	 */
	private $dependencyInjection;

    /**
     * Constructor.
     *
     * @param DependencyInjection $dependencyInjection
     */
	public function __construct(DependencyInjection $dependencyInjection){

		$this->dependencyInjection=$dependencyInjection;
	}

	/**
	 * Get Environment
	 *
	 * @return Environment
	 */
	public function getEnvironment(){
		return $this->dependencyInjection->get('environment');
	}

	/**
	 * Get Event manager
	 *
	 * @return EventManager
	 */
	public function getEventManager(){
		return $this->dependencyInjection->get('eventManager');
	}

    /**
     *
     * @param string $name service name
     * @return object
     * @throws ServiceNotFoundException
     */
	public function getService($name){
		try{
			return $this->dependencyInjection->get('service.'.$name);
		}
		catch(InstanceNotFoundException $e){
			throw new ServiceNotFoundException($name);
		}
	}

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->dependencyInjection->get('config');
    }
}