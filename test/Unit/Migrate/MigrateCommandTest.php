<?php

namespace Test\Migrate;

use ItePHP\Core\Environment;
use ItePHP\Root;

class MigrateCommandTest extends \PHPUnit_Framework_TestCase{

	private $environment;

	public function setUp(){
		$this->environment=new Environment(true,true,'test',__DIR__.'/../../Asset/Migrate');
	}

	public function testCreate(){
		date_default_timezone_set('Europe/Warsaw');
		$this->clearStageDir();

		$root=new Root($this->environment);

		ob_start();
		$root->executeCommand(['migrate','-o','create']);
		$result=ob_get_clean();
		ob_flush();

		$this->clearStageDir();

		$this->assertContains('File created:',$result);

	}

	public function testUpgrade(){
		date_default_timezone_set('Europe/Warsaw');

		file_put_contents($this->environment->getRootPath().'/count.txt', '');
		file_put_contents($this->environment->getRootPath().'/migrate.txt', '0');

		$root=new Root($this->environment);

		ob_start();
		$sigint=$root->executeCommand(['migrate','-o','upgrade']);
		$result=ob_get_clean();
		ob_flush();
		$data=file_get_contents($this->environment->getRootPath().'/count.txt');

		$this->assertEquals('1-2-3-',$data);

	}

	public function testDowngrade(){
		date_default_timezone_set('Europe/Warsaw');

		file_put_contents($this->environment->getRootPath().'/count.txt', '1-2-3-');
		file_put_contents($this->environment->getRootPath().'/migrate.txt', '20160301120032');

		$root=new Root($this->environment);

		ob_start();
		$sigint=$root->executeCommand(['migrate','-o','downgrade']);
		$result=ob_get_clean();
		ob_flush();
		$data=file_get_contents($this->environment->getRootPath().'/count.txt');

		$this->assertEquals('',$data);

	}

	private function clearStageDir(){
		$allowStages=['Version20160101120032.php','Version20160201120032.php','Version20160301120032.php'];
		$stageOpen=opendir($this->environment->getRootPath().'/Stage');
		while($stageRead=readdir($stageOpen)){
			if(in_array($stageRead,['.','..'])){
				continue;
			}

			if(!in_array($stageRead, $allowStages)){
				unlink($this->environment->getRootPath().'/Stage/'.$stageRead);
			}

		}

	}

}