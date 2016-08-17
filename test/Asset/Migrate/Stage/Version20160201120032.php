<?php

namespace Migrate;
use ItePHP\Core\Container;

class Version20160201120032{
	
	public function up(Container $container){
		$data=file_get_contents($container->getEnviorment()->getRootPath().'/count.txt');
		file_put_contents($container->getEnviorment()->getRootPath().'/count.txt', $data.'2-');
	}

	public function down(Container $container){
		$data=file_get_contents($container->getEnviorment()->getRootPath().'/count.txt');
		file_put_contents($container->getEnviorment()->getRootPath().'/count.txt', substr($data, 0,-2));
	}

}