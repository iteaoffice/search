<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace Search\Service;

use Calendar\Service\CalendarService;
use Contact\Service\ContactService;
use Event\Service\RegistrationService;
use General\Service\CountryService;
use InvalidArgumentException;
use Invoice\Service\InvoiceService;
use News\Service\BlogService;
use News\Service\NewsService;
use Organisation\Service\AdvisoryBoard\CityService;
use Organisation\Service\AdvisoryBoard\SolutionService;
use Organisation\Service\AdvisoryBoard\TenderService;
use Organisation\Service\OrganisationService;
use Press\Service\PressService;
use Project\Service\Achievement\ExploitableResultService;
use Project\Service\AchievementService;
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
    private AchievementService $achievementService;
    private ExploitableResultService $exploitableResultService;
    private PublicationService $publicationService;
    private InvoiceService $invoiceService;
    private RegistrationService $registrationService;
    private CalendarService $calendarService;
    private NewsService $newsService;
    private BlogService $blogService;
    private PressService $pressService;
    private OrganisationService $organisationService;
    private CityService $cityService;
    private TenderService $tenderService;
    private SolutionService $solutionService;
    private CountryService $countryService;

    public function __construct(ContactService $contactService, IdeaService $ideaService, DescriptionService $descriptionService, ProjectService $projectService, VersionService $versionService, VersionDocumentService $versionDocumentService, WorkpackageService $workpackageService, ResultService $resultService, ActionService $actionService, AchievementService $achievementService, ExploitableResultService $exploitableResultService, PublicationService $publicationService, InvoiceService $invoiceService, RegistrationService $registrationService, CalendarService $calendarService, NewsService $newsService, BlogService $blogService, PressService $pressService, OrganisationService $organisationService, CityService $cityService, TenderService $tenderService, SolutionService $solutionService, CountryService $countryService)
    {
        $this->contactService           = $contactService;
        $this->ideaService              = $ideaService;
        $this->descriptionService       = $descriptionService;
        $this->projectService           = $projectService;
        $this->versionService           = $versionService;
        $this->versionDocumentService   = $versionDocumentService;
        $this->workpackageService       = $workpackageService;
        $this->resultService            = $resultService;
        $this->actionService            = $actionService;
        $this->achievementService       = $achievementService;
        $this->exploitableResultService = $exploitableResultService;
        $this->publicationService       = $publicationService;
        $this->invoiceService           = $invoiceService;
        $this->registrationService      = $registrationService;
        $this->calendarService          = $calendarService;
        $this->newsService              = $newsService;
        $this->blogService              = $blogService;
        $this->pressService             = $pressService;
        $this->organisationService      = $organisationService;
        $this->cityService              = $cityService;
        $this->tenderService            = $tenderService;
        $this->solutionService          = $solutionService;
        $this->countryService           = $countryService;
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
            case 'achievement':
                $this->achievementService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'exploitable-result':
                $this->exploitableResultService->updateCollectionInSearchEngine($clearIndex);
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
            case 'city':
                $this->cityService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'tender':
                $this->tenderService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'solution':
                $this->solutionService->updateCollectionInSearchEngine($clearIndex);
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
                $this->achievementService->updateCollectionInSearchEngine($clearIndex);
                $this->exploitableResultService->updateCollectionInSearchEngine($clearIndex);
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
                $this->cityService->updateCollectionInSearchEngine($clearIndex);
                $this->tenderService->updateCollectionInSearchEngine($clearIndex);
                $this->countryService->updateCollectionInSearchEngine($clearIndex);
                $this->countryService->updateCollectionInSearchEngine($clearIndex);
                break;
            default:
                throw new InvalidArgumentException(sprintf('%s is incorrect', $index));
        }
    }
}
