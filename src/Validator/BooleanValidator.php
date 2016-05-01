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

namespace ItePHP\Core\Validator;
use ItePHP\Core\Core\Validator;
use ItePHP\Core\Exception\ValueNotFoundException;

/**
 * Validator for number
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.18.0
 */
class BooleanValidator extends Validator{
	
	/**
	 * {@inheritdoc}
	 */
	public function validate($value){
		$empty=false;

		try{
			$empty=$this->getOption('empty');
		}
		catch(ValueNotFoundException $e){
			//ignore
		}

		if((!$value || $value=='false') && $empty){
			return;
		}

		if(!in_array($value, array('true','on',true))){
			return "Invalid value.";
		}
	}
}