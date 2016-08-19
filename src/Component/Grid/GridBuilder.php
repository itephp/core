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

use ItePHP\Component\Grid\GridFormatter;
use ItePHP\Component\Grid\GridDataManager;
use ItePHP\Component\Grid\Column;
use ItePHP\Core\Request;

/**
 * Generator grid. Support for mapping data, pagination and generate html code.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.17.0
 */
class GridBuilder{
	private $formatter;
	private $dataManager;
	private $limit=10;
	private $page=1;
	private $columns=array();

	/**
	 * @var Request $request
	 */
	private $request;

	/**
	 * Constructor
	 *
	 * @param Request $request
	 * @since 0.20.0
	 */
	public function __construct(Request $request){
		$this->request=$request;
	}

	/**
	 * Set formatter with html rule pattern
	 *
	 * @param \ItePHP\Component\Grid\GridFormatter $formatter
	 * @since 0.17.0
	 */
	public function setFormatter(GridFormatter $formatter){
		$this->formatter=$formatter;
	}

	/**
	 * Set formatter with html rule pattern
	 *
	 * @param \ItePHP\Component\Grid\GridDataManager $dataManager
	 * @since 0.17.0
	 */
	public function setDataManager(GridDataManager $dataManager){
		$this->dataManager=$dataManager;
	}

	/**
	 * Get DataManager
	 *
	 * @return \ItePHP\Component\Grid\GridDataManager
	 * @since 0.18.0
	 */
	public function getDataManager(){
		return $this->dataManager;
	}

	/**
	 * Get columns data
	 *
	 * @return array
	 * @since 0.18.0
	 */
	public function getColumns(){
		return $this->columns;
	}

	/**
	 * Get records
	 *
	 * @return array
	 * @since 0.18.0
	 */
	public function getRecords(){
		$query=$this->request->getQuery();
		$sort=null;
		if(isset($query['sort']) && isset($this->columns[$query['sort']])){
			$sort=$this->columns[$query['sort']]->getSortKeys();
		}
		if (isset($query['page'])) {
			$this->page=$query['page'];
		}


		return $this->dataManager->getRecords($this->limit,$this->page,$sort);
	}

	/**
	 * Get total count records
	 *
	 * @return int
	 * @since 0.18.0
	 */
	public function getTotalCount(){
		return $this->dataManager->getTotalCount();
	}

	/**
	 * Set limit records on single page
	 *
	 * @param int $limit - items on page
	 * @since 0.17.0
	 */
	public function setLimit($limit){
		$this->limit=$limit;
	}

	/**
	 * Set current page
	 *
	 * @param int $page - current page
	 * @since 0.17.0
	 */
	public function setPage($page){
		$this->page=$page;
	}

	/**
	 * Add column
	 *
	 * @param Column $column
	 * @since 0.17.0
	 */
	public function addColumn(Column $column){
		$this->columns[]=$column;
	}

	/**
	 * Generate html grid string
	 *
	 * @return string with html form
	 * @since 0.17.0
	 */
	public function render(){
		$sort=0;
		$query=$this->request->getQuery();
		if(isset($query['sort']) && isset($this->columns[$query['sort']])){
			$sort=$query['sort'];
		}

		return $this->formatter->render($this->columns
			,$this->getRecords()
			,$this->dataManager->getTotalCount(),$this->limit,$this->page,$sort);
	}


	/**
	 * @since 0.17.0
	 */
	public function __toString(){
		return $this->render();
	}

}
