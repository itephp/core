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

/**
 * Interface for grid formmater. Definition how generate view for GridBuilder.
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.17.0
 */
interface GridFormatter{

	/**
	 * Method generated html grid
	 *
	 * @param array $columns - columns name
	 * @param array $records - records list
	 * @param int $totalCount - max records count
	 * @param int $limit - count records on page
	 * @param int $page - current pag number
	 * @param int $sort index column to sort
	 * @since 0.17.0
	 */
	public function render($columns,$records,$totalCount,$limit,$page,$sort);

}