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

/**
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class PresenterNotFoundException extends \Exception{

	/**
	 *
	 * @param string $presenterName
	 */	
	public function __construct($presenterName){
		parent::__construct('Presenter '.$presenterName.' not found.');
	}

}