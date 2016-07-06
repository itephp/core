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
 * Metadata class container
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.3.0
 */
class MetadataClass{
	
	private $name;
	private $className;
	private $methods=[];

	public function __construct($name,$className){
		$this->name=$name;
		$this->className=$className;
	}

	public function getName(){
		return $this->name;
	}

	public function getClassName(){
		return $this->className;
	}

	public function registerInvoke(MetadataMethod $method){
		$this->methods[]=$method;
	}

	public function getMethods(){
		return $this->methods;
	}
}