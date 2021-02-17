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
 * Composite expression class
 *
 * Class representing multiple expressions with an optional combination type
 */
class CompositeExpression extends Expression
{
    public const TYPE_AND = 'AND';
    public const TYPE_OR = 'OR';
    public const TYPE_SPACE = ' ';

    private array $expressions;
    private ?string $type;

    /**
     * Create new group of expression
     *
     * @param mixed[] $expressions
     * @param string|null $type
     */
    public function __construct(array $expressions, ?string $type = self::TYPE_SPACE)
    {
        $this->expressions = $expressions;
        $this->type = $type;
    }

    public function __toString(): string
    {
        $parts = [];

        foreach ($this->expressions as $expression) {
            if (! $expression) {
                continue;
            }

            $parts[] = Util::sanitize($expression);
        }

        if (! $parts) {
            return '';
        }

        if ($this->type === static::TYPE_OR || $this->type === static::TYPE_AND) {
            $glue = ' ' . $this->type . ' ';
        } else {
            $glue = $this->type;
        }

        return implode($glue, array_filter($parts));
    }

    public static function isValidType(?string $type): bool
    {
        return $type === static::TYPE_OR
            || $type === static::TYPE_AND
            || $type === static::TYPE_SPACE
            || $type === null;
    }
}
