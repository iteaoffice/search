<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Search
 *
 * @author    Bart van Eijck <bart.van.eijck@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace Search\Service;

use Solarium\Client;
use Solarium\Core\Query\Result\ResultInterface;
use Solarium\QueryType\Select\Query\Query;

interface SearchServiceInterface
{
    /**
     * The search service.
     *
     * @param Client $solrClient
     */
    public function setSolrClient(Client $solrClient);

    /**
     * Get search service.
     *
     * @return Client
     */
    public function getSolrClient();

    /**
     * Clear the current index
     */
    public function clearIndex();

    /**
     * Set the search params and prepare Solarium
     *
     * @param string $searchTerm
     * @param array $searchFields
     * @param string $order
     * @param string $direction
     *
     * @return SearchServiceInterface
     */
    public function setSearch(
        string $searchTerm,
        array $searchFields = [],
        string $order = '',
        string $direction = Query::SORT_ASC
    ): SearchServiceInterface;

    /**
     * Insert/update a document
     *
     * @param object $entity
     *
     * @return ResultInterface
     */
    public function updateDocument($entity);

    /**
     * Delete a document
     *
     * @param object $entity
     *
     * @return ResultInterface
     */
    public function deleteDocument($entity);

    /**
     * Insert/update a full index, optionally clearing the index first
     *
     * @param bool $clear
     */
    public function updateIndex($clear = false);
}
