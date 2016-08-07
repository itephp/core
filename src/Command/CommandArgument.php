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

namespace ItePHP\Command;

/**
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.4.0
 */
class CommandArgument{

	/**
	 *
	 * @var string
	 */
	private $name;

	/**
	 *
	 * @var int
	 */
	private $length;

	/**
	 *
	 * @var mixed
	 */
	private $default;

	/**
	 *
	 * @param string $name
	 * @param int $length
	 */
	public function __construct($name,$length){
		$this->name=$name;
		$this->length=$length;
		$this->required=true;
	}

	/**
	 *
	 * @param mixed $default
	 */
	public function setDefault($default){
		$this->default=$default;
		$this->required=false;
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
	 * @return int
	 */
	public function getLength(){
		return $this->length;
	}

	/**
	 *
	 * @return mixed
	 */
	public function getDefault(){
		return $this->default;
	}

	/**
	 *
	 * @return boolean
	 */
	public function isRequired(){
		return $this->required;
	}

}