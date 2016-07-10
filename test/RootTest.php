<?php

namespace Test;

require_once(__DIR__.'/autoload.php');

define('ITE_ROOT',__DIR__.'/Asset/Root');

use ItePHP\Core\Enviorment;
use ItePHP\Root;

class RootTest extends \PHPUnit_Framework_TestCase{
	
	public function testExecuteRequest(){

		$envioroment=new Enviorment(true,true,'test');

		$root=new Root($envioroment);
		$root->executeRequest('test/api.html');

	}
}