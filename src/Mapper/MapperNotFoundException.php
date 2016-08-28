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

namespace ItePHP\Mapper;

use ItePHP\Core\Exception;

/**
 * Throw when mapper service or mapper snippet can not find cast rule.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class MapperNotFoundException extends Exception{

	/**
	 * Constructor.
	 *
	 * @param string $mapperName
	 */
	public function __construct($mapperName){
		parent::__construct(4,'Mapper "'.$mapperName.'" not found.','Internal server error.');
	}
}