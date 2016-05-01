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

namespace ItePHP\Component\Grid;

/**
 * Interface of column formatter (cell in grid).
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.17.0
 */
interface ColumnFormatter{

	/**
	 * Method generated html for column
	 *
	 * @param mixed $data - field from record
	 * @since 0.17.0
	 */
	public function render($data);

}