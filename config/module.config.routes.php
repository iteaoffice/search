<?php

/**
 * ITEA Office all rights reserved
 *
 * @category  Search
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

use Search\Controller;

return [
    'router' => [
        'routes' => [
            'search' => [
                'type'     => 'Literal',
                'priority' => 1000,
                'options'  => [
                    'route'    => '/search',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'search',
                    ],
                ],
            ],
        ],
    ],
];
