<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace Search\Solr\Expression;

use Search\Solr\ExpressionInterface;
use Search\Solr\Util;

/**
 * Class representing boosted queries
 *
 * Class to construct boosted queries in the like of <term>^<boost>
 */
class BoostExpression extends Expression
{
    private float $boost;

    /**
     * @param float $boost
     * @param ExpressionInterface|string|null $expr
     */
    public function __construct(float $boost, $expr)
    {
        $this->boost = $boost;
        $this->expr  = $expr;
    }

    public function __toString(): string
    {
        return Util::sanitize($this->expr) . '^' . $this->boost;
    }
}
