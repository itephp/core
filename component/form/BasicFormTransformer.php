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

use ItePHP\Core\Component\Form\Transformer;

/**
 * Transformer for FormBuilder
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.22.0
 */
class BasicFormTransformer implements Transformer{

    /**
     * {@inheritdoc}
     */
	public function decode($data){
		return $data;
	}

    /**
     * {@inheritdoc}
     */
	public function encode($data){
		return $data;
	}

}