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

namespace ItePHP\Route;

/**
 * Factory for dispatcher. 
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class Router{
	
	/**
	 *
	 * @var array
	 */
	private $actions=[];

	/**
	 *
	 * @param string $pattern
	 * @param Dispatcher $dispatcher
	 */
	public function addAction($pattern,Dispatcher $dispatcher){
		$this->actions[$pattern]=$dispatcher;
	}

	/**
	 * Create dispatcher.
	 *
	 * @param string $url
	 * @return Dispatcher
	 * @throws RouteNotFoundException
	 */
	public function createDispatcher($url){
		$dispatcher=$this->findAction($url);

		if($dispatcher){
			return $dispatcher;
		}

		throw new RouteNotFoundException($url);
	}

	/**
	 * Find action routing
	 *
	 * @param string $url
	 * @return Dispatcher
	 */
	private function findAction($url){
		foreach($this->actions as $pattern=>$metadata){
			if(!preg_match('/^'.$pattern.'$/',$url)){
				continue;
			}
			return $metadata;
		}

	}

}