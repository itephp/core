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

namespace ItePHP\Structure;

use ItePHP\Config\ConfigBuilder;
use ItePHP\Config\ConfigBuilderNode;

/**
 * Structure for session actions.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class ActionAuthenticateStructure implements Structure{

    /**
     * {@inheritdoc}
     */
	public function doConfig(ConfigBuilder $configBuilder){
		$actionSessionNode=new ConfigBuilderNode('authenticate');
		$actionSessionNode->addAttribute('unauth-redirect',false);
		$actionSessionNode->addAttribute('auth-redirect',false);

		$actionNode=$configBuilder->getNode('action');		
		$actionNode->addNode($actionSessionNode);

		$sessionNode=new ConfigBuilderNode('authenticate');
		$sessionNode->addAttribute('max-time',0);

		$configBuilder->addNode($sessionNode);
	}
}