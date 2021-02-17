<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

use Search\Controller;

return [
    'console' => [
        'router' => [
            'routes' => [
                'cli-search-update' => [
                    'options' => [
                        'route'    => 'search update <entity>',
                        'defaults' => [
                            'controller' => Controller\ConsoleController::class,
                            'action'     => 'search-update',
                        ],
                    ],
                ],
                'cli-search-reset'  => [
                    'options' => [
                        'route'    => 'search reset <entity>',
                        'defaults' => [
                            'controller' => Controller\ConsoleController::class,
                            'action'     => 'search-reset',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
