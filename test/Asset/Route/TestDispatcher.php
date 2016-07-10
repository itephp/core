<?php

namespace Asset\Route;

use ItePHP\Route\Dispatcher;

class TestDispatcher implements Dispatcher{

	private $name;

	public function __construct($name){
		$this->name=$name;
	}

	public function getName(){
		return $this->name;
	}

	public function execute(){
		//ignore
	}

}