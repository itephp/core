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

namespace Asset;

use ItePHP\Core\RequestProvider;
use ItePHP\Core\HeaderNotFoundException;
use ItePHP\Component\Form\FileUploaded;
use ItePHP\Core\FileNotUploadedException;
use ItePHP\Core\Config;

/**
 * Provider for request.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class RequestTest implements RequestProvider{

	private $data=[];
	private $query=[];
	private $session;
	private $arguments=[];
	private $url;
	private $headers=[];
	private $clientIp;
	private $files=[];
	private $body;
	private $host;

	/**
	 *
	 * @var Config
	 */
	private $config;

	public function __construct($url,$type){
		$this->url=$url;
		$this->type=$type;
		$this->host='localhost';
		$this->session=new SessionTest();

	}

	/**
	 * Get uploaded file
	 * @param string $name - field name
	 * @return mixed
	 * @since 0.12.0
	 */
	public function getFile($name){
		if(!isset($this->files[$name])){
			throw new FileNotUploadedException($name);
		}

		return $this->files[$name];
	}

	public function addFile(FileUploaded $file){
		$this->files[$file->getName()]=$file;
	}

	public function getUrl(){
		return $this->url;
	}

	public function getType(){
		return $this->type;
	}

	public function getHeader($name){
		if(!isset($this->headers[strtolower($name)]))
			throw new HeaderNotFoundException($name);
			
		return $this->headers[strtolower($name)]; 
	}

	public function addHeader($name,$value){
		$this->headers[strtolower($name)]=$value;
	}

	public function getBody(){
		return $this->body;
	}

	public function setArgument($name,$value){
		$this->arguments[$name]=$value;
	}

	public function getArguments(){
		return $this->arguments;
	}

	public function removeArgument($index){
		unset($this->arguments[$index]);
	}

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
	public function setConfig(Config $config){
		$this->config=$config;
	}

	public function getData(){
		return $this->data;
	}

	public function setData($data){
		$this->data=$data;
	}

	public function getQuery(){
		return $this->query;
	}

	public function setQuery($query){
		$this->query=$query;
	}

	public function getHost(){
		return $this->host;
	}

	public function getProtocol(){
		return 'HTTP/1.1';
	}

	public function isSSL(){
		return false;
	}

	public function isAjax(){
		return false;
	}

	public function getClientIp(){
		return '127.0.0.1';
	}

	/**
	 * Detect for uploaded big post data on server. If return false then propably file and other post data not uploaded.
	 * @return boolean
	 * @since 0.18.0 
	 */
	public function isFullUploadedData(){
		return true;
	}

}