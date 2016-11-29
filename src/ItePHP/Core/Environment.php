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
 * Environment settings.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class Environment{

	/**
	 * @var bool $debug
	 */
	private $debug;

	/**
	 * @var boolean $silent
	 */
	private $silent;

	/**
	 * @var string $name
	 */
	private $name;

	/**
	 * @var string
	 */
	private $rootPath;

	/**
	 * @var string
	 */
	private $srcPath;

	/**
	 * @var string
	 */
	private $cachePath;

	/**
	 * @var string
	 */
	private $webPath;

    /**
     * @var string
     */
    private $vendorPath;

    /**
     * @param bool $debug
     * @param bool $silent
     * @param string $name
     * @param string $rootPath
     * @param string $configPath
     * @param string $srcPath
     * @param string $webPath
     * @param string $cachePath
     * @param string $vendorPath
     */
	public function __construct($debug,$silent,$name,$rootPath,$configPath=null,$srcPath=null,$webPath=null
		,$cachePath=null,$vendorPath=null){

		$this->debug=$debug;
		$this->silent=$silent;
		$this->name=$name;

		$this->rootPath=$rootPath;
		if($srcPath===null){
			$srcPath=$this->rootPath.'/src';
		}

		if($configPath===null){
			$configPath=$this->rootPath.'/config';
		}

		if($cachePath===null){
			$cachePath=$this->rootPath.'/cache';
		}

		if($webPath===null){
			$webPath=$this->rootPath.'/web';
		}

        if($vendorPath===null){
            $vendorPath=$this->rootPath.'/vendor';
        }

        $this->srcPath=$srcPath;
		$this->configPath=$configPath;
		$this->cachePath=$cachePath;
		$this->webPath=$webPath;
        $this->vendorPath=$vendorPath;
	}

	/**
	 * @return bool
	 */
	public function isDebug(){
		return $this->debug;
	}	

	/**
	 * @return bool
	 */
	public function isSilent(){
		return $this->silent;
	}

	/**
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getRootPath(){
		return $this->rootPath;
	}

	/**
	 * @return string
	 */
	public function getSrcPath(){
		return $this->srcPath;
	}

	/**
	 * @return string
	 */
	public function getVendorPath(){
		return $this->vendorPath;
	}

	/**
	 * @return string
	 */
	public function getWebPath(){
		return $this->webPath;
	}

	/**
	 * @return string
	 */
	public function getCachePath(){
		return $this->cachePath;
	}

	/**
	 * @return string
	 */
	public function getConfigPath(){
		return $this->configPath;
	}

}
