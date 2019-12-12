<?php
/**
 * ITEA Office all rights reserved
 *
 * @category   Search
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2017 ITEA Office (http://itea3.org)
 */

declare(strict_types=1);

namespace Search\Paginator\Adapter;

use Solarium\Client;
use Solarium\QueryType\Select\Query\Query;
use Solarium\QueryType\Select\Result\Result;
use Zend\Paginator\Adapter\AdapterInterface;

/**
 * Class SolariumPaginator
 * @package Search\Paginator\Adapter
 */
final class SolariumPaginator implements AdapterInterface
{
    protected Client $client;
    protected Query $query;
    protected ? int $count = null;

    public function __construct(Client $client, Query $query)
    {
        $this->client = $client;
        $this->query = $query;
    }

    public function count() : ?int
    {
        if (null === $this->count) {
            $this->getItems(0, 0);
        }

        return $this->count;
    }

    public function getItems($offset, $itemCountPerPage): Result
    {
        $this->query->setStart($offset);
        $this->query->setRows($itemCountPerPage);
        $result = $this->client->select($this->query);
        $this->count = $result->getNumFound();

        return $result;
    }
}
