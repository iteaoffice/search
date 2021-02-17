<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace Search\Solr\Expression;

/**
 * Class LitExpression
 * @package Search\Solr\Expression
 */
class LitExpression extends Expression
{
    public function __construct(string $expr)
    {
        $this->expr = $expr;
    }

    public function __toString(): string
    {
        return $this->expr;
    }
}
