<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

declare(strict_types=1);

namespace Search;

return [
    'laminas-cli' => [
        'commands' => [
            'search:update-index' => Command\UpdateIndex::class,
        ],
    ],
];
