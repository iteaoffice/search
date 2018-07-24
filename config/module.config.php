<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Search
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

use Search\Controller;
use Search\Service;
use Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Zend\Stdlib;

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
