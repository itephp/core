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
use Pactum\ConfigBuilder;
use Pactum\ConfigBuilderObject;

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

        $configBuilder->addArray('service',new ConfigBuilderObject())->getValue()
            ->addString('class')
            ->addString('name')
            ->addBoolean('singleton',true)
            ->addArray('method',new ConfigBuilderObject())->getValue()
            ->addString('name')
            ->addArray('argument',new ConfigBuilderObject())->getValue()
            ->addString('type')
            ->addString('value'); //FIXME change to addMixed
    }
}