<?php

namespace Test;

require_once(__DIR__.'/../autoload.php');
require_once(ROOT.'/src/DependencyInjection/MetadataClass.php');
require_once(ROOT.'/src/DependencyInjection/MetadataMethod.php');
require_once(ROOT.'/test/Asset/StandaloneClass.php');

use ItePHP\DependencyInjection\DependencyInjection;
use ItePHP\DependencyInjection\MetadataClass;
use ItePHP\DependencyInjection\MetadataMethod;
use Asset\StandaloneClass;
use Asset\DependencyClass;

class DependencyInjectionTest extends \PHPUnit_Framework_TestCase{
	
	public function testInvokeStandalone(){

		$metadataClass=new MetadataClass('standalone','Asset\StandaloneClass');

		$metadataMethod=new MetadataMethod('__construct');
		$metadataMethod->addAttribute(MetadataMethod::PRIMITIVE_TYPE,'param1');
		$metadataMethod->addAttribute(MetadataMethod::PRIMITIVE_TYPE,'param2');
		$metadataClass->registerInvoke($metadataMethod);

		$metadataMethod=new MetadataMethod('setParam3');
		$metadataMethod->addAttribute(MetadataMethod::PRIMITIVE_TYPE,'data');
		$metadataClass->registerInvoke($metadataMethod);

		$di=new DependencyInjection();
		$di->register($metadataClass);

		$standaloneClass=$di->get('standalone');

		$this->assertEquals('param1',$standaloneClass->getParam1());
		$this->assertEquals('param2',$standaloneClass->getParam2());
		$this->assertEquals('data',$standaloneClass->getParam3());
	}

	public function testInvokeDependency(){
		$di=new DependencyInjection();

		$metadataClass=new MetadataClass('standalone','Asset\StandaloneClass');

		$metadataMethod=new MetadataMethod('__construct');
		$metadataMethod->addAttribute(MetadataMethod::PRIMITIVE_TYPE,'param1');
		$metadataMethod->addAttribute(MetadataMethod::PRIMITIVE_TYPE,'param2');
		$metadataClass->registerInvoke($metadataMethod);

		$metadataMethod=new MetadataMethod('setParam3');
		$metadataMethod->addAttribute(MetadataMethod::STATIC_TYPE,'Asset\StandaloneClass::DATA');
		$metadataClass->registerInvoke($metadataMethod);

		$di->register($metadataClass);

		$metadataClass=new MetadataClass('dependency','Asset\DependencyClass');

		$metadataMethod=new MetadataMethod('setStandalone');
		$metadataMethod->addAttribute(MetadataMethod::REFERENCE_TYPE,'standalone');
		$metadataClass->registerInvoke($metadataMethod);

		$metadataMethod=new MetadataMethod('enableFlag');
		$metadataClass->registerInvoke($metadataMethod);

		$di->register($metadataClass);

		$dependencyClass=$di->get('dependency');

		$this->assertEquals('param1',$dependencyClass->getParam1());
		$this->assertEquals('param2',$dependencyClass->getParam2());
		$this->assertEquals('data1',$dependencyClass->getParam3());
		$this->assertTrue($dependencyClass->isFlag());
	}

}