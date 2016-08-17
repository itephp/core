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

namespace ItePHP\Presenter;

use ItePHP\Core\Presenter;
use ItePHP\Core\Response;
use ItePHP\Core\Enviorment;

/**
 * Presenter for json.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class JSON implements Presenter{

	/**
	 *
	 * @param Enviorment $config
	 * @param Response $response
	 */
	public function render(Enviorment $config , Response $response){
		$this->setHeaders($config,$response);
		echo json_encode($response->getContent());
	}

	/**
	 *
	 * @param Enviorment $requestConfig
	 * @param Response $response
	 */
	private function setHeaders(Enviorment $requestConfig,Response $response){
		if(!$requestConfig->isSilent()){
			header('HTTP/1.1 '.$response->getStatusCode().' '.$response->getStatusMessage());
			header('Content-type: application/json');
			foreach($response->getHeaders() as $name=>$value){
				if($name!='content-type')
					header($name.': '.$value);
			}
		}

	}
}