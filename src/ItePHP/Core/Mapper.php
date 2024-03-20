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

use ItePHP\Exception\ServiceNotFoundException;
use ItePHP\Core\Container;
/**
 * Base class for mapper. Cast value to another value
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
abstract class Mapper{

	/**
	 * ExecuteResources
	 *
	 * @var \ItePHP\Core\Container $container
	 */
	private $container;
	
	/**
	 * Constructor.
	 *
	 * @param \ItePHP\Core\Container $container
	 * @since 0.1.0
	 */
	final public function __construct(Container $container){
		$this->container=$container;
	}

	/**
	 * Get service.
	 *
	 * @param string $name
	 * @return object service object
	 * @since 0.1.0
	 */
	final public function getService($name){
		return $this->container->getService($name);
	}

	/**
	 * Cast value to another value.
	 *
	 * @param mixed $value
	 * @return mixed
	 * @since 0.1.0
	 */
	abstract public function cast($value);
}