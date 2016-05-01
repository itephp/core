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

namespace ItePHP\Core\Snippet;
use ItePHP\Core\Provider\Response;
use ItePHP\Core\Core\Container;

class Route {
	
	/**
	 * create response with configure redirect action
	 *
	 * @param \ItePHP\Core\Core\Container $container
	 * @param string $url - destiny http address
	 * @return \ItePHP\Core\Provider\Response
	 */
	public function redirect(Container $container,$url){
		$response=new Response();
		$response->redirect($url);
		return $response;
	}

}
