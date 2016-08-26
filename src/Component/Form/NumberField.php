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

namespace ItePHP\Component\Form;

use ItePHP\Validator\NumberValidator;

/**
 * FormBuilder field
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class NumberField extends InputField{

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		$options['type']='number';

		if(!isset($options['validator'])){
			$this->setValidator(new NumberValidator());
		}

		if(isset($options['min'])){
			$this->setMin($options['min']);
			unset($options['min']);
		}

		if(isset($options['max'])){
			$this->setMax($options['max']);
			unset($options['max']);
		}

		parent::__construct($options);
	}

	/**
	 * Set html tag min
	 *
	 * @param int $value - value of tag min
	 */
	public function setMin($value){
		$this->setTag('min',$value);
		if($this->getValidator()){
			$this->getValidator()->setOption('min',$value);
		}
	}

	/**
	 * Get html tag min
	 *
	 * @return int - value of tag min
	 */
	public function getMin(){
		return $this->getTag('min');
	}

	/**
	 * Set html tag max
	 *
	 * @param int $value - value of tag max
	 */
	public function setMax($value){
		$this->setTag('max',$value);
		if($this->getValidator()){
			$this->getValidator()->setOption('max',$value);
		}
	}

	/**
	 * Get html tag max
	 *
	 * @return int - value of tag max
	 */
	public function getMax(){
		return $this->getTag('max');
	}

}