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
 * Range expression class
 *
 * Let you specify range queries in the like of field:[<start> TO <end>] or field:{<start> TO <end>}
 */
class RangeExpression extends Expression
{
    /**
     * Start of the range
     *
     * @var string|int|Expression
     */
    protected $start;

    /**
     * End of the range
     *
     * @var string|int|Expression
     */
    protected $end;

    /**
     * Inclusive or exclusive the range start/end?
     *
     * @var bool
     */
    protected bool $inclusive;

    /**
     * Create new range query object
     *
     * @param null $start
     * @param null $end
     * @param bool $inclusive
     */
    public function __construct($start = null, $end = null, bool $inclusive = true)
    {
        $this->start = $start;
        $this->end = $end;
        $this->inclusive = (bool) $inclusive;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s%s TO %s%s',
            $this->inclusive ? '[' : '{',
            $this->cast($this->start),
            $this->cast($this->end),
            $this->inclusive ? ']' : '}'
        );
    }

    /**
     * @param ExpressionInterface|string|null $value
     * @return ExpressionInterface|string
     */
    private function cast($value)
    {
        return $value === null ? '*' : Util::sanitize($value);
    }
}
