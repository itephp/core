<?php

namespace Test;

require_once(__DIR__.'/../autoload.php');

use ItePHP\Config\ConfigBuilderArgument;

class ConfigBuilderArgumentTest extends \PHPUnit_Framework_TestCase{
	
	public function testGetName(){
		$ca=new ConfigBuilderArgument('class');

		$this->assertEquals('class',$ca->getName());
	}

	public function testIsRequired(){
		$ca=new ConfigBuilderArgument('class');

		$this->assertTrue($ca->isRequired());

		$ca=new ConfigBuilderArgument('class','data');

		$this->assertFalse($ca->isRequired());

		$ca=new ConfigBuilderArgument('class','');
		$this->assertFalse($ca->isRequired());

		$ca=new ConfigBuilderArgument('class',false);
		$this->assertFalse($ca->isRequired());

	}


	public function testGetDefault(){
		$ca=new ConfigBuilderArgument('class','data');

		$this->assertEquals('data',$ca->getDefault());
	}

}