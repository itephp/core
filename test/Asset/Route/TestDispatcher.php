<?php

namespace Test\Asset\Route;


use Via\Dispatcher;

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