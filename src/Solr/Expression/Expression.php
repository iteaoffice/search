<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace Search\Solr\Expression;

use Search\Solr\ExpressionInterface;

/**
 * Base class for expressions
 *
 * The base class for query expressions provides methods to escape and quote query strings as well being the object to
 * create literal queries which should not be escaped
 */
abstract class Expression implements ExpressionInterface
{
    /**
     * Expression object or string
     *
     * @var Expression|string
     */
    protected $expr;

    public function isEqual(string $expr): bool
    {
        return (string)$expr === (string)$this;
    }

    public function __toString(): string
    {
        return (string)$this->expr;
    }
}
