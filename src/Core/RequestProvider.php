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

use ItePHP\Core\Config;


/**
 * Interface for request
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
interface RequestProvider{

	/**
	 * Get uploaded file.
	 *
	 * @param string $name field name
	 * @return FileUploaded
	 */
	public function getFile($name);
	
	/**
	 * Get http url.
	 *
	 * @return string
	 */
	public function getUrl();

	/**
	 * Get http method (POST,PUT,GET,DELETE).
	 *
	 * @return string
	 */
	public function getType();

	/**
	 * Get header value.
	 *
	 * @param string $name header name
	 * @return string
	 * @throws HeaderNotFoundException
	 */
	public function getHeader($name);

	/**
	 * Get request body.
	 *
	 * @return string
	 */
	public function getBody();

	/**
	 * Set method of controller argument.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function setArgument($name,$value);

	/**
	 * Get method of controller arguments.
	 *
	 * @param array
	 */
	public function getArguments();

	/**
	 * Remove method of controller argument.
	 *
	 * @param string $name
	 */
	public function removeArgument($name);

	/**
	 * Get session provider.
	 *
	 * @return \ItePHP\Core\SessionProvider
	 */
	public function getSession();

	/**
	 * Get request POST data.
	 *
	 * @return array
	 */
	public function getData();

	/**
	 * Get request GET data.
	 *
	 * @return array
	 */
	public function getQuery();

	/**
	 * Get host name.
	 *
	 * @return string
	 */
	public function getHost();

	/**
	 * Get server protocol (HTTP/1.1).
	 *
	 * @return string
	 */
	public function getProtocol();

	/**
	 * Check is security connect to server.
	 *
	 * @return boolean
	 */
	public function isSSL();

	/**
	 * Check is ajax request (xml http request)
	 *
	 * @return boolean
	 */
	public function isAjax();

	/**
	 * Get client ip.
	 *
	 * @return string
	 */
	public function getClientIp();

	/**
	 * Detect for uploaded big post data on server. If return false then propably file and other post data not uploaded.
	 *
	 * @return boolean
	 */
	public function isFullUploadedData();

	/**
	 *
	 * @return Config
	 */
	public function getConfig();

	/**
	 *
	 * @param Config $config
	 */
	public function setConfig(Config $config);

}