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

namespace ItePHP\Core\Event;
use ItePHP\Core\Core\Event;
use ItePHP\Core\Provider\Response;
use ItePHP\Core\Event\ExecutedActionEvent;

/**
 * Event to setting security headers
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.14.0
 */
class HttpSecurity extends Event{
	
	/**
	 * Init event.
	 *
	 * @param \ItePHP\Core\Event\ExecutedActionEvent $event
	 * @param array $config
	 * @since 0.1.0
	 */
	public function onExecutedAction(ExecutedActionEvent $event,$config){
		$response=$event->getResponse();
		$this->supportTimeForceSSL($response,$config);
	}
	

	/**
	 * Support Strict-Transport-Security
	 *
	 * @param \ItePHP\Core\Provider\Response $response
	 * @param array $config
	 * @see https://en.wikipedia.org/wiki/HTTP_Strict_Transport_Security
	 * @since 0.1.0
	 */
	private function supportTimeForceSSL(Response $response, $config){
		if(isset($config['forceSSL']) && $config['forceSSL']=="true"){
			$maxAge='3600000';
			if(isset($config['timeForceSSL']))
				$maxAge=$config['timeForceSSL'];

			$response->setHeader('Strict-Transport-Security','max-age='.$maxAge.'; includeSubDomains');
		}

	}

}