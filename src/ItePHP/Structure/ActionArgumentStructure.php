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
 * Structure for actions.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class ActionArgumentStructure implements Structure{

    /**
     * {@inheritdoc}
     */
	public function doConfig(ConfigBuilder $configBuilder){
		$argumentNode=new ConfigBuilderNode('argument');
		$argumentNode->addAttribute('storage');
		$argumentNode->addAttribute('name');
		$argumentNode->addAttribute('pattern','');
		$argumentNode->addAttribute('validator','');
		$argumentNode->addAttribute('mapper','');
		$argumentNode->addAttribute('default',false);

		$actionNode=$configBuilder->getNode('action');		
		$actionNode->addNode($argumentNode);
	}
}