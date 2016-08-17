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
 */
class MetadataMethod{

	/**
	 *
	 * @var string
	 */
	const PRIMITIVE_TYPE='primitive';

	/**
	 *
	 * @var string
	 */
	const STATIC_TYPE='static';

	/**
	 *
	 * @var string
	 */
	const REFERENCE_TYPE='reference';
	
	/**
	 *
	 * @var string
	 */
	private $name;

	/**
	 *
	 * @var array
	 */
	private $arguments=[];

	/**
	 *
	 * @param string $methodName
	 */
	public function __construct($methodName){
		$this->name=$methodName;
	}

	/**
	 *
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 *
	 * @return string $type
	 * @return mixed $value
	 */
	public function addArgument($type,$value){
		$this->arguments[]=['type'=>$type,'value'=>$value];
	}

	/**
	 *
	 * @return array
	 */
	public function getArguments(){
		return $this->arguments;
	}

}