<?php

namespace Test;

require_once(__DIR__.'/autoload.php');

define('ITE_ROOT',__DIR__.'/Asset/Root');

use ItePHP\Core\Enviorment;
use ItePHP\Root;

class RootTest extends \PHPUnit_Framework_TestCase{
	
	public function testExecuteRequest(){
		$_SERVER=[];
		$_SERVER['REMOTE_ADDR']='127.0.0.1';
		$envioroment=new Enviorment(true,true,'test');

		$root=new Root($envioroment);

		ob_start();
		$root->executeRequest('/test');
		$result=ob_get_clean();
		ob_flush();

		$this->assertEquals('hello',$result);

	}
}