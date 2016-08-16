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

namespace ItePHP\Component\Form;

/**
 * Throw when FileUploaded can not save file.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.16.0
 */
class FileFailSavedException extends \Exception{
	
	/**
	 * Constructor.
	 *
	 * @param string $reason
	 */	
	public function __construct($reason){
		parent::__construct("File fail saved. Reason: ".$reason.".");
	}
}