<?php

namespace Test;

require_once(__DIR__.'/../../autoload.php');

use ItePHP\Config\ConfigBuilder;
use ItePHP\Config\ConfigBuilderNode;
use ItePHP\Config\XmlFileReader;

class ConfigBuilderTest extends \PHPUnit_Framework_TestCase{
	
	private $config;

	public function setUp(){
		$this->config=new ConfigBuilder();

		$xmlReader=new XmlFileReader(__DIR__.'/../../Asset/Config/ConfigBuilder/example.xml');
		$this->config->addReader($xmlReader);

		$xmlReader=new XmlFileReader(__DIR__.'/../../Asset/Config/ConfigBuilder/example2.xml');
		$this->config->addReader($xmlReader);

		$argumentNode=new ConfigBuilderNode('argument');
		$argumentNode->addAttribute('type');
		$argumentNode->addAttribute('value');

		$methodNode=new ConfigBuilderNode('method');
		$methodNode->addAttribute('name');
		$methodNode->addNode($argumentNode);

		$serviceNode=new ConfigBuilderNode('service');
		$serviceNode->addAttribute('name');
		$serviceNode->addAttribute('class');
		$serviceNode->addAttribute('singletone','true');
		$serviceNode->addNode($methodNode);

		$this->config->addNode($serviceNode);

		$variableNode=new ConfigBuilderNode('variable');
		$this->config->addNode($variableNode);

	}	

	public function testParse(){

		$container=$this->config->parse();

		$this->parseServiceNodes($container);
		$this->assertCount(1,$container->getNodes('variable'));
	}

	private function parseServiceNodes($container){
		$serviceNodes=$container->getNodes('service');

		$serviceConfig=[
			[
				'serviceName',
				'ItePHP\Config\ConfigBuilder',
				'false',
					[
						[
							'name'=>'__constructor'
							,'arguments'=>[
								[
									'type'=>'primitive',
									'value'=>'data1',
								],
								[
									'type'=>'reference',
									'value'=>'manager',
								],
							]
						],
						[
							'name'=>'setData'
							,'arguments'=>[
								[
									'type'=>'primitive',
									'value'=>'1',
								]
							]
						],
					],
			],
			[
				'serviceName2',
				'ItePHP\Config\ConfigContainer',
				'true',
					[
					]
			]
		];

		$this->assertCount(count($serviceConfig),$serviceNodes);
		foreach($serviceNodes as $index=>$serviceNode){
			$config=$serviceConfig[$index];
			$this->parseServiceNode($serviceNode,$config[0],$config[1],$config[2],$config[3]);
		}

	}

	private function parseServiceNode($node,$name,$class,$singletone,$methods){
		$this->assertEquals($name,$node->getAttribute('name'));
		$this->assertEquals($class,$node->getAttribute('class'));
		$this->assertEquals($singletone,$node->getAttribute('singletone'));

		$methodNodes=$node->getNodes('method');
		$this->assertCount(count($methods),$methodNodes);
		for($i=0; $i < count($methodNodes); $i++){
			$this->parseMethodNode($methodNodes[$i],$methods[$i]);
		}
	}

	private function parseMethodNode($node,$config){
		$this->assertEquals($config['name'],$node->getAttribute('name'));

		$argumentNodes=$node->getNodes('argument');
		$this->assertCount(count($config['arguments']),$argumentNodes);
		for($i=0; $i < count($config['arguments']); $i++){
			$this->parseArgumentNode($argumentNodes[$i],$config['arguments'][$i]);
		}

	}

	private function parseArgumentNode($node,$config){
		$this->assertEquals($config['type'],$node->getAttribute('type'));
		$this->assertEquals($config['value'],$node->getAttribute('value'));
	}

}