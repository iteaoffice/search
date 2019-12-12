<?php

/**
 * Jield copyright message placeholder.
 *
 * @category    Search
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
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
