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
 * Presenter for http error.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class HTTPErrorPresenter implements Presenter{

    /**
     * @var Environment
     */
    private $environment;

    /**
     * HTTPErrorPresenter constructor.
     * @param Environment $environment
     */
    public function __construct(Environment $environment){
        $this->environment=$environment;
    }

    /**
     * {@inheritdoc}
     */
	public function render(Request $request , Response $response){
	    if(!$this->environment->isSilent()){
            header('HTTP/1.1 '.$response->getStatusCode().' '.$response->getStatusMessage());
            foreach($response->getHeaders() as $name=>$value){
                header($name.': '.$value);
            }
        }

		echo $this->createTemplate($response);
	}

    /**
     *
     * @param Response $response
     * @return string
     */
	private function createTemplate($response){
		$content=$response->getContent();
		if($content instanceof \Exception){
			$templateContent=$this->createExceptionContent($content);
		}
		else{
			$templateContent=$this->createObjectContent($content);

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

		return $template;

	}

    /**
     *
     * @param \Exception $exception
     * @return string
     */
	private function createExceptionContent(\Exception $exception){
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
     * @param mixed $content
     * @return string
     */
	private function createObjectContent($content){
		$template='
			<div>'.gettype($content).'</div>
			<div>'.(string)$content.'</div>
		';

		return $template;
	}

}