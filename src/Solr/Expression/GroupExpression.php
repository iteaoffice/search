<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace Search\Solr\Expression;

/**
 * Group expression class
 *
 * Class representing expressions grouped together in the like of (term1 term2).
 */
class GroupExpression extends CompositeExpression
{
    public function __toString(): string
    {
        $part = parent::__toString();

        if (! $part) {
            return $part;
        }

        return '(' . $part . ')';
    }
}
