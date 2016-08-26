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
 * Interface for grid formatter. Definition how generate view for GridBuilder.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
interface GridFormatter{

	/**
	 * Method generated html grid
	 *
	 * @param Column[] $columns - columns name
	 * @param mixed[] $records - records list
	 * @param int $totalCount - max records count
	 * @param int $limit - count records on page
	 * @param int $page - current pag number
	 * @param int $sort index column to sort
	 */
	public function render($columns,$records,$totalCount,$limit,$page,$sort);

}