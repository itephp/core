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

namespace ItePHP\Core\Parser;

/**
 * Interface for parser config file.
 *
 * @author Michal Tomczak (michal.tomczak@iteracja.com)
 * @since 0.1.0
 */
interface ParserInterface extends \Iterator{
	public function getValue($key);
	public function loadFromFile($path);
	public function loadFromString($data);
}