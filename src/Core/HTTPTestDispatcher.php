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

use ItePHP\Core\ExecuteResources;
use ItePHP\Provider\Response;
use ItePHP\Event\ExecuteActionEvent;
use ItePHP\Event\ExecutedActionEvent;
use ItePHP\Event\ExecutePresenterEvent;
use ItePHP\Core\EventManager;
use ItePHP\Exception\ActionNotFoundException;
use ItePHP\Provider\Session;
use ItePHP\Test\Request;
use ItePHP\Contener\RequestConfig;

/**
 * Dispatcher for phpunit http request.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
class HTTPTestDispatcher  extends HttpDispatcher {

	/**
	 * Set Request test
	 *
	 * @param \ItePHP\Test\Request $request
	 * @since 0.1.0
	 */
	public function setRequest(Request $request){
		$this->request=$request;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param \ItePHP\Core\ExecuteResources $resources
	 * @param \ItePHP\Core\EventManager $eventManager
	 * @since 0.1.0
	 */
	public function execute(ExecuteResources $resources,EventManager $eventManager){
		$this->resources=$resources;
		$this->eventManager=$eventManager;
		$this->request->setConfig($this->config);
		$this->resources->registerRequest($this->request);

		$this->callMethod();

	}	

}