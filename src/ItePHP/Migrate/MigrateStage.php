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

namespace ItePHP\Migrate;

use ItePHP\Core\Container;

interface MigrateStage
{

    /**
     * @param Container $container
     * @return mixed
     */
    public function up(Container $container);

    /**
     * @param Container $container
     * @return mixed
     */
    public function down(Container $container);

}