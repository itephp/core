<?php

/**
 * ItePHP: Freamwork PHP (http://php.iteracja.com)
 * Copyright (c) NewClass (http://newclass.pl)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the file LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) NewClass (http://newclass.pl)
 * @link          http://php.iteracja.com ItePHP Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace ItePHP\Core\Core;

use ItePHP\Core\Root;
use ItePHP\Core\Test\Request;
use ItePHP\Core\Core\Enviorment;
use ItePHP\Core\Test\BrowserEmulator;
use ItePHP\Core\Provider\Session;

require __DIR__.'/../core/Autoloader.php';

/**
 * Helper for functionalit web test
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
abstract class WebTestCase extends \PHPUnit_Framework_TestCase{
	
	/**
	 * Main ItePHP class.
	 *
	 * @var \ItePHP\Core\Root $root
	 */
	private $root;

	/**
	 * Enviorment
	 *
	 * @var \ItePHP\Core\Core\Enviorment $enviorment
	 */
	private $enviorment;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct(){
		$autoloader=new Autoloader();
		$this->root=new Root($autoloader,true,true,'test');
		$this->enviorment=new Enviorment(true,true,'test');
	}

	/**
	 * Get service object.
	 *
	 * @param string $name
	 * @return object service object
	 * @since 0.1.0
	 */
	protected function getService($name){
		return $this->root->getService($name);
	}

	/**
	 * Execute project command
	 *
	 * @return array sigint and result command
	 * @since 0.1.0
	 */
	protected function executeCommand(){
		ob_start();
		$sigint=$this->root->executeCommand(func_get_args());
		$result=ob_get_clean();
		ob_flush();

		return array($sigint,$result);
	}

	/**
	 * Create RequestTest
	 *
	 * @param string $url
	 * @return \ItePHP\Core\Test\Request
	 * @since 0.1.0
	 */
	protected function createRequest($url){

		return new Request($url,$this->enviorment);
	}

	/**
	 * Create BorwserEmulator.
	 *
	 * @param \ItePHP\Core\Provider\Session $session
	 * @return \ItePHP\Core\Test\BrowserEmulator
	 * @since 0.1.0
	 */
	protected function createClient(Session $session=null){
		return new BrowserEmulator($this->enviorment,$session);
	}

	/**
	 * Create Session.
	 *
	 * @param \ItePHP\Core\Provider\Session
	 * @since 0.1.0
	 */
	protected function createSession(){
		return new Session($this->enviorment);
	}

}