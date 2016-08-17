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

/**
 * Main class for project exception.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class Exception extends \Exception{
	
	/**
	 * Constructor.
	 *
	 * @param int $code error code
	 * @param string $message error message
	 * @param string $safeMessage message show in production mode
	 * @param string $file file when throwed exception
	 * @param int $line line when throwed exception
	 */	
	public function __construct($code,$message , $safeMessage=null,$file=null,$line=null){
		$this->code=$code;
		$this->message=$message;
		$this->safeMessage=$safeMessage;
		if($this->file)
			$this->file=$file;
		if($this->line)
			$this->line=$line;
	}

	/**
	 * Get message for production mode.
	 *
	 * @return string
	 */	
	public function getSafeMessage(){
		if($this->safeMessage)
			return $this->safeMessage;
		else
			return $this->getMessage();
	}
}