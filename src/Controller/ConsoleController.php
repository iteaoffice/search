<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace Search\Controller;

use Search\Service\ConsoleService;
use Laminas\Mvc\Controller\AbstractActionController;

/**
 * final class ConsoleController
 *
 * @package Search\Controller
 */
final class ConsoleController extends AbstractActionController
{
    private ConsoleService $consoleService;

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
