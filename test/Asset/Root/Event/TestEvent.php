<?php

/**
 * ItePHP: Framework PHP (http://itephp.com)
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the file LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) NewClass (http://newclass.pl)
 * @link          http://itephp.com ItePHP Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Asset\Root\Event;

use Asset\Root\Service\TestService;
use ItePHP\Core\ExecuteActionEvent;
use ItePHP\Core\ExecutedActionEvent;

class TestEvent{
	
	/**
	 *
	 * @var TestService
	 */
	private $test;

	/**
	 *
	 * @param TestService $testService
	 */
	public function __construct($testService){
		$this->test=$testService;
	}

	public function doExecuteAction(ExecuteActionEvent $event){

	}

	public function doExecutedAction(ExecutedActionEvent $event){
		
	}

}