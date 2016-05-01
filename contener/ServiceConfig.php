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

namespace ItePHP\Core\Contener;

/**
 * Contener with service config.
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
class ServiceConfig{
	
	/**
	 * Config data.
	 *
	 * @var array $class
	 */
	private $config;

	/**
	 * Constructor.
	 * @param array $config
	 * @since 0.1.0
	 */
	public function __construct($config){
		$this->config=$config;
	}

	/**
	 * Get config value.
	 *
	 * @param string $key
	 * @return mixed
	 * @since 0.1.0
	 */
	public function get($key){
		return $this->config[$key];
	}
}