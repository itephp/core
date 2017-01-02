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

namespace ItePHP\Presenter;

use ItePHP\Core\Presenter;
use ItePHP\Core\Request;
use ItePHP\Core\AbstractResponse;
use ItePHP\Core\Environment;

/**
 * Presenter for html.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class HTMLResponse extends AbstractResponse{


    /**
     * Generate content html.
     * @return void
     */
    public function renderBody()
    {
        echo (string)$this->getContent();
    }
}