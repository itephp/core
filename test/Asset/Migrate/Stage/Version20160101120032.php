<?php

namespace Migrate;
use ItePHP\Command\Migrate;

class Version20160101120032{
	
	public function up(Migrate $migrate){
		$data=ITE_ROOT.'/test/Asset/Migrate/count.txt';
		file_put_contents(ITE_ROOT.'/test/Asset/Migrate/count.txt', $data.'1-');
	}

	public function down(Migrate $migrate){
		$data=ITE_ROOT.'/test/Asset/Migrate/count.txt';
		file_put_contents(ITE_ROOT.'/test/Asset/Migrate/count.txt', substr($data, 0,-2));
	}

}