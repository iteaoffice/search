<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Search
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (http://itea3.org)
 */

use Search\Form;

return [
    'factories' => [
        'search_search_result_form' => function ($sm) {
            return new Form\SearchResult($sm);
        },
    ],
];
