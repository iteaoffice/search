<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Calendar
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

return [
    'bjyauthorize' => [
        'guards' => [
            /* If this guard is specified here (i.e. it is enabled], it will block
             * access to all routes unless they are specified here.
             */
            'BjyAuthorize\Guard\Route' => [
                [
                    'route' => 'cli-search-update',
                    'roles' => [],
                ],
                [
                    'route' => 'cli-search-reset',
                    'roles' => [],
                ],
                [
                    'route' => 'cli-search-reset-registration-by-meeting',
                    'roles' => [],
                ],
                [
                    'route' => 'cli-search-update-registration-by-meeting',
                    'roles' => [],
                ],
            ],
        ],
    ],
];
