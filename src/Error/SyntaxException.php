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

namespace ItePHP\Error;

/**
 * Throw when in php file is syntax error.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class SyntaxException extends \Exception{

    /**
     * @var int
     */
    private $level;

    /**
     * Constructor.
     *
     * @param string $message
     * @param string $file
     * @param int $line
     * @param int $level
     */
	public function __construct($message,$file, $line,$level=0){
		parent::__construct($message);
		$this->file=$file;
		$this->line=$line;
        $this->level=$level;
	}
}