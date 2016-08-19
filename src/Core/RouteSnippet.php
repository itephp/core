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
use ItePHP\Provider\Response;
use ItePHP\Core\Container;

class RouteSnippet {
	
	/**
	 * create response with configure redirect action
	 *
	 * @param Container $container
	 * @param string $url - destiny http address
	 * @return Response
	 */
	public function redirect(Container $container,$url){
		$response=new Response();
		$response->redirect($url);
		return $response;
	}

}
