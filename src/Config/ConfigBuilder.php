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
 */
class ConfigBuilder{
	
	/**
	 *
	 * @var array
	 */
	private $nodes=[];

	/**
	 *
	 * @var array
	 */
	private $readers=[];

	/**
	 *
	 * @param Reader $reader
	 */
	public function addReader(Reader $reader){
		$this->readers[]=$reader;
	}

	/**
	 *
	 * @param ConfigBuilderNode $node
	 */
	public function addNode(ConfigBuilderNode $node){
		$this->nodes[$node->getName()]=$node;
	}

	/**
	 *
	 * @param string $name
	 * @return ConfigBuilderNode
	 * @throws ConfigException
	 */	
	public function getNode($name){
		if(!isset($this->nodes[$name])){
			throw new ConfigException('Node '.$name.' not found.');
		}
		return $this->nodes[$name];
	}

	/**
	 *
	 * @return ConfigContainer
	 */
	public function parse(){
		$nodes=[];

		foreach($this->readers as $reader){
			$nodes=$this->mergeNodes($nodes,$this->parseReader($reader));
		}

		return new ConfigContainer($nodes,[]);
	}

	/**
	 *
	 * @param array $originNode
	 * @param array $newNodes
	 * @return array
	 */
	private function mergeNodes($originNode,$newNodes){
		foreach($newNodes as $nodeName=>$node){
			if(!isset($originNode[$nodeName])){
				$originNode[$nodeName]=[];
			}

			$originNode[$nodeName]=array_merge($originNode[$nodeName],$node);
		}

		return $originNode;

	}

	/**
	 *
	 * @param Reader $reader
	 * @return array
	 */
	private function parseReader(Reader $reader){
		$nodes=[];
		foreach($this->nodes as $node){
			$nodes[$node->getName()]=$this->parseNodes($reader,$node);
		}

		return $nodes;
	}

	/**
	 *
	 * @param Reader $reader
	 * @param ConfigBuilderNode $node 
	 * @return array
	 */
	private function parseNodes(Reader $reader,ConfigBuilderNode $node){
		$nodes=[];
		$fileNodes=$reader->getNodes($node->getName());
		foreach($fileNodes as $fileNode){
			$nodes[]=$this->parseNode($fileNode,$node);
		}

		return $nodes;
	}

	/**
	 *
	 * @param ReaderNode $readerNode
	 * @param ConfigBuilderNode $node 
	 * @return ConfigContainerNode
	 */
	private function parseNode(ReaderNode $readerNode,ConfigBuilderNode $node){
		$arguments=[];
		$nodes=[];
		foreach($node->getAttributes() as $argument){
			$arguments[$argument->getName()]=$this->parseAttribute($readerNode,$argument);
		}

		foreach($node->getNodes() as $node){
			$nodes[$node->getName()]=$this->parseNodes($readerNode,$node);
		}

		return new ConfigContainer($nodes,$arguments);

	}

	/**
	 *
	 * @param ReaderNode $readerNode
	 * @param ConfigBuilderArgument $argument 
	 * @return string
	 */
	private function parseAttribute(ReaderNode $readerNode,ConfigBuilderArgument $argument){
		$value=null;
		try{
			$value=$readerNode->getAttribute($argument->getName());
		}
		catch(ConfigException $e){
			if($argument->isRequired()){
				throw $e;
			}
			$value=$argument->getDefault();
		}

		return $value;
	}

}