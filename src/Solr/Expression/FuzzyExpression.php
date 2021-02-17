<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace Search\Solr\Expression;

use Search\Solr\Util;

/**
 * Class for fuzzy query expressions
 */
class FuzzyExpression extends Expression
{
    /**
     * Similarity (0.0 to 1.0)
     *
     * @var float
     */
    private float $similarity;

    /**
     * Create new fuzzy query object
     *
     * @param string|Expression $expr
     * @param float|null $similarity
     */
    public function __construct($expr, ?float $similarity = null)
    {
        $this->expr = $expr;

        if ($similarity !== null) {
            $this->similarity = (float)$similarity;
        }
    }

    public function __toString(): string
    {
        return Util::escape($this->expr) . '~' . $this->similarity;
    }
}
