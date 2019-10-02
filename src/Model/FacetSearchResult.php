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

class FacetSearchResult
{
    /**
     * @var int
     */
    protected $numFound;
    /**
     * @var Facet[]
     */
    protected $facets;
    /**
     * @var array
     */
    protected $results;

    /**
     * @return int
     */
    public function getNumFound()
    {
        return $this->numFound;
    }

    /**
     * @param int $numFound
     */
    public function setNumFound($numFound)
    {
        $this->numFound = $numFound;
    }

    /**
     * @return Facet[]
     */
    public function getFacets()
    {
        return $this->facets;
    }

    /**
     * @param Facet[] $facets
     */
    public function setFacets($facets)
    {
        $this->facets = $facets;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param array $results
     */
    public function setResults($results)
    {
        $this->results = $results;
    }
}
