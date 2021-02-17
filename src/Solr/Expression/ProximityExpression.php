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
 * Proximity query class
 *
 * Proximity queries allow to search for two words in a specific distance ("<word1> <word2>"~<proximity>)
 */
class ProximityExpression extends Expression
{
    private array $words;
    private int $proximity;

    /**
     * Create new proximity query object
     *
     * @param string[] $words
     * @param int $proximity
     */
    public function __construct(array $words, int $proximity)
    {
        $this->words     = $words;
        $this->proximity = (int)$proximity;
    }

    public function __toString(): string
    {
        return Util::quote(implode(' ', $this->words)) . '~' . $this->proximity;
    }
}
