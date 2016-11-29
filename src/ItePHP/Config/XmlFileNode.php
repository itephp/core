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

use \SimpleXMLElement;
/**
 * Xml reader.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class XmlFileNode implements ReaderNode{
	
	/**
	 *
	 * @var SimpleXMLElement
	 */ 
	private $data;

    /**
     * @var string[]
     */
    private $path=[];

    /**
	 *
	 * @param string[] $path
	 * @param SimpleXMLElement $node
	 */
	public function __construct($path,SimpleXMLElement $node){
		$this->path=$path;
		$this->data=$node;
	}

    /**
     * {@inheritdoc}
     */
	public function getNodes($name){
		$nodes=[];
		foreach($this->data->children() as $kNode=>$node){
			if($kNode!=$name){
				continue;
			}

			$nodes[]=new XmlFileNode($this->path+[$kNode],$node);
		}

		return $nodes;
	}

    /**
     * {@inheritdoc}
     */
	public function getAttribute($name){
		$attributes=$this->data->attributes();
		if(!isset($attributes[$name])){
			throw new ConfigException('Argument '.$name.' not found ('.implode('->',$this->path).').');
		}
		return (string)$attributes[$name];
	}
}