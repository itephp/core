<?php

namespace Test\Core;

use ItePHP\Config\ConfigBuilder;
use ItePHP\Config\ConfigException;
use ItePHP\Config\XmlFileReader;
use ItePHP\Core\Config;
use ItePHP\Structure\PresenterStructure;
use ItePHP\Structure\VariableStructure;

class ConfigTest extends \PHPUnit_Framework_TestCase{

    private $config;

    protected function setUp(){
        $configBuilder=new ConfigBuilder();

        $structure=new VariableStructure();
        $structure->doConfig($configBuilder);

        $structure=new PresenterStructure();
        $structure->doConfig($configBuilder);

        $xmlFileReader=new XmlFileReader(__DIR__.'/../../Asset/Core/Config/config.xml');
        $configBuilder->addReader($xmlFileReader);

        $this->config=new Config($configBuilder->parse());
    }

    public function testGetNodes(){
        $presenter=$this->config->getNodes('presenter');

        $this->assertCount(2,$presenter);

        $this->assertInstanceOf(Config::class,$presenter[0]);
    }

    public function testGetAttribute(){
        $presenters=$this->config->getNodes('presenter');

        $presenter=$presenters[0];

        $methods=$presenter->getNodes('method');
        $method=$methods[0];

        $arguments=$method->getNodes('argument');
        $this->assertEquals('ItePHP\Presenter\JSON',$presenter->getAttribute('class'));

        $this->assertEquals(true,$arguments[0]->getAttribute('value'));
        $this->assertEquals(false,$arguments[1]->getAttribute('value'));
        $this->assertEquals(1432,$arguments[2]->getAttribute('value'));
        $this->assertEquals(1432.321,$arguments[3]->getAttribute('value'));
        $this->assertEquals(null,$arguments[4]->getAttribute('value'));
        $this->assertEquals('data',$arguments[5]->getAttribute('value'));

    }

    public function testGetAttributeConfigExceptionVariable(){
        $presenters=$this->config->getNodes('presenter');

        $presenter=$presenters[1];
        $exception=null;
        try{
            $presenter->getAttribute('class');
        }
        catch(\Exception $e){
            $exception=$e;
        }

        $this->assertInstanceOf(ConfigException::class,$exception);
        $this->assertEquals('Variable unknown not found.',$exception->getMessage());

    }

    public function testGetAttributeConfigExceptionPrimitive(){
        $presenters=$this->config->getNodes('presenter');

        $presenter=$presenters[1];
        $methods=$presenter->getNodes('method');
        $method=$methods[0];

        $arguments=$method->getNodes('argument');

        $exception=null;
        try{
            $arguments[0]->getAttribute('value');
        }
        catch(\Exception $e){
            $exception=$e;
        }

        $this->assertInstanceOf(ConfigException::class,$exception);
        $this->assertEquals('Invalid primitive data value invalid.',$exception->getMessage());

    }

}