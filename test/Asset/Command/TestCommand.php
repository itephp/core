<?php

namespace Test\Asset\Command;

use ItePHP\Command\CommandInterface;
use ItePHP\Command\CommandConfig;
use ItePHP\Command\InputStream;
use ItePHP\Command\OutputStream;

class TestCommand implements CommandInterface{

	public function doConfig(CommandConfig $config){
		$config->addArgument('--boolean',0,"0");
		$config->addArgument('string');
		$config->addArgument('array',2,[]);
	}

	public function execute(InputStream $in,OutputStream $out){
		$out->write($in->getArgument('--boolean'));

		$out->write($in->getArgument('string'));

		$out->write(implode("-",$in->getArgument('array')));

		$out->flush();
	}

}