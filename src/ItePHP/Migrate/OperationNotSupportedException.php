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

namespace ItePHP\Migrate;

/**
 * Throw when executed url not configured in config/actions.xml
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class OperationNotSupportedException extends \Exception{
	
	/**
	 * Constructor.
	 *
	 * @param string $operation
	 */
	public function __construct($operation){
		parent::__construct('Operation '.$operation.' not supported. Allowed: create, upgrade, downgrade');
	}
}