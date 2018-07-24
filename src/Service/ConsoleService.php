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

/**
 * Class SearchControllerFactory
 *
 * @package Search\Factory
 */
class ConsoleService
{
    /**
     * @var ContactSearchService
     */
    private $contactSearchService;
    /**
     * @var ProfileSearchService
     */
    private $profileSearchService;
    /**
     * @var IdeaSearchService
     */
    private $ideaSearchService;
    /**
     * @var DescriptionSearchService
     */
    private $descriptionSearchService;
    /**
     * @var ProjectSearchService
     */
    private $projectSearchService;
    /**
     * @var VersionSearchService
     */
    private $versionSearchService;
    /**
     * @var VersionDocumentSearchService
     */
    private $versionDocumentSearchService;
    /**
     * @var WorkpackageDocumentSearchService
     */
    private $workpackageDocumentSearchService;
    /**
     * @var ResultSearchService
     */
    private $resultSearchService;
    /**
     * @var ImpactStreamSearchService
     */
    private $impactStreamSearchService;
    /**
     * @var PublicationSearchService
     */
    private $publicationSearchService;
    /**
     * @var InvoiceSearchService
     */
    private $invoiceSearchService;
    /**
     * @var AffiliationSearchService
     */
    private $affiliationSearchService;
    /**
     * @var RegistrationSearchService
     */
    private $registrationSearchService;

    public function __construct(
        ContactSearchService $contactSearchService,
        ProfileSearchService $profileSearchService,
        IdeaSearchService $ideaSearchService,
        DescriptionSearchService $descriptionSearchService,
        ProjectSearchService $projectSearchService,
        VersionSearchService $versionSearchService,
        VersionDocumentSearchService $versionDocumentSearchService,
        WorkpackageDocumentSearchService $workpackageDocumentSearchService,
        ResultSearchService $resultSearchService,
        ImpactStreamSearchService $impactStreamSearchService,
        PublicationSearchService $publicationSearchService,
        InvoiceSearchService $invoiceSearchService,
        AffiliationSearchService $affiliationSearchService,
        RegistrationSearchService $registrationSearchService
    ) {
        $this->contactSearchService = $contactSearchService;
        $this->profileSearchService = $profileSearchService;
        $this->ideaSearchService = $ideaSearchService;
        $this->descriptionSearchService = $descriptionSearchService;
        $this->projectSearchService = $projectSearchService;
        $this->versionSearchService = $versionSearchService;
        $this->versionDocumentSearchService = $versionDocumentSearchService;
        $this->workpackageDocumentSearchService = $workpackageDocumentSearchService;
        $this->resultSearchService = $resultSearchService;
        $this->impactStreamSearchService = $impactStreamSearchService;
        $this->publicationSearchService = $publicationSearchService;
        $this->invoiceSearchService = $invoiceSearchService;
        $this->affiliationSearchService = $affiliationSearchService;
        $this->registrationSearchService = $registrationSearchService;
    }

    public function resetIndex(string $index, $clearIndex = false): void
    {
        switch ($index) {
            case 'contact':
                $this->contactSearchService->updateIndex($clearIndex);
                break;
            case 'profile':
                $this->profileSearchService->updateIndex($clearIndex);
                break;
            case 'registration':
                $this->registrationSearchService->updateIndex($clearIndex);
                break;
            case 'idea':
                $this->ideaSearchService->updateIndex($clearIndex);
                break;
            case 'roadmap':
                $this->descriptionSearchService->updateIndex($clearIndex);
                break;
            case 'project':
                $this->projectSearchService->updateIndex($clearIndex);
                break;
            case 'version':
                $this->versionSearchService->updateIndex($clearIndex);
                break;
            case 'version-document':
                $this->versionDocumentSearchService->updateIndex($clearIndex);
                break;
            case 'workpackage-document':
                $this->workpackageDocumentSearchService->updateIndex($clearIndex);
                break;
            case 'result':
                $this->resultSearchService->updateIndex($clearIndex);
                break;
            case 'impact-stream':
                $this->impactStreamSearchService->updateIndex($clearIndex);
                break;
            case 'publication':
                $this->publicationSearchService->updateIndex($clearIndex);
                break;
            case 'invoice':
                $this->invoiceSearchService->updateIndex($clearIndex);
                break;
            case 'affiliation':
                $this->affiliationSearchService->updateIndex($clearIndex);
                break;
            case 'all':
                $this->contactSearchService->updateIndex($clearIndex);
                $this->profileSearchService->updateIndex($clearIndex);
                $this->registrationSearchService->updateIndex($clearIndex);
                $this->ideaSearchService->updateIndex($clearIndex);
                $this->descriptionSearchService->updateIndex($clearIndex);
                $this->projectSearchService->updateIndex($clearIndex);
                $this->versionSearchService->updateIndex($clearIndex);
                $this->versionDocumentSearchService->updateIndex($clearIndex);
                $this->workpackageDocumentSearchService->updateIndex($clearIndex);
                $this->resultSearchService->updateIndex($clearIndex);
                $this->impactStreamSearchService->updateIndex($clearIndex);
                $this->publicationSearchService->updateIndex($clearIndex);
                $this->invoiceSearchService->updateIndex($clearIndex);
                $this->affiliationSearchService->updateIndex($clearIndex);
                break;
            default:
                throw new \InvalidArgumentException(sprintf("%s is incorrect", $index));
        }
    }

    public function updateRegistrationByMeeting(int $meetingId, bool $clearIndex = false): void
    {
        $this->registrationSearchService->updateRegistrationByMeeting($meetingId, $clearIndex);
    }
}
