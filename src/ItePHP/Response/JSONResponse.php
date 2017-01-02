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

use ItePHP\Core\AbstractResponse;

/**
 * Presenter for json.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class JSONResponse extends AbstractResponse{

    public function __construct()
    {
        $this->setHeader('Content-type','application/json');
    }

    /**
     * Generate content like: html, json etc.
     * @return void
     */
    public function renderBody()
    {
        echo json_encode($this->getContent());
    }
}