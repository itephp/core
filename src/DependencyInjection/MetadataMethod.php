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

namespace ItePHP\DependencyInjection;

/**
 * Metadata method container
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.2.0
 */
class MetadataMethod{

	const PRIMITIVE_TYPE='primitive';
	const STATIC_TYPE='static';
	const REFERENCE_TYPE='reference';
	
	private $name;
	private $arguments=[];

	public function __construct($methodName){
		$this->name=$methodName;
	}

	public function getName(){
		return $this->name;
	}

	public function addArgument($type,$value){
		$this->arguments[]=['type'=>$type,'value'=>$value];
	}

	public function getArguments(){
		return $this->arguments;
	}

}