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
 * Validator for date
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.18.0
 */
class DateValidator extends Validator{
	
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

		if(!$value && $empty){
			return;
		}

		$d = \DateTime::createFromFormat('Y-m-d', $value);
	    if($d && $d->format('Y-m-d') == $value);
	    else
			return 'Invalid date format.';
	}

}