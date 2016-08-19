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

use ItePHP\Component\Form\FormFormatter;
use ItePHP\Component\Form\BasicFormFormatter;
use ItePHP\Core\ValidatorService;
use ItePHP\Validator\EmailValidator;

/**
 * FormBuilder field
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.17.0
 */
class EmailField extends InputField{

    /**
     * {@inheritdoc}
     */
	public function __construct($options){
		$options['type']='email';

		if(!isset($options['validator'])){
			$this->setValidator(new EmailValidator());
		}

		parent::__construct($options);

	}

}