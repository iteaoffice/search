<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace Search\Solr\Expression;

use Search\Solr\ExpressionInterface;

class FunctionExpression extends Expression
{
    /**
     * @var string
     */
    private $function;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @param Expression|string $function
     * @param ExpressionInterface|array|null $parameters
     */
    public function __construct($function, $parameters = null)
    {
        $this->function = $function;
        $this->parameters = $parameters;
    }

    public function __toString(): string
    {
        $parameters = $this->parameters ?: null;

        if ($parameters && ! $parameters instanceof ParameterExpression) {
            $parameters = new ParameterExpression($parameters);
        }

        return $this->function . '(' . $parameters . ')';
    }
}
