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

namespace ItePHP\Config;

/**
 * Xml reader.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.4.0
 */
class XmlFileNode implements FileReaderNode{
	
	private $data;

	public function __construct($node){
		$this->data=$node;
	}

	public function getNodes($name){
		$nodes=[];
		foreach($this->data->children() as $kNode=>$node){
			if($kNode!=$name){
				continue;
			}

			$nodes[]=new XmlFileNode($node);
		}

		return $nodes;
	}

	public function getAttribute($name){
		$attributes=$this->data->attributes();
		if(!isset($attributes[$name])){
			throw new ConfigException('Argument '.$name.' not found.');
		}
		return $attributes[$name];
	}
}