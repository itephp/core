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
 * Error dispatcher.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class ErrorDispatcher{

	/**
	 * Flag block propagation error events
	 *
	 * @var bool $stopPropagation
	 */	
	private $stopPropagation;

	/**
	 *
	 * @var ErrorHandler[]
	 */
	private $handlers=[];

	/**
	 *
	 * @param ErrorHandler $handler
	 */
	public function addHandler(ErrorHandler $handler){
		$this->handlers[spl_object_hash($handler)]=$handler;
	}

	/**
	 *
	 * @return ErrorHandler[]
	 */
	public function getHandlers(){
		return array_values($this->handlers);
	}

	/**
	 *
	 * @param ErrorHandler $handler
	 */
	public function removeHandler(ErrorHandler $handler){
		unset($this->handlers[spl_object_hash($handler)]);
	}

	/**
	 * Shutdown callback
	 */	
	public function shutdown(){
		if($this->stopPropagation)
			return;
		$error = error_get_last();
		if( $error !== NULL) {
			$file = $error["file"];
			$line = $error["line"];
			$message = $error["message"];

			$this->fireHandlers(new SyntaxException($message,$file,$line));
		}
	}

	/**
	 * Exception callback.
	 *
	 * @param Exception $exception
	 * @return bool
	 */	
	public function exception(Exception $exception){
		if($this->stopPropagation){
            return false;
        }

		$this->fireHandlers($exception);

		$this->stopPropagation=true;
		return false;
	}
		
	/**
	 * Error callback.
	 *
	 * @param int $level
	 * @param string $message
	 * @param string $file
	 * @param int $line
	 * @throws SyntaxException
	 */	
	public function error($level, $message, $file, $line){
		throw new SyntaxException($message,$file,$line,$level);
	}

	/**
	 *
	 * @param Exception $exception
	 */
	private function fireHandlers(Exception $exception){
		foreach($this->handlers as $handler){
			$handler->execute($exception);
		}
	}

}