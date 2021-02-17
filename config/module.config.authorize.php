<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

use BjyAuthorize\Guard\Route;

return [
    'bjyauthorize' => [
        'guards' => [
            /* If this guard is specified here (i.e. it is enabled], it will block
             * access to all routes unless they are specified here.
             */
            Route::class => [
                [
                    'route' => 'cli-search-update',
                    'roles' => [],
                ],
                [
                    'route' => 'cli-search-reset',
                    'roles' => [],
                ],
            ],
        ],
    ],
];
