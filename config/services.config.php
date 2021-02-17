<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

use Search\Form;

return [
    'factories' => [
        'search_search_result_form' => function ($sm) {
            return new Form\SearchResult($sm);
        },
    ],
];
