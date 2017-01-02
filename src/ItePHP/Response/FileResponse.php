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

namespace ItePHP\Response;

use ItePHP\Core\AbstractResponse;
use ItePHP\Core\HeaderNotFoundException;

/**
 * Presenter for files.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class FileResponse extends AbstractResponse
{
    /**
     * @var string
     */
    private $contentString;

    /**
     * FileResponse constructor.
     * @param string $type
     */
    public function __construct($type)
    {
        $this->setHeader('Content-Type',$type);
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->setHeader('Content-Disposition','attachment;filename="'.$name.'"');
    }

    /**
     *
     */
    public function renderBody()
    {

        if ($this->getStatusCode() < 299) {

            $startRange = 0;
            $endRange = filesize($this->getContent()) - 1;

            try {
                $contentRange = $this->getHeader('Content-Range');

                if (preg_match('/^bytes ([0-9]+)-([0-9]+)\/([0-9]+)$/', $contentRange, $match)) {
                    $startRange = (int)$match[1];
                    $endRange = (int)$match[2];

                }

            } catch (HeaderNotFoundException $e) {
                //skipp
            }

            if($this->contentString){
                $this->renderString();
                return;
            }

            $buffer = 1024 * 8;
            $file = @fopen($this->getContent(), 'rb');
            fseek($file, $startRange);
            while (!feof($file) && ($p = ftell($file)) <= $endRange) {
                if ($p + $buffer > $endRange) {
                    $buffer = $endRange - $p + 1;
                }
                set_time_limit(0);
                echo fread($file, $buffer);
                flush();
            }

            fclose($file);
        }
    }

    /**
     * @param string $data
     */
    public function setContentString($data)
    {
        $this->contentString=$data;
    }

    /**
     * @return void
     */
    private function renderString()
    {
        echo $this->contentString;
    }

}

