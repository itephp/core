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

namespace ItePHP\Core\Parser;

/**
 * Xml file parser.
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
class XML{
	
	/**
	 * Constructor.
	 *
	 * @param string $data
	 * @param string $type
	 * @since 0.1.0
	 */
	public function __construct($data,$type='FILE'){
		if($type=='FILE')
			$this->data=new \SimpleXMLElement(file_get_contents($data));
		else
			$this->data=new \SimpleXMLElement($data);
	}

	/**
	 * Get data.
	 *
	 * @return mixed
	 * @since 0.1.0
	 */
	public function get(){
		return $this->data;
	}

	/**
	 * Get child data.
	 *
	 * @param string $name
	 * @return mixed
	 * @since 0.1.0
	 */
	public function getChild($name){
		$data=$this->data->children();
		return $data[0];
	}
}