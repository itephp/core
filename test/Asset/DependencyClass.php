<?php

namespace Test\Asset;

class DependencyClass{

	private $standalone;
	private $flag=false;

	public function setStandalone(StandaloneClass $standalone){
		$this->standalone=$standalone;
	}	

	public function enableFlag(){
		$this->flag=true;
	}	

	public function getParam1(){
		return $this->standalone->getParam1();
	}

	public function getParam2(){
		return $this->standalone->getParam2();
	}

	public function getParam3(){
		return $this->standalone->getParam3();
	}

	public function isFlag(){
		return $this->flag;
	}

}