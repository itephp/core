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
 * Xml file reader
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.4.0
 */
class XmlFileReader implements Reader{
	
	/**
	 *
	 * @var SimpleXMLElement
	 */ 
	private $data;

	/**
	 *
	 * @param string $file
	 */ 
	public function __construct($file){
		$this->data=new \SimpleXMLElement(file_get_contents($file));


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

			$nodes[]=new XmlFileNode($node);
		}

		return $nodes;
	}
}