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

namespace ItePHP\Provider;

use ItePHP\Core\SessionProvider;
use ItePHP\Exception\ValueNotFoundException;
use ItePHP\Core\Enviorment;

/**
 * Provider for session.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
class Session implements SessionProvider{

	private $id;

	public function __construct(Enviorment $enviorment){
		if(!$enviorment->isSilent()){
			session_start();			
			$this->id=session_id();
		}
		else{//test
			$this->id=mt_rand(1,10000000);
		}
	}

	public function getId(){
		return $this->id;
	}

	public function get($key){
		if(!isset($_SESSION[$key]))
			throw new ValueNotFoundException($key);
		return $_SESSION[$key];
	}

	public function set($key,$value){
		$_SESSION[$key]=$value;
	}

	public function remove($key){
		if(isset($_SESSION[$key])){
			unset($_SESSION[$key]);			
		}
		else{
			throw new ValueNotFoundException($key);
		}

	}

	public function clear(){
		$_SESSION=array();
	}

}