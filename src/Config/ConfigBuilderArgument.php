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

namespace ItePHP\Config;

/**
 * Config Argument.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.4.0
 */
class ConfigBuilderArgument{

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var boolean
	 */
	private $required;

	/**
	 * @var string
	 */
	private $default;
	
	/**
	 *
	 * @param string $name
	 * @param boolean $required
	 * @param string $default
	 */
	public function __construct($name,$required,$default){
		$this->name=$name;
		$this->required=$required;
		$this->default=$default;
	}

	/**
	 *
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 *
	 * @return boolean
	 */
	public function isRequired(){
		return $this->required;
	}

	/**
	 *
	 * @return string
	 */
	public function getDefault(){
		return $this->default;
	}

}