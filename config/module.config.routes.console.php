<?php

/**
 * Jield copyright message placeholder.
 *
 * @category    Search
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2018 ITEA Office (https://itea3.org)
 */

use Search\Controller;

return [
    'console' => [
        'router' => [
            'routes' => [
                'cli-search-update'                         => [
                    'options' => [
                        'route'    => 'search update <entity>',
                        'defaults' => [
                            'controller' => Controller\ConsoleController::class,
                            'action'     => 'search-update',
                        ],
                    ],
                ],
                'cli-search-reset'                          => [
                    'options' => [
                        'route'    => 'search reset <entity>',
                        'defaults' => [
                            'controller' => Controller\ConsoleController::class,
                            'action'     => 'search-reset',
                        ],
                    ],
                ],
                'cli-search-reset-registration-by-meeting'  => [
                    'options' => [
                        'route'    => 'search reset-registration <meeting>',
                        'defaults' => [
                            'controller' => Controller\ConsoleController::class,
                            'action'     => 'reset-registration-by-meeting',
                        ],
                    ],
                ],
                'cli-search-update-registration-by-meeting' => [
                    'options' => [
                        'route'    => 'search update-registration <meeting>',
                        'defaults' => [
                            'controller' => Controller\ConsoleController::class,
                            'action'     => 'update-registration-by-meeting',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
