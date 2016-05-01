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

namespace ItePHP\Core\Component\Grid;

use ItePHP\Core\Component\Grid\ColumnFormatter;

/**
 * Formatter for GridBuilder
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.17.0
 */
class BasicColumnFormatter implements ColumnFormatter{

	/**
	 * {@inheritdoc}
	 */
	public function render($data){
		return htmlspecialchars(trim(implode(' ',$data)));
	}

}