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

use ItePHP\Error\ErrorHandler;
use ItePHP\Core\Enviorment;
use \Exception;

/**
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.4.0
 */
class CriticalErrorHandler implements ErrorHandler{
	
	/**
	 *
	 * @var Enviorment
	 */ 
	
	private $enviorment;
	/**
	 *
	 * @param Enviorment $enviorment
	 */
	public function __construct(Enviorment $enviorment){
		$this->enviorment=$enviorment;
	}

    /**
     * {@inheritdoc}
     */
	public function execute(Exception $exception){
		error_log($exception->getMessage()." ".$exception->getFile()."(".$exception->getLine().")");

		if(!$exception->isDebug()){
			return;
		}

		$presenter=new HttpErrorPresenter();
		$response=new Response();
		$response->setContent($exception);

		$presenter->render($this->enviorment,$response);

	}

}