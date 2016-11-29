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

/**
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class CriticalErrorHandler implements ErrorHandler{
	
	/**
	 *
	 * @var Environment
	 */ 	
	private $environment;

	/**
	 *
	 * @param Environment $environment
	 */
	public function __construct(Environment $environment){
		$this->environment=$environment;
	}

    /**
     * {@inheritdoc}
     */
	public function execute(\Exception $exception){
		error_log($exception->getMessage()." ".$exception->getFile()."(".$exception->getLine().")");

		if(!$this->environment->isDebug()){
			return;
		}

		$presenter=new HTTPErrorPresenter($this->environment);
		$response=new Response();
		$response->setContent($exception);
		$request=new EmptyRequest('',null);

		$presenter->render($request,$response);

	}

}