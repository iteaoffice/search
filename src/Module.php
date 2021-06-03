<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace Search;

use Laminas\Console\Adapter\AdapterInterface;
use Laminas\ModuleManager\Feature;

/**
 * Class Module
 * @package Search
 */
final class Module implements
    Feature\ConfigProviderInterface,
    Feature\ConsoleUsageProviderInterface
{
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getConsoleUsage(AdapterInterface $console): array
    {
        return [
            'Search management',
            // Describe available commands
            'search reset <entity>'  => 'Reset the search index (wipe and rebuild index)',
            [
                '<entity>',
                'Choose an entity to reset: project, version, roadmap, version-document, workpackage-document, 
                result, impact-stream, action, achievement, exploitable-result, publication, invoice, contact, 
                profile, affiliation, registration, calendar, news, blog, press, organisation, country, all',
            ],
            'search update <entity>' => 'Update the search index',
            [
                '<entity>',
                'Choose an entity to update: project, version, roadmap, version-document, workpackage-document,
                result, impact-stream, action, achievement, exploitable-result, publication, invoice, contact, 
                profile, affiliation, registration, calendar, news, blog, press, organisation, country, all',
            ],
        ];
    }
}
