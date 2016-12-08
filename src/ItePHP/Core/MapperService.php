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

use ItePHP\Mapper\AbstractMapper;
use ItePHP\Mapper\MapperNotFoundException;

/**
 * Service to cast variables
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class MapperService{
	
	/**
	 * @var Container
	 */
	private $container;

	/**
	 * 
	 * @param Container $container
	 */
	public function __construct(Container $container){
		$this->container=$container;
	}

	/**
	 * Cast value
	 *
	 * @param string $mapperName - class with implements mapped code eg: "ItePHP\Mapper\Text"
	 * @param mixed $value - value to cast
	 * @return mixed
	 * @throws MapperNotFoundException
	 */
	public function cast($mapperName,$value){
		if(!class_exists($mapperName)){			
			throw new MapperNotFoundException($mapperName);
		}

        /**
         * @var AbstractMapper $mapper
         */
		$mapper=new $mapperName($this->container);
		return $mapper->cast($value);
	}

}