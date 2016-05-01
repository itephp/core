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

namespace ItePHP\Core\Exception;
use ItePHP\Core\Core\Exception;

/**
 * Throw when mapper service or mapper snipper can not find cast rule.
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
class MapperNotFoundException extends Exception{
	
	/**
	 * Constructor.
	 *
	 * @param string $mapperName
	 * @since 0.1.0
	 */
	public function __construct($mapperName){
		parent::__construct(4,'Mapper "'.$mapperName.'" not found.','Internal server error.');
	}
}