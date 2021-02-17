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
 * Boolean expression class
 *
 * Class to construct bool queries (+<term> or -<term>)
 */
class BooleanExpression extends Expression
{
    public const OPERATOR_REQUIRED   = '+';
    public const OPERATOR_PROHIBITED = '-';

    private string $operator;

    /**
     * Use the NOT notation: (*:* NOT <expr>), e.g. (*:* NOT fieldName:*)
     *
     * @var bool
     */
    private bool $useNotNotation;

    /**
     * Create new expression object
     *
     * @param string $operator
     * @param ExpressionInterface|string $expr
     * @param bool $useNotNotation use the NOT notation: (*:* NOT <expr>), e.g. (*:* NOT fieldName:*)
     */
    public function __construct(string $operator, $expr, bool $useNotNotation = false)
    {
        $this->operator       = $operator;
        $this->useNotNotation = $useNotNotation;

        $this->expr = $expr;
    }

    public function __toString(): string
    {
        return $this->useNotNotation
            ? '(*:* NOT ' . Util::escape($this->expr) . ')'
            : $this->operator . Util::escape($this->expr);
    }
}
