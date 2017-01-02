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
 * Provider for response.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
abstract class AbstractResponse
{
    /**
     *
     * @var int
     */
    private $statusCode = 200;

    /**
     *
     * @var mixed
     */
    private $content;

    /**
     *
     * @var string[]
     */
    private $headers = [];

    /**
     *
     * @var string[]
     */
    private $availableStatusCodes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        110 => 'Connection Timed Out',
        111 => 'Connection refused',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        310 => 'Too many redirects',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    ];

    /**
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     *
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->availableStatusCodes[$this->statusCode];
    }

    /**
     *
     * @param int $statusCode
     * @throws InvalidStatusCodeException
     */
    public function setStatusCode($statusCode)
    {
        if (!isset($this->availableStatusCodes[$statusCode])) {
            throw new InvalidStatusCodeException($statusCode);
        }
        $this->statusCode = $statusCode;
    }

    /**
     *
     * @param string $name
     * @param string $value
     */
    public function setHeader($name, $value)
    { //FIXME change to addHeader
        $this->headers[strtolower($name)] = $value;
    }

    /**
     *
     * @param string $name
     * @return string
     * @throws HeaderNotFoundException
     */
    public function getHeader($name)
    {
        $name = strtolower($name);
        if (!isset($this->headers[$name])) {
            throw new HeaderNotFoundException($name);
        }
        return $this->headers[$name];
    }

    /**
     *
     * @return string[]
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param $url
     * @return $this
     */
    public function redirect($url)
    {
        $this->setStatusCode(302);
        $this->setHeader('Location', $url);
        return $this;
    }

    /**
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     *
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param int $seconds
     */
    public function setExpire($seconds)
    {
        $time = gmdate("D, d M Y H:i:s", time() + $seconds) . " GMT";

        $this->setHeader('Expires', $time);
        $this->setHeader('Cache-Control', "public; max-age=" . $seconds);
        $this->setHeader('Pragma', "public; max-age=" . $seconds);

    }

    /**
     * Generate view.
     */
    public function render()
    {
        $this->renderHead();
        $this->renderBody();
    }

    /**
     * Send headers.
     */
    public function renderHead()
    {
        http_response_code($this->getStatusCode());
        foreach ($this->getHeaders() as $name => $value) {
            header($name . ': ' . $value);
        }
    }

    /**
     * Generate content like: html, json etc.
     * @return void
     */
    abstract public function renderBody();

}
