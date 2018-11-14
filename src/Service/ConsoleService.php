<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Search
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2018 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/main for the canonical source repository
 */

declare(strict_types=1);

namespace Search\Service;

use Affiliation\Service\AffiliationService;
use Calendar\Service\CalendarService;
use Contact\Service\ContactService;
use Contact\Service\ProfileService;
use Event\Service\RegistrationService;
use General\Service\CountryService;
use Invoice\Service\InvoiceService;
use News\Service\NewsService;
use Organisation\Service\OrganisationService;
use Press\Service\PressService;
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
    /**
     * @var ContactService
     */
    private $contactService;
    /**
     * @var IdeaService
     */
    private $ideaService;
    /**
     * @var DescriptionService
     */
    private $descriptionService;
    /**
     * @var ProjectService
     */
    private $projectService;
    /**
     * @var VersionService
     */
    private $versionService;
    /**
     * @var VersionDocumentService
     */
    private $versionDocumentService;
    /**
     * @var WorkpackageService
     */
    private $workpackageService;
    /**
     * @var ResultService
     */
    private $resultService;
    /**
     * @var PublicationService
     */
    private $publicationService;
    /**
     * @var InvoiceService
     */
    private $invoiceService;
    /**
     * @var AffiliationService
     */
    private $affiliationService;
    /**
     * @var RegistrationService
     */
    private $registrationService;
    /**
     * @var CalendarService
     */
    private $calendarService;
    /**
     * @var NewsService
     */
    private $newsService;
    /**
     * @var PressService
     */
    private $pressService;
    /**
     * @var OrganisationService
     */
    private $organisationService;
    /**
     * @var CountryService
     */
    private $countryService;

    public function __construct(
        ContactService $contactService,
        IdeaService $ideaService,
        DescriptionService $descriptionService,
        ProjectService $projectService,
        VersionService $versionService,
        VersionDocumentService $versionDocumentService,
        WorkpackageService $WorkpackageService,
        ResultService $resultService,
        PublicationService $publicationService,
        InvoiceService $invoiceService,
        AffiliationService $affiliationService,
        RegistrationService $registrationService,
        CalendarService $calendarService,
        NewsService $newsService,
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
        $this->publicationService = $publicationService;
        $this->invoiceService = $invoiceService;
        $this->affiliationService = $affiliationService;
        $this->registrationService = $registrationService;
        $this->calendarService = $calendarService;
        $this->newsService = $newsService;
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
            case 'publication':
                $this->publicationService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'invoice':
                $this->invoiceService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'affiliation':
                $this->affiliationService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'calendar':
                $this->calendarService->updateCollectionInSearchEngine($clearIndex);
                break;
            case 'news':
                $this->newsService->updateCollectionInSearchEngine($clearIndex);
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
                $this->versionDocumentService->updateCollectionInSearchEngine($clearIndex);
                $this->workpackageService->updateCollectionInSearchEngine($clearIndex);
                $this->resultService->updateCollectionInSearchEngine($clearIndex);
                $this->publicationService->updateCollectionInSearchEngine($clearIndex);
                $this->invoiceService->updateCollectionInSearchEngine($clearIndex);
                $this->affiliationService->updateCollectionInSearchEngine($clearIndex);
                $this->calendarService->updateCollectionInSearchEngine($clearIndex);
                $this->newsService->updateCollectionInSearchEngine($clearIndex);
                $this->pressService->updateCollectionInSearchEngine($clearIndex);
                $this->organisationService->updateCollectionInSearchEngine($clearIndex);
                $this->countryService->updateCollectionInSearchEngine($clearIndex);
                break;
            default:
                throw new \InvalidArgumentException(sprintf("%s is incorrect", $index));
        }
    }

    public function updateRegistrationByMeeting(int $meetingId, bool $clearIndex = false): void
    {
        $this->registrationService->updateRegistrationByMeeting($meetingId, $clearIndex);
    }
}
