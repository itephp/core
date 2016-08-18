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
 * Validator for reg exp
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class RegExpValidator extends ValidatorAbstract{
	
	/**
	 * {@inheritdoc}
	 */
	public function validate($value){
		$pattern=$this->getOption('pattern');
		if(!preg_match('/'.$pattern.'/',$value)){
			return 'Invalid pattern format.';
		}
	}
}
