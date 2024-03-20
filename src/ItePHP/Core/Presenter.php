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
use ItePHP\Contener\Config;
use ItePHP\Contener\RequestConfig;

/**
 * Interface for generator view
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
interface Presenter{
	
	/**
	 * Method with render rules.
	 *
	 * @param \ItePHP\Contener\RequestConfig $config
	 * @param \ItePHP\Provider\Response $response
	 * @since 0.1.0
	 */
	public function render(RequestConfig $config , Response $response);

}