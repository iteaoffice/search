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

use Solarium\Core\Query\AbstractQuery;

/**
 * Interface SearchUpdateInterface
 *
 * @package Application\Service
 */
interface SearchUpdateInterface
{
    public function prepareSearchUpdate($entity): AbstractQuery;

    public function updateCollectionInSearchEngine(bool $clearIndex = false): void;

    public function updateEntityInSearchEngine($entity): void;
}
