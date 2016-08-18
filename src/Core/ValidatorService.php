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
 * Service to validate
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class ValidatorService{
	
	/**
	 *
	 * @param string $validator
	 * @param mixed $value
	 */
	public function validate($validator,$value){
		$validator=new $validator();
		return $validator->validate($value);
	}

	/**
	 *
	 * @param array $validators - array with value and validators: 
	 * [
	 * 'field name'=>[
	 *		'validator rule class'
	 *		,'value'
	 *		]
	 *	,'another field name'=>[
	 *		'validator rule class'
	 *		,'value'
	 *		]
	 *	]
	 * @return array with errors. If success then empty array.
	 */
	public function multiValidate($validators){
		$errors=[];
		foreach($validators as $kValidate=>$validate){
			$error=$this->validate($validate[0],$validate[1]);
			if($error)
				$errors[]=['field'=>$kValidate,'message'=>$error];
		}

		return $errors;
	}

	/**
	 * Validate data from storage array.
	 *
	 * @param array $validators - array with rules validation example: ['nameField'=>'Validator\ExampleClassValidator']
	 * @param array $storage - array with values, example ['nameField1'=>'value1','nameField2'=>'value2']
	 * @return array with errors. If success then empty array.
	 */
	public function storageValidate($validators,$storage){
		$errors=[];
		foreach($validators as $kValidate=>$validate){
			if(!isset($storage[$kValidate])){
				$errors[]=['field'=>$kValidate,'message'=>'Value '.$kValidate.' not found.'];
				continue;
			}

			$error=$this->validate($validate,$storage[$kValidate]);
			if($error)
				$errors[]=['field'=>$kValidate,'message'=>$error];
		}

		return $errors;

	}
}