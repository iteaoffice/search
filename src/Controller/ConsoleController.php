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

namespace Search\Controller;

use Search\Service\ConsoleService;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * final class ConsoleController
 *
 * @package Search\Controller
 */
final class ConsoleController extends AbstractActionController
{
    /**
     * @var ConsoleService
     */
    private $consoleService;

    public function __construct(ConsoleService $consoleService)
    {
        $this->consoleService = $consoleService;
    }

    public function searchUpdateAction(): void
    {
        $this->consoleService->resetIndex((string)$this->params('entity'));
    }

    public function searchResetAction(): void
    {
        $this->consoleService->resetIndex((string)$this->params('entity'), true);
    }
}
