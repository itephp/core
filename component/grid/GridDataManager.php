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
 * Manager to loading records from source.
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.17.0
 */
interface GridDataManager{

	/**
	 * Get records for single page
	 *
	 * @param int $limit - count record on page
	 * @param int $page - current page
	 * @param string $sort - sort by column
	 * @return array - records
	 * @since 0.17.0
	 */
	public function getRecords($limit,$page,$sort=null);

	/**
	 * Get total count records
	 *
	 * @return int - total count records
	 * @since 0.17.0
	 */
	public function getTotalCount();
	
}