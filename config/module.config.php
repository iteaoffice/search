<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

use Search\Controller;
use Search\Service;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\Stdlib;

$config = [
    'controllers'        => [
        'factories' => [
            Controller\IndexController::class   => ConfigAbstractFactory::class,
            Controller\ConsoleController::class => ConfigAbstractFactory::class,
        ],
    ],
    'controller_plugins' => [
        'invokables' => [
            'csvExport' => Controller\Plugin\CsvExport::class,
        ],
    ],
    'view_manager'       => [
        'template_map' => include __DIR__ . '/../template_map.php',
    ],
    'service_manager'    => [
        'factories' => [
            Service\ConsoleService::class => ConfigAbstractFactory::class,
        ],
    ],
];

foreach (Stdlib\Glob::glob(__DIR__ . '/module.config.{,*}.php', Stdlib\Glob::GLOB_BRACE) as $file) {
    $config = Stdlib\ArrayUtils::merge($config, include $file);
}
return $config;
