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
 * Structure for commands.
 *
 * @author Michal Tomczak (michal.tomczak@itephp.com)
 * @since 0.4.0
 */
class CommandStructure implements Structure{

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


		$commandNode=new ConfigBuilderNode('command');
		$commandNode->addAttribute('class');
		$commandNode->addAttribute('name');

		$commandNode->addNode($methodNode);

		$configBuilder->addNode($commandNode);

	}	

}