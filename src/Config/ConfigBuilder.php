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
 * Config builder.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.4.0
 */
class ConfigBuilder{
	
	private $nodes=[];

	private $fileReader;

	public function __construct(FileReader $fileReader){
		$this->fileReader=$fileReader;
	}

	public function addNode(ConfigBuilderNode $node){
		$this->nodes[]=$node;
	}

	public function parse(){
		//TODO parse
		$nodes=[];
		foreach($this->nodes as $node){
			$nodes[$node->getName()]=$this->parseNodes($this->fileReader,$node);
		}

		return new ConfigContainer($nodes);
	}

	private function parseNodes($fileReader,ConfigBuilderNode $node){
		$nodes=[];
		$fileNodes=$fileReader->getNodes($node->getName());
		foreach($fileNodes as $fileNode){
			$nodes[]=$this->parseNode($fileNode,$node);
		}

		return $nodes;
	}

	private function parseNode($fileNode,ConfigBuilderNode $node){
		$arguments=[];
		$nodes=[];
		foreach($node->getAttributes() as $argument){
			$arguments[$argument->getName()]=$this->parseAttribute($fileNode,$argument);
		}

		foreach($node->getNodes() as $node){
			$nodes[$node->getName()]=$this->parseNodes($fileNode,$node);
		}

		return new ConfigContainerNode($nodes,$arguments);

	}

	private function parseAttribute($fileNode,ConfigBuilderArgument $argument){
		$value=null;
		try{
			$value=$fileNode->getAttribute($argument->getName());
		}
		catch(ConfigException $e){
			if($argument->isRequired()){
				throw new $e;
			}
			$value=$argument->getDefault();
		}

		return $value;
	}

}