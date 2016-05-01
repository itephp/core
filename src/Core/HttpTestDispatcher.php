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

namespace ItePHP\Core\Core;

use ItePHP\Core\Core\ExecuteResources;
use ItePHP\Core\Provider\Response;
use ItePHP\Core\Event\ExecuteActionEvent;
use ItePHP\Core\Event\ExecutedActionEvent;
use ItePHP\Core\Event\ExecutePresenterEvent;
use ItePHP\Core\Core\EventManager;
use ItePHP\Core\Exception\ActionNotFoundException;
use ItePHP\Core\Provider\Session;
use ItePHP\Core\Test\Request;
use ItePHP\Core\Contener\RequestConfig;

/**
 * Dispatcher for phpunit http request.
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
class HttpTestDispatcher  extends HttpDispatcher {

	/**
	 * Set Request test
	 *
	 * @param \ItePHP\Core\Test\Request $request
	 * @since 0.1.0
	 */
	public function setRequest(Request $request){
		$this->request=$request;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param \ItePHP\Core\Core\ExecuteResources $resources
	 * @param \ItePHP\Core\Core\EventManager $eventManager
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