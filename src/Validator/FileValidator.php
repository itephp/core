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
 * Validator for file
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class FileValidator extends ValidatorAbstract{
	
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

		if(!$value){
			return 'File not uploaded.';
		}

		if($value->isError()){
			return $value->getError();
		}

		try{
			$accept=$this->getOption('accept');
			if(!preg_match('/'.str_replace(array('*','/'),array('.+','\\/'),$accept).'/' ,$value->getExtension())){
				return 'Invalid file type.';
			}
		}
		catch(ValueNotFoundException $e){
			//ignore
		}

		try{
			$maxSize=$this->getOption('maxSize');
			if($value->getSize()>$maxSize){
				return 'File is too large.';
			}
		}
		catch(ValueNotFoundException $e){
			//ignore
		}
	}
}
