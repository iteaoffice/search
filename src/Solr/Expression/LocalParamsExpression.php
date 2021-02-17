<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace Search\Solr\Expression;

use Search\Solr\Util;

class LocalParamsExpression extends Expression
{
    private $type;
    private array $params;
    private bool $shortForm = true;

    /**
     * @param Expression|string $type
     * @param mixed[] $params
     * @param bool $shortForm
     */
    public function __construct($type, array $params = [], bool $shortForm = true)
    {
        $this->type = $type;
        $this->params = $params;
        $this->shortForm = $shortForm;
    }

    public function __toString(): string
    {
        $typeString = $this->shortForm ? $this->type : 'type=' . $this->type;
        $paramsString = $this->buildParamString();

        return '{!' . $typeString . $paramsString . '}';
    }

    private function buildParamString(): string
    {
        if ($this->shortForm && count($this->params) === 1 && key($this->params) === $this->type) {
            return '=' . Util::sanitize(current($this->params));
        }

        $paramsString = '';

        foreach ($this->params as $key => $value) {
            $paramsString .= ' ' . $key . '=' . Util::sanitize($value);
        }

        return $paramsString;
    }
}
