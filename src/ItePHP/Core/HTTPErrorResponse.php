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

namespace ItePHP\Core;

/**
 * Presenter for http error.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class HTTPErrorResponse extends AbstractResponse
{

    /**
     *
     * @return string
     */
    private function createTemplate()
    {
        $content = $this->getContent();
        if ($content instanceof \Exception) {
            $templateContent = $this->createExceptionContent($content);
        } else {
            $templateContent = $this->createObjectContent($content);

        }
        $template = '<!DOCTYPE html>
			<HTML>
				<HEAD>
				</HEAD>
			<BODY>' . $templateContent . '</BODY>
			</HEAD>
			';

        return $template;

    }

    /**
     *
     * @param \Exception $exception
     * @return string
     */
    private function createExceptionContent(\Exception $exception)
    {
        $template = '
			<div>' . get_class($exception) . '</div>
			<div>' . $exception->getMessage() . '</div>
			<div>' . $exception->getFile() . '</div>
			<div>' . $exception->getLine() . '</div>
		';

        return $template;
    }

    /**
     *
     * @param mixed $content
     * @return string
     */
    private function createObjectContent($content)
    {
        $template = '
			<div>' . gettype($content) . '</div>
			<div>' . (string)$content . '</div>
		';

        return $template;
    }

    /**
     * Generate content like: html, json etc.
     * @return void
     */
    public function renderBody()
    {
        echo $this->createTemplate();
    }
}