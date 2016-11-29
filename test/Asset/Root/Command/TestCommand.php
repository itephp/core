<?php

namespace Test\Asset\Root\Command;

use ItePHP\Command\CommandInterface;
use ItePHP\Command\CommandConfig;
use ItePHP\Command\InputStream;
use ItePHP\Command\OutputStream;

use Test\Asset\Root\Service\TestService;

class TestCommand implements CommandInterface{

	private $testService;

	public function __construct(TestService $testService){
		$this->testService=$testService;
	}

	public function doConfig(CommandConfig $config){
		$config->addArgument('--name');
	}

	public function execute(InputStream $in,OutputStream $out){
		$out->write($this->testService->getText().' '.$in->getArgument('--name'));

		$out->flush();
	}

}