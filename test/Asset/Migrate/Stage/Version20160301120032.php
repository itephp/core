<?php

namespace Migrate;
use ItePHP\Core\Container;

class Version20160301120032{
	
	public function up(Container $container){
		$data=file_get_contents(ITE_ROOT.'/count.txt');
		file_put_contents(ITE_ROOT.'/count.txt', $data.'3-');
	}

	public function down(Container $container){
		$data=file_get_contents(ITE_ROOT.'/count.txt');
		file_put_contents(ITE_ROOT.'/count.txt', substr($data, 0,-2));
	}

}