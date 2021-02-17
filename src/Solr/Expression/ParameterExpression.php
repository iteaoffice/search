<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace Search\Solr\Expression;

use Search\Solr\Util;

class ParameterExpression extends Expression
{
    private array $parameters;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function __toString(): string
    {
        $parameters = array_map([$this, 'replaceNull'], $this->parameters);

        return implode(', ', array_map([Util::class, 'sanitize'], $parameters));
    }

    /**
     * @param mixed $value
     * @return PhraseExpression|mixed
     */
    private function replaceNull($value): PhraseExpression
    {
        return $value ?? new PhraseExpression('');
    }
}
