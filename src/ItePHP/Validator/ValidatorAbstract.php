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

namespace ItePHP\Validator;

/**
 * Main class for validators. Check correct values.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
abstract class ValidatorAbstract{

	/**
	 * Array with config options.
	 *
	 * @var mixed[] $options
	 */
	private $options=[];
	
	/**
	 * Implement method to validate value.
	 *
	 * @param mixed $value - value to parse
	 * @return string - error message
	 */
	abstract public function validate($value);

	/**
	 * Set extra options.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	final public function setOption($name,$value){
		$this->options[$name]=$value;
	}

	/**
	 * Get extra option.
	 *
	 * @param string $name - name of option
	 * @return mixed - option
	 * @throws ValueNotFoundException
	 */
	protected function getOption($name){
		if(!isset($this->options[$name])){
			throw new ValueNotFoundException($name);
		}

		return $this->options[$name];
	}
}