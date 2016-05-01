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

namespace ItePHP\Core\Component\Form;

use ItePHP\Core\Component\Form\FormFormatter;
use ItePHP\Core\Component\Form\BasicFormFormatter;
use ItePHP\Core\Provider\Request;
use ItePHP\Core\Core\ValidatorService;
use ItePHP\Core\Validator\BooleanValidator;

/**
 * FormBuilder field
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.15.0
 */
class CheckboxField extends InputField{

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		$options+=array(
			'checked'=>false
		);

		$options['type']='checkbox';

		if(!isset($options['validator'])){
			$this->setValidator(new BooleanValidator());
		}

		parent::__construct($options);

	}

    /**
     * {@inheritdoc}
     */
	public function setData($value){
		$this->setChecked((boolean)$value);
	}

    /**
     * {@inheritdoc}
     */
	public function getData(){
		if($this->isChecked()){
			if($this->getValue())
				return $this->getValue();
			else
				return 'on';
		}
		else
			return false;
	}

	/**
	 * Get value of tag checked
	 *
	 * @return boolean
	 * @since 0.15.0
	 */
	public function isChecked(){
		return $this->getTag('checked');
	}

	/**
	 * Set value of tag checked
	 *
	 * @param boolean $flag - if true then checked else unchecked
	 * @since 0.15.0
	 */
	public function setChecked($flag){
		return $this->setTag('checked',$flag);
	}

}