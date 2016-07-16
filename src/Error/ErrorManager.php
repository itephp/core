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

namespace ItePHP\Error;

use \Exception;

/**
 * Error manager.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.4.0
 */
class ErrorManager{

	/**
	 *
	 * @var ErrorDispatcher
	 */
	private $dispatcher;

	/**
	 * Constructor.
	 *
	 * @since 0.1.0
	 */	
	public function __construct(){
		$this->stopPropagation=false;
		$this->runtimePath=getcwd();
		$this->dispatcher=new ErrorDispatcher();

		ini_set('display_errors', 'Off');
		register_shutdown_function(array($this->dispatcher, 'shutdown'));
		set_error_handler(array($this->dispatcher, 'error'));
		set_exception_handler(array($this->dispatcher,'exception'));
	}

	/**
	 *
	 * @param ErrorHandler $handler
	 */
	public function addHandler(ErrorHandler $handler){
		$this->dispatcher->addHandler($handler);
	}

	/**
	 *
	 * @return array
	 */
	public function getHandlers(){
		return $this->dispatcher->getHandlers();
	}

	/**
	 *
	 * @param ErrorHandler $handler
	 */
	public function removeHandler(ErrorHandler $handler){
		$this->dispatcher->removeHandler($handler);
	}

	/**
	 * Exception callback.
	 *
	 * @param Exception $exception
	 * @since 0.1.0
	 */	
	public function exception(Exception $exception){
		$this->dispatcher->exception($exception);
	}

}