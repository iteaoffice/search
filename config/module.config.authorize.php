<?php

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
