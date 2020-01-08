<?php

/**
 * ITEA Office all rights reserved
 *
 * @category  Search
 *
 * @author    Bart van Eijck <bart.van.eijck@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
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
