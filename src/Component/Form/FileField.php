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

use ItePHP\Validator\FileValidator;

/**
 * FormBuilder field
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class FileField extends InputField{

    /**
     * @var mixed[]
     */
	private $data;

    /**
     * @var int
     */
	private $maxSize;

	/**
	 * {@inheritdoc}
	 */
	public function __construct($options){

		$options+=array(
			'multiple'=>false
		);

		$options['type']='file';

		if(!isset($options['validator'])){
			$this->setValidator(new FileValidator());
		}

		if(isset($options['accept'])){
			$this->setAccept($options['accept']);
			unset($options['accept']);
		}

		if(isset($options['maxSize'])){
			$this->setMaxSize($options['maxSize']);
			unset($options['maxSize']);
		}
		else{
			$this->setMaxSize($this->getServerMaxSize());
		}

		parent::__construct($options);
	}

	/**
	 * Set html tag multiple
	 *
	 * @param boolean $flag - value of tag multiple
	 */
	public function setMultiple($flag){
		$this->setTag('multiple',$flag);
	}

	/**
	 * Get html tag multiple
	 *
	 * @return bool
	 */
	public function isMultiple(){
		$tags=$this->getTags();
		return (isset($tags['multiple']) && $tags['multiple']);
	}

	/**
	 * Set html tag accept
	 *
	 * @param string $accept - value of tag accept
	 */
	public function setAccept($accept){
		$this->setTag('accept',$accept);
		if($this->getValidator()){
			$this->getValidator()->setOption('accept',$accept);
		}
	}

	/**
	 * Get html tag accept
	 *
	 * @return string
	 */
	public function getAccept(){
		return $this->accept;
	}

    /**
     * Set max size file
     *
     * @param int $maxSize - size in bytes
     * @throws FileMaxSizeException
     */
	public function setMaxSize($maxSize){
		$serverMaxSize=$this->getServerMaxSize();
		if($maxSize>$serverMaxSize){
			throw new FileMaxSizeException($serverMaxSize);
		}
		$this->maxSize=$maxSize;
		if($this->getValidator()){
			$this->getValidator()->setOption('maxSize',$maxSize);
		}
	}

	/**
	 * Get max size file
	 *
	 * @return int - file size in bytes
	 */
	public function getMaxSize(){
		return $this->maxSize;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setData($value){
		$this->data=$value;
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
	public function clearData(){
		$this->data=null;
	}

    /**
     * {@inheritdoc}
     */
	public function componentRender(){
		$template='<input ';

		foreach($this->getTags() as $kTag=>$tag){
			if($tag!=''){
				if($kTag=='name' && $this->isMultiple()){
					$tag.='[]';
				}				
				$template.=$kTag.'="'.htmlspecialchars($tag).'" ';
			}
		}
		$template.=' />';
		return $template;

	}

	/**
	 * Get max size file from config server
	 *
	 * @return int - file size in bytes
	 */
	private function getServerMaxSize(){
		return min($this->phpSizeToBytes(ini_get('post_max_size')),$this->phpSizeToBytes(ini_get('upload_max_filesize')));  
	}

	/**
	 * Php size format to bytes
	 *
	 * @param string $size - php size format
	 * @return int - file size in bytes
	 */
	private function phpSizeToBytes($size){  
		if (is_numeric( $size)){
			return $size;
		}
		$suffix = substr($size, -1);
		$value = substr($size, 0, -1);
		switch(strtolower($suffix)){
			case 'p':
				$value *= 1024;
			case 't':
				$value *= 1024;
			case 'g':
				$value *= 1024;
			case 'm':
				$value *= 1024;
			case 'k':
				$value *= 1024;
				break;
		}
		return $value;  
	}

}
