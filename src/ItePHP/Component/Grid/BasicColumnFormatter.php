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

use ItePHP\Component\Grid\ColumnFormatter;

/**
 * Formatter for GridBuilder
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.17.0
 */
class BasicColumnFormatter implements ColumnFormatter{

	/**
	 * {@inheritdoc}
	 */
	public function renderRecord($data){
		return htmlspecialchars(trim(implode(' ',$data)));
	}

    /**
     * Method generated html for column
     *
     * @param string $name label name
     * @since 0.17.0
     * @return string
     */
    public function renderLabel($name)
    {
        return $name;
    }
}