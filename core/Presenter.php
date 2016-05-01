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

use ItePHP\Core\Provider\Response;
use ItePHP\Core\Contener\Config;
use ItePHP\Core\Contener\RequestConfig;

/**
 * Interface for generator view
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
interface Presenter{
	
	/**
	 * Method with render rules.
	 *
	 * @param \ItePHP\Core\Contener\RequestConfig $config
	 * @param \ItePHP\Core\Provider\Response $response
	 * @since 0.1.0
	 */
	public function render(RequestConfig $config , Response $response);

}