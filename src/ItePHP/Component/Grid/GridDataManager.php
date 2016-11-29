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
 * Manager to loading records from source.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
interface GridDataManager{

	/**
	 * Get records for single page
	 *
	 * @param int $limit - count record on page
	 * @param int $page - current page
	 * @param string $sort - sort by column
	 * @return mixed[] - records
	 */
	public function getRecords($limit,$page,$sort=null);

	/**
	 * Get total count records
	 *
	 * @return int - total count records
	 */
	public function getTotalCount();
	
}