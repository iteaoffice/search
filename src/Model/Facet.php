<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Search
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (http://itea3.org)
 */

declare(strict_types=1);

namespace Search\Model;

use Solarium\QueryType\Select\Result\Result;

/**
 * Class Facet.
 */
class Facet
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var FacetResult[]
     */
    protected $facetResults;

    /**
     * @return \Search\Model\FacetResult[]
     */
    public function getFacetResults()
    {
        return $this->facetResults;
    }

    /**
     * @param \Search\Model\FacetResult[] $facetResults
     */
    public function setFacetResults(
        $facetResults
    ) {
        $this->facetResults = $facetResults;
    }

    /**
     * @param Result $resultSet
     */
    public function addResults(Result $resultSet): void
    {
        $facetResults = [];
        foreach ($resultSet->getFacetSet()->getFacet($this->getName()) as $name => $count) {
            $facetResult = new FacetResult();
            $facetResult->setName($name);
            $facetResult->setCount($count);
            $facetResults[] = $facetResult;
        }
        $this->setFacetResults($facetResults);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
