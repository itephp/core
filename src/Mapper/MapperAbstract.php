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

namespace ItePHP\Mapper;

use ItePHP\Core\Container;
/**
 * Base class for mapper. Cast value to another value
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
abstract class MapperAbstract{

	/**
	 * ExecuteResources
	 *
	 * @var Container $container
	 */
	protected $container;
	
	/**
	 * Constructor.
	 *
	 * @param Container $container
	 */
	final public function __construct(Container $container){
		$this->container=$container;
	}

	/**
	 * Cast value to another value.
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	abstract public function cast($value);

}