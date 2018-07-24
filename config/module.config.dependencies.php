<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/project for the canonical source repository
 */

declare(strict_types=1);

namespace Search;

use Affiliation\Search\Service\AffiliationSearchService;
use Contact\Search\Service\ContactSearchService;
use Contact\Search\Service\ProfileSearchService;
use Event\Search\Service\RegistrationSearchService;
use Invoice\Search\Service\InvoiceSearchService;
use Project\Search\Service\DescriptionSearchService;
use Project\Search\Service\IdeaSearchService;
use Project\Search\Service\ImpactStreamSearchService;
use Project\Search\Service\ProjectSearchService;
use Project\Search\Service\ResultSearchService;
use Project\Search\Service\VersionDocumentSearchService;
use Project\Search\Service\VersionSearchService;
use Project\Search\Service\WorkpackageDocumentSearchService;
use Publication\Search\Service\PublicationSearchService;
use Search\Service\ConsoleService;
use Zend\ServiceManager\AbstractFactory\ConfigAbstractFactory;

return [
    ConfigAbstractFactory::class => [
        // Controllers
        Controller\IndexController::class   => [
            'Config'
        ],
        Controller\ConsoleController::class => [
            ConsoleService::class
        ],
        Service\ConsoleService::class       => [
            ContactSearchService::class,
            ProfileSearchService::class,
            IdeaSearchService::class,
            DescriptionSearchService::class,
            ProjectSearchService::class,
            VersionSearchService::class,
            VersionDocumentSearchService::class,
            WorkpackageDocumentSearchService::class,
            ResultSearchService::class,
            ImpactStreamSearchService::class,
            PublicationSearchService::class,
            InvoiceSearchService::class,
            AffiliationSearchService::class,
            RegistrationSearchService::class
        ],
    ]
];
