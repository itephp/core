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

namespace ItePHP\Core\Presenter;

use ItePHP\Core\Core\Presenter;
use ItePHP\Core\Provider\Response;
use ItePHP\Core\Contener\RequestConfig;

/**
 * Presenter for json.
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
class JSON implements Presenter{

	public function render(RequestConfig $config , Response $response){
		$this->setHeaders($config,$response);
		echo json_encode($response->getContent());
	}

	private function setHeaders($requestConfig,$response){
		if(!$requestConfig->isSilent()){
			header('HTTP/1.1 '.$response->getStatusCode().' '.$response->getStatusMessage());
		}
		header('Content-type: application/json');
		foreach($response->getHeaders() as $name=>$value){
			if($name!='content-type')
				header($name.': '.$value);
		}

	}
}