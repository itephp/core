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

/**
 * Interface for validtor service.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
interface ValidatorService{

	/**
	 * Validate once value.
	 *
	 * @param Validator $validator class validator rule
	 * @param mixed $value valut to validation
	 * @return string
	 */
	public function validate($validator,$value);

	/**
	 * Validate multiple values
	 * @param array $validators - array with value and validators: 
	 * array(
	 * 'field name'=>array(
	 *		'validator rule class'
	 *		,'value'
	 *	)
	 *	,'another field name'=>array(
	 *		'validator rule class'
	 *		,'value')
	 *	)
	 * @return array
	 */
	public function multiValidate($validators);

	/**
	 * Validate data from storage array.
	 *
	 * @param array $validators array with rules validation example: array('nameField'=>'Validator\ExampleClassValidator')
	 * @param array $storage array with values, example array('nameField1'=>'value1','nameField2'=>'value2')
	 * @return array
	 */
	public function storageValidate($validators,$storage);

}

