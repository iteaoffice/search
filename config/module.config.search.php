<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Application
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (http://itea3.org)
 */
return [
    'google'        => [
        'cx' => '009339216969913709813:g_lfsuqxjz0', //itea
    ],
    'configuration' => [
        'solr_default' => [
            'select_path' => '/select',
            'update_path' => '/update',
            'resultClass' => '\SolrClient\Query\Result',
        ],
    ],
];
