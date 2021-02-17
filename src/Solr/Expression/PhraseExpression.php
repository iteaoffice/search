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
 * Class for query phrases
 *
 * Phrases are grouped terms for exact matching in the like of "word1 word2"
 */
class PhraseExpression extends Expression
{
    public function __construct(string $expr)
    {
        $this->expr = $expr;
    }

    public function __toString(): string
    {
        return Util::quote($this->expr);
    }
}
