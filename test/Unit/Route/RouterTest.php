<?php

namespace Test;

require_once(__DIR__.'/../../autoload.php');

use Via\Router;

class RouterTest extends \PHPUnit_Framework_TestCase{
	
	private $router;

	protected function  setUp(){
		$this->router=new Router();
		$this->router->addAction('test\/wow',new TestDispatcher('route1'));
		$this->router->addAction('test\/dig\/[0-9]+',new TestDispatcher('route2'));
	}

	public function testCreateDispatcher(){

		$dispatcher=$this->router->createDispatcher('test/dig/123');

		$this->assertEquals('route2',$dispatcher->getName());

	}
}