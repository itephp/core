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
 */
class Column{

	/**
	 * List of id.
	 *
	 * @var string[] $keys
	 */
	private $keys;

	/**
	 * Column label/name.
	 *
	 * @var string $label
	 */
	private $label;

	/**
	 * Class with pattern to generate html.
	 *
	 * @var ColumnFormatter $formatter
	 */
	private $formatter;

	/**
	 * List of sort id.
	 *
	 * @var mixed[] $sortKeys
	 */
	private $sortKeys;

	/**
	 * Constructor
	 *
	 * @param mixed $keys list of id for column or one element
	 * @param string $label
	 * @param ColumnFormatter $formatter class with template to format column
	 * @param mixed $sortKeys sort by list of id
	 */
	public function __construct($keys,$label,ColumnFormatter $formatter=null,$sortKeys=null){
		if(!is_array($keys)){
			$keys=[$keys];
		}

		$this->keys=$keys;

		$this->label=$label;

		if(!$formatter){
			$formatter=new BasicColumnFormatter();
		}

		$this->setFormatter($formatter);
		if($sortKeys===null){
			$sortKeys=$keys;
		}
		$this->setSortBy($sortKeys);
	}

	/**
	 * Set formatter class
	 *
	 * @param ColumnFormatter $formatter class with template to format column
	 */
	public function setFormatter(ColumnFormatter $formatter){
		$this->formatter=$formatter;
	}

	/**
	 * Set sortable column by Keys
	 *
	 * @param mixed $sortKeys sort by list of id
	 */
	public function setSortBy($sortKeys){

		if($sortKeys==null){
			$sortKeys=[];
		}

		if(!is_array($sortKeys)){
			$sortKeys=[$sortKeys];
		}
		$this->sortKeys=$sortKeys;
	}

	/**
	 * 
	 * @return mixed[]
	 */
	public function getSortKeys(){
		return $this->sortKeys;
	}

	/**
	 * Check sortable column
	 *
	 * @return bool
	 */
	public function isSortable(){
		return !empty($this->sortKeys);
	}

	/**
	 * Get label of column
	 *
	 * @return string
	 */
	public function getLabel(){
		return $this->label;
	}

	/**
	 * Get column formatter
	 *
	 * @return ColumnFormatter
	 */
	public function getFormatter(){
		return $this->formatter;
	}

	/**
	 * Get column keys
	 *
	 * @return string[]
	 */
	public function getKeys(){
		return $this->keys;
	}

}