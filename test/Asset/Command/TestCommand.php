<?php

namespace Asset\Command;

use ItePHP\Command\CommandInterface;
use ItePHP\Command\CommandConfig;
use ItePHP\Command\InputStream;
use ItePHP\Command\OutputStream;

class TestCommand implements CommandInterface{

	public function doConfig(CommandConfig $config){
		$config->addArgument('--boolean',0,false);
		$config->addArgument('string');
		$config->addArgument('array',2,[]);
	}

	public function execute(InputStream $in,OutputStream $out){
		$out->write($in->getValue('--boolean'));

		$out->write($in->getValue('string'));

		$out->write(implode("-",$in->getValue('array')));

		$out->flush();
	}

}