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
 * Field query expression
 *
 * Class representing a query limited to specific fields (field:<value>)
 */
class FieldExpression extends Expression
{
    /**
     * Field name
     *
     * @var string
     */
    private string $field;

    /**
     * Create new field query
     *
     * @param string $field
     * @param string|Expression $expr
     */
    public function __construct(string $field, $expr)
    {
        $this->field = $field;

        $this->expr = $expr;
    }

    public function __toString(): string
    {
        $field      = Util::escape($this->field);
        $expression = Util::sanitize($this->expr);

        if ($this->expr instanceof LocalParamsExpression) {
            return $expression . $field;
        }

        return $field . ':' . $expression;
    }
}
