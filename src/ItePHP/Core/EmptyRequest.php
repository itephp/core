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

use Config\Config;
use Config\Config\Action;
use ItePHP\Provider\Session;

/**
 * Provider for request.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class EmptyRequest implements Request{

	/**
	 *
	 * @var string[]
	 */
	private $data=[];

	/**
	 *
     * @var string[]
	 */
	private $query=[];

	/**
	 *
	 * @var Session
	 */
	private $session;

	/**
	 *
	 * @var mixed[]
	 */
	private $arguments=[];

	/**
	 *
	 * @var string
	 */
	private $url;

	/**
	 *
	 * @var string[]
	 */
	private $headers=[];

	/**
	 *
	 * @var string
	 */
	private $clientIp;

	/**
	 *
	 * @var mixed[]
	 */
	private $files=[];

	/**
	 *
	 * @var Config
	 */
	private $config;

    /**
     * {@inheritdoc}
     */
	public function getFile($name){
		if(!isset($this->files[$name])){
			throw new FileNotUploadedException($name);
		}

		return $this->files[$name];
	}

    /**
     * {@inheritdoc}
     */
	public function getUrl(){
		return $this->url;
	}

    /**
     * {@inheritdoc}
     */
	public function getType(){
		return $_SERVER['REQUEST_METHOD'];
	}

    /**
     * {@inheritdoc}
     */
	public function getHeader($name){
		if(!isset($this->headers[strtolower($name)])){
			throw new HeaderNotFoundException($name);			
		}
			
		return $this->headers[strtolower($name)]; 
	}

    /**
     * {@inheritdoc}
     */
	public function getBody(){
		return file_get_contents('php://input');
	}

    /**
     * {@inheritdoc}
     */
	public function setArgument($name,$value){
		$this->arguments[$name]=$value;
	}

    /**
     * {@inheritdoc}
     */
	public function getArguments(){
		return $this->arguments;
	}

    /**
     * {@inheritdoc}
     */
	public function removeArgument($name){
		unset($this->arguments[$name]);
	}

    /**
     * {@inheritdoc}
     */
	public function getSession(){
		return $this->session;
	}

    /**
     * {@inheritdoc}
     */
	public function getConfig(){
		return $this->config;
	}

    /**
     * {@inheritdoc}
     */
	public function setConfig(Action $config){
		$this->config=$config;
	}

    /**
     * {@inheritdoc}
     */
	public function getData(){
		return $this->data;
	}

    /**
     * {@inheritdoc}
     */
	public function getQuery(){
		return $this->query;
	}

    /**
     * {@inheritdoc}
     */
	public function getHost(){
		return $_SERVER['HTTP_HOST'];
	}

    /**
     * {@inheritdoc}
     */
	public function getProtocol(){
		return $_SERVER['SERVER_PROTOCOL'];
	}

    /**
     * {@inheritdoc}
     */
	public function isSSL(){
		return ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) 
			&& $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) 
			&& $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') || (isset($_SERVER['HTTP_X_SSL_CIPHER'])));
	}

    /**
     * {@inheritdoc}
     */
	public function isAjax(){
		try{
			return strtolower($this->getHeader('x-requested-with'))=='xmlhttprequest';
		}
		catch(HeaderNotFoundException $e){
			return false;
		}

	}

    /**
     * {@inheritdoc}
     */
	public function getClientIp(){
		return $this->clientIp;
	}

    /**
     * {@inheritdoc}
     */
	public function isFullUploadedData(){
	    return true;
	}

}