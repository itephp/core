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

namespace ItePHP\Core\Test;

use ItePHP\Core\Test\BrowserEmulator;
use ItePHP\Core\Exception\InvalidQuerySelectorException;
use ItePHP\Core\Test\AnchorElement;
use ItePHP\Core\Test\FormElement;
use ItePHP\Core\Test\InputElement;
use ItePHP\Core\Test\TextareaElement;
use ItePHP\Core\Test\SelectElement;
use ItePHP\Core\Exception\ElementNotFoundException;

/**
 * Html Element for BrowserEmulator
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
class HTMLElement{
	protected $browser;
	protected $node;
	protected static $cache=array();

	public function __construct(BrowserEmulator $browser,$node){
		$this->browser=$browser;
		$this->node=$node;
	}

	public function getId(){
		return $this->getAttribute('id');
	}

	public function getClass(){
		return $this->getAttribute('class');
	}

	public function getAttribute($name){
		return $this->node->getAttribute($name);
	}

	public function getElement($query){
		$nodes=$this->findElements($query);
		if(count($nodes)==0){
			throw new ElementNotFoundException();
		}

		return $nodes[0];
	}

	public function findElements($query){

		//detect parts
		if(!preg_match('/(?:^| )(.*?)((\.|#)(.*?)){0,1}($| )/',$query, $match)){
			throw new InvalidQuerySelectorException($query);
		}

		$tag=$match[1];

		$className=null;
		$id=null;
		if($match[2]=='.'){
			$className=$match[3];
		}

		if($match[2]=='#'){
			$id=$match[3];
		}
		
		$nodes=array();
		foreach($this->node->getElementsByTagName('*') as $node){

			if($tag && $tag!=$node->tagName){
				continue;
			}
			if($className && !in_array($className,explode(' ',$node->getAttribute('class')))){
				continue;
			}

			if($id && $id!=$node->getAttribute('id')){
				continue;
			}

			$htmlElement=$this->createElement($node);


			$nodes[]=$htmlElement;

		}

		return $nodes;

	}

	public function getText(){
		return htmlspecialchars($this->getHtml());
	}

	public function getHtml() { 
		$innerHTML= '';
		$children = $this->node->childNodes; 
		foreach ($children as $child) { 
			$innerHTML.= $child->ownerDocument->saveXML( $child ); 
		} 

		return $innerHTML; 
	}

	public function getParent(){
		return $this->createElement($this->node->parentNode);
	}

	public function hasElement($query){
		try{

			$this->getElement($query);
			return true;
		}
		catch(ElementNotFoundException $e){
			return false;
		}
	}

	private function createElement($node){

		if(isset(static::$cache[spl_object_hash($node)])){
			return static::$cache[spl_object_hash($node)];
		}

		switch($node->tagName){
			case 'a':
				$htmlElement=new AnchorElement($this->browser,$node);
			break;
			case 'form':
				$htmlElement=new FormElement($this->browser,$node);
			break;
			case 'input':
				$htmlElement=new InputElement($this->browser,$node);
			break;
			case 'textarea':
				$htmlElement=new TextareaElement($this->browser,$node);
			break;
			case 'select':
				$htmlElement=new SelectElement($this->browser,$node);
			break;
			default:
				$htmlElement=new HTMLElement($this->browser,$node);
			break;
		}

		static::$cache[spl_object_hash($node)]=$htmlElement;

		return $htmlElement;		
	}
}