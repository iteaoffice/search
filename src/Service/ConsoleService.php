<?php

/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Search
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/main for the canonical source repository
 */

declare(strict_types=1);

namespace Search\Service;

use Affiliation\Service\AffiliationService;
use Calendar\Service\CalendarService;
use Contact\Service\ContactService;
use Event\Service\RegistrationService;
use General\Service\CountryService;
use InvalidArgumentException;
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

/**
 * Class SearchControllerFactory
 *
 * @package Search\Factory
 */
class ConsoleService
{
    private ContactService $contactService;
    private IdeaService $ideaService;
    private DescriptionService $descriptionService;
    private ProjectService $projectService;
    private VersionService $versionService;
    private VersionDocumentService $versionDocumentService;
    private WorkpackageService $workpackageService;
    private ResultService $resultService;
    private ActionService $actionService;
    private PublicationService $publicationService;
    private InvoiceService $invoiceService;
    private AffiliationService $affiliationService;
    private RegistrationService $registrationService;
    private CalendarService $calendarService;
    private NewsService $newsService;
    private BlogService $blogService;
    private PressService $pressService;
    private OrganisationService $organisationService;
    private CountryService $countryService;

    public function __construct(
        ContactService $contactService,
        IdeaService $ideaService,
        DescriptionService $descriptionService,
        ProjectService $projectService,
        VersionService $versionService,
        VersionDocumentService $versionDocumentService,
        WorkpackageService $WorkpackageService,
        ResultService $resultService,
        ActionService $actionService,
        PublicationService $publicationService,
        InvoiceService $invoiceService,
        AffiliationService $affiliationService,
        RegistrationService $registrationService,
        CalendarService $calendarService,
        NewsService $newsService,
        BlogService $blogService,
        PressService $pressService,
        OrganisationService $organisationService,
        CountryService $countryService
    ) {
        $this->contactService = $contactService;
        $this->ideaService = $ideaService;
        $this->descriptionService = $descriptionService;
        $this->projectService = $projectService;
        $this->versionService = $versionService;
        $this->versionDocumentService = $versionDocumentService;
        $this->workpackageService = $WorkpackageService;
        $this->resultService = $resultService;
        $this->actionService = $actionService;
        $this->publicationService = $publicationService;
        $this->invoiceService = $invoiceService;
        $this->affiliationService = $affiliationService;
        $this->registrationService = $registrationService;
        $this->calendarService = $calendarService;
        $this->newsService = $newsService;
        $this->blogService = $blogService;
        $this->pressService = $pressService;
        $this->organisationService = $organisationService;
        $this->countryService = $countryService;
    }

    public function resetIndex(string $index, $clearIndex = false): void
    {
        switch ($index) {
            case 'contact':
                $this->contactService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'profile':
                $this->contactService->updateProfileCollectionInSearchEngine($clearIndex);
                break;
            case 'registration':
                $this->registrationService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'idea':
                $this->ideaService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'roadmap':
                $this->descriptionService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'project':
                $this->projectService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'version':
                $this->versionService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'version-document':
                $this->versionDocumentService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'workpackage-document':
                $this->workpackageService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'result':
                $this->resultService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'action':
                $this->actionService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'publication':
                $this->publicationService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'invoice':
                $this->invoiceService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'calendar':
                $this->calendarService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'news':
                $this->newsService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'blog':
                $this->blogService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'press':
                $this->pressService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'organisation':
                $this->organisationService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'country':
                $this->countryService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'all':
                $this->contactService->updateCollectionInSearchEngine($clearIndex);
                $this->contactService->updateProfileCollectionInSearchEngine($clearIndex);
                $this->registrationService->updateCollectionInSearchEngine($clearIndex);
                $this->ideaService->updateCollectionInSearchEngine($clearIndex);
                $this->descriptionService->updateCollectionInSearchEngine($clearIndex);
                $this->projectService->updateCollectionInSearchEngine($clearIndex);
                $this->versionService->updateCollectionInSearchEngine($clearIndex);
                $this->actionService->updateCollectionInSearchEngine($clearIndex);
                $this->versionDocumentService->updateCollectionInSearchEngine($clearIndex);
                $this->workpackageService->updateCollectionInSearchEngine($clearIndex);
                $this->resultService->updateCollectionInSearchEngine($clearIndex);
                $this->publicationService->updateCollectionInSearchEngine($clearIndex);
                $this->invoiceService->updateCollectionInSearchEngine($clearIndex);
                $this->calendarService->updateCollectionInSearchEngine($clearIndex);
                $this->newsService->updateCollectionInSearchEngine($clearIndex);
                $this->blogService->updateCollectionInSearchEngine($clearIndex);
                $this->pressService->updateCollectionInSearchEngine($clearIndex);
                $this->organisationService->updateCollectionInSearchEngine($clearIndex);
                $this->countryService->updateCollectionInSearchEngine($clearIndex);
                break;
            default:
                throw new InvalidArgumentException(sprintf('%s is incorrect', $index));
        }
    }
}
