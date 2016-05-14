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

namespace ItePHP\Core;

define('ITE_ROOT', realpath(__DIR__.'/../../../../../'));
define('ITE_WEB', ITE_ROOT.'/web');
define('ITE_SRC', ITE_ROOT.'/src');

use ItePHP\Root;
use ItePHP\Test\Request;
use ItePHP\Core\Enviorment;
use ItePHP\Test\BrowserEmulator;
use ItePHP\Provider\Session;

/**
 * Helper for functionalit web test
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
abstract class WebTestCase extends \PHPUnit_Framework_TestCase{
	
	/**
	 * Main ItePHP class.
	 *
	 * @var \ItePHP\Root $root
	 */
	private $root;

	/**
	 * Enviorment
	 *
	 * @var \ItePHP\Core\Enviorment $enviorment
	 */
	private $enviorment;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct(){
		$this->root=new Root(true,true,'test');
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
	 * @return \ItePHP\Test\Request
	 * @since 0.1.0
	 */
	protected function createRequest($url){

		return new Request($url,$this->enviorment);
	}

	/**
	 * Create BorwserEmulator.
	 *
	 * @param \ItePHP\Provider\Session $session
	 * @return \ItePHP\Test\BrowserEmulator
	 * @since 0.1.0
	 */
	protected function createClient(Session $session=null){
		return new BrowserEmulator($this->enviorment,$session);
	}

	/**
	 * Create Session.
	 *
	 * @param \ItePHP\Provider\Session
	 * @since 0.1.0
	 */
	protected function createSession(){
		return new Session($this->enviorment);
	}

}