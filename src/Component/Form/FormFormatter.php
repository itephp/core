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

use ItePHP\Component\Form\FormField;

/**
 * Interface for form formmater. Definition how generate view for FormBuilder.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.15.0
 */
interface FormFormatter{

	/**
	 * Method generated html for field
	 *
	 * @param \ItePHP\Common\FormField $field - FormField object 
	 * @since 0.15.0
	 */
	public function renderField(FormField $field);

	/**
	 * Method generated html for form open html element
	 *
	 * @param array $tags - tag list
	 * @since 0.13.0
	 */
	public function renderFormBegin($tags);

	/**
	 * Method generated html for form close html element
	 *
	 * @since 0.13.0
	 */
	public function renderFormEnd();

	/**
	 * Method generated html for form submit button
	 *
	 * @param array $tags - tag list
	 * @since 0.13.0
	 */
	public function renderSubmit($tags);

}