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

use ItePHP\Core\Presenter;
use \Exception;
use ItePHP\Core\Response;

/**
 * Presenter for http error.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.4.0
 */
class HTTPErrorPresenter implements Presenter{

    /**
     * {@inheritdoc}
     */
	public function render(Enviorment $enviorment , Response $response){

		if(!$config->isSilent()){
			header('HTTP/1.1 '.$response->getStatusCode().' '.$response->getStatusMessage());			
		}

		foreach($response->getHeaders() as $name=>$value){
			header($name.': '.$value);
		}

		echo $this->createTemplate($response);

	}

	/**
	 *
	 * @param Response $response
	 */
	private function createTemplate($response){
		$content=$response->getContent();
		$templateContent='';
		if($content instanceof Exception){
			$templateContent=$this->createExceptionContent($content);
		}
		else{
			$templateContent=$this->createObjectContent();

		}
		$template='<!DOCTYPE html>
			<HTML>
				<HEAD>
				</HEAD>
			<BODY>'.
				$templateContent
			.'</BODY>
			</HEAD>
			';

	}

	/**
	 *
	 * @param Exception $exception
	 */
	private function createExceptionContent(Exception $exception){
		$template='
			<div>'.get_class($exception).'</div>
			<div>'.$exception->getMessage().'</div>
			<div>'.$exception->getFile().'</div>
			<div>'.$exception->getLine().'</div>
		';

		return $template;

	}

	/**
	 *
	 * @param mixed $exception
	 */
	private function createObjectContent(mixed $content){
		$template='
			<div>'.gettype($content).'</div>
			<div>'.(string)$content.'</div>
		';

		return $template;

	}

}