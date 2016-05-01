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

namespace ItePHP\Core\Snippet;

use ItePHP\Core\Core\Container;

/**
 * Snippet for mapper
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.13.0
 */
class Mapper {
	
	public function cast(Container $container,$mapperName,$value){
		return $container->getService('mapper')->cast($container,$mapperName,$value);
	}
}
