<?php

namespace Test;

require_once(__DIR__.'/../autoload.php');

define('ITE_ROOT',__DIR__.'/../Asset/Migrate');

define('ITE_SRC', ITE_ROOT.'/src');

use ItePHP\Core\Enviorment;
use ItePHP\Root;

class MigrateCommandTest extends \PHPUnit_Framework_TestCase{

	public function testCreate(){
		date_default_timezone_set('Europe/Warsaw');
		$this->clearStageDir();

		$envioroment=new Enviorment(true,true,'test');
		$root=new Root($envioroment);

		ob_start();
		$sigint=$root->executeCommand(['migrate','-o','create']);
		$result=ob_get_clean();
		ob_flush();

		$this->clearStageDir();

		$this->assertContains('File created:',$result);

	}

	public function testUpgrade(){
		date_default_timezone_set('Europe/Warsaw');

		file_put_contents(ITE_ROOT.'/count.txt', '');
		file_put_contents(ITE_ROOT.'/migrate.txt', '0');

		$envioroment=new Enviorment(true,true,'test');
		$root=new Root($envioroment);

		ob_start();
		$sigint=$root->executeCommand(['migrate','-o','upgrade']);
		$result=ob_get_clean();
		ob_flush();
		$data=file_get_contents(ITE_ROOT.'/count.txt');

		$this->assertEquals('1-2-3-',$data);

	}

	public function testDowngrade(){
		date_default_timezone_set('Europe/Warsaw');

		file_put_contents(ITE_ROOT.'/count.txt', '1-2-3-');
		file_put_contents(ITE_ROOT.'/migrate.txt', '20160301120032');

		$envioroment=new Enviorment(true,true,'test');
		$root=new Root($envioroment);

		ob_start();
		$sigint=$root->executeCommand(['migrate','-o','downgrade']);
		$result=ob_get_clean();
		ob_flush();
		$data=file_get_contents(ITE_ROOT.'/count.txt');

		$this->assertEquals('',$data);

	}

	private function clearStageDir(){
		$allowStages=['Version20160101120032.php','Version20160201120032.php','Version20160301120032.php'];
		$stageOpen=opendir(ITE_ROOT.'/Stage');
		while($stageRead=readdir($stageOpen)){
			if(in_array($stageRead,['.','..'])){
				continue;
			}

			if(!in_array($stageRead, $allowStages)){
				unlink(ITE_ROOT.'/Stage/'.$stageRead);
			}

		}

	}

}