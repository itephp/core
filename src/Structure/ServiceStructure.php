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
 * Structure for services.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 */
class ServiceStructure implements Structure{

    /**
     * {@inheritdoc}
     */
	public function doConfig(ConfigBuilder $configBuilder){

		$argumentNode=new ConfigBuilderNode('argument');
		$argumentNode->addAttribute('type');
		$argumentNode->addAttribute('value');

		$methodNode=new ConfigBuilderNode('method');
		$methodNode->addAttribute('name');
		$methodNode->addNode($argumentNode);

		$serviceNode=new ConfigBuilderNode('service');
		$serviceNode->addAttribute('name');
		$serviceNode->addAttribute('class');
		$serviceNode->addAttribute('singleton','true');

		$serviceNode->addNode($methodNode);

		$configBuilder->addNode($serviceNode);
	}
}