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
