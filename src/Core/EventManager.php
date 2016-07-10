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

use ItePHP\Core\ExecuteResources;

/**
 * Manager for events.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.1.0
 */
class EventManager{
	
	/**
	 * Enviorment.
	 *
	 * @var array $events
	 */
	private $events=array();

	/**
	 * Register event.
	 *
	 * @param string $event event name
	 * @param object $obj
	 * @param string $methodName
	 * @since 0.1.0
	 */
	public function register($event,$obj,$methodName){
		$this->events+=[
			$event=>[
			]
		];
		$this->events[$event][]=[
			'object'=>$obj,
			'methodName'=>$methodName,
			];
	}

	/**
	 * Execute event.
	 *
	 * @param string $event event name
	 * @param object $infoClass contener with event info eg.: \ItePHP\Event\ExecutePresenterEvent
	 * @since 0.1.0
	 */
	public function fire($eventName,$infoClass=null){
		if(isset($this->events[$eventName])){
			foreach($this->events[$eventName] as $bind=>$data){
				call_user_func_array([$data['object'], $data['methodName']], [$infoClass]);
			}
		}
		//FIXME throw exception?
	}

}