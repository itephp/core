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

namespace ItePHP\Test;

use ItePHP\Test\FormFieldElement;

/**
 * Html Element for BrowserEmulator
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class SelectElement extends FormFieldElement{
	private $data;
	
	public function getData(){
		if($this->data!==null){
			return $this->data;
		}

		foreach($this->findElements('option') as $option){
			if($option->getAttribute('selected')!=''){
				return $option->getAttribute('value');
			}
		}

		return null;
	}

	public function setData($data){
		$this->data=$data;
		return $this;
	}
}