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
use ItePHP\Contener\RequestConfig;
use ItePHP\Core\ExecuteResources;
use ItePHP\Core\EventManager;

/**
 * Interface dispatcher
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
interface Dispatcher {

	/**
	 * Execute dispatcher
	 *
	 * @param \ItePHP\Core\ExecuteResources $resources
	 * @param \ItePHP\Core\EventManager $eventManager
	 * @since 0.1.0
	 */
	public function execute(ExecuteResources $resources,EventManager $eventManager);
}