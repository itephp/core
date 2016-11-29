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

namespace Test\Asset;

use ItePHP\Core\SessionProvider;
use ItePHP\Action\ValueNotFoundException;
use ItePHP\Core\Environment;

/**
 * Provider for session.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
class SessionTest implements SessionProvider{

	private $id;
	private $data;

	public function __construct(){
		$this->id=mt_rand(1,10000000);
	}

	public function getId(){
		return $this->id;
	}

	public function get($key){
		if(!isset($this->data[$key])){
			throw new ValueNotFoundException($key);			
		}
		return $this->data[$key];
	}

	public function set($key,$value){
		$this->data[$key]=$value;
	}

	public function remove($key){
		if(isset($this->data[$key])){
			unset($this->data[$key]);			
		}
		else{
			throw new ValueNotFoundException($key);
		}

	}

	public function clear(){
		$this->data=[];
	}

}