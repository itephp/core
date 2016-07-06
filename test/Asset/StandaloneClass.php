<?php

namespace Asset;

class StandaloneClass{

	private $param1;
	private $param2;
	private $param3;

	const DATA='data1';

	public function __construct($param1,$param2){
		$this->param1=$param1;
		$this->param2=$param2;
	}

	public function getParam1(){
		return $this->param1;
	}

	public function getParam2(){
		return $this->param2;
	}

	public function getParam3(){
		return $this->param3;
	}

	public function setParam3($data){
		$this->param3=$data;
	}

}