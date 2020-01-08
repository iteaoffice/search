<?php
/**
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/project for the canonical source repository
 */

declare(strict_types=1);

namespace Search;

use Affiliation\Service\AffiliationService;
use Calendar\Service\CalendarService;
use Contact\Service\ContactService;
use Event\Service\RegistrationService;
use General\Service\CountryService;
use Invoice\Service\InvoiceService;
use News\Service\BlogService;
use News\Service\NewsService;
use Organisation\Service\OrganisationService;
use Press\Service\PressService;
use Project\Service\ActionService;
use Project\Service\DescriptionService;
use Project\Service\IdeaService;
use Project\Service\ProjectService;
use Project\Service\ResultService;
use Project\Service\VersionDocumentService;
use Project\Service\VersionService;
use Project\Service\WorkpackageService;
use Publication\Service\PublicationService;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;

return [
    ConfigAbstractFactory::class => [
        // Controllers
        Controller\IndexController::class   => [
            'Config'
        ],
        Controller\ConsoleController::class => [
            Service\ConsoleService::class
        ],
        Service\ConsoleService::class       => [
            ContactService::class,
            IdeaService::class,
            DescriptionService::class,
            ProjectService::class,
            VersionService::class,
            VersionDocumentService::class,
            WorkpackageService::class,
            ResultService::class,
            ActionService::class,
            PublicationService::class,
            InvoiceService::class,
            AffiliationService::class,
            RegistrationService::class,
            CalendarService::class,
            NewsService::class,
            BlogService::class,
            PressService::class,
            OrganisationService::class,
            CountryService::class
        ],
    ]
];
