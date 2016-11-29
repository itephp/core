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
namespace Test\Asset\Root\Service;

class TestService{
	
	/**
	 *
	 * @var string
	 */
	private $text;

	/**
	 *
	 * @param string $text
	 */
	public function __construct($text){
		$this->text=$text;
	}

	/**
	 *
	 * @return string
	 */
	public function getText(){
		return $this->text;
	}

}