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

use Solarium\QueryType\Select\Query\Query;
use Solarium\QueryType\Update\Result;

interface SearchServiceInterface
{
    public function getSolrClient();

    public function clearIndex(bool $optimize = true);

    public function setSearch(
        string $searchTerm,
        array $searchFields = [],
        string $order = '',
        string $direction = Query::SORT_ASC
    ): SearchServiceInterface;

    public function deleteDocument(object $entity, bool $optimize = true): Result;
}
