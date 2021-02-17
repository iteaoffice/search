<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace Search\Solr\Query;

use DateTime;
use Search\Solr\Expression\DateTimeExpression;
use Search\Solr\Expression\GroupExpression;
use Search\Solr\Util;

class QueryString
{
    private string $query;
    private array $placeholders = [];

    public function __construct(string $query)
    {
        $this->query = $query;
    }

    /**
     * Add a value for a placeholder
     *
     * @param mixed $value
     */
    public function setPlaceholder(string $placeholder, $value): self
    {
        $this->placeholders[$placeholder] = $value;

        return $this;
    }

    /**
     * Add values for several placeholders as key => value pairs
     *
     * @param mixed[] $placeholders
     */
    public function setPlaceholders(array $placeholders): self
    {
        $this->placeholders = $placeholders;

        return $this;
    }

    /** Return string representation */
    public function __toString(): string
    {
        $replacements = [];

        foreach ($this->placeholders as $placeholder => $value) {
            if ($value instanceof DateTime) {
                $value = new DateTimeExpression($value);
            } elseif (is_array($value)) {
                $value = new GroupExpression($value);
            } elseif (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            } else {
                $value = Util::sanitize($value);
            }

            $replacements['<' . $placeholder . '>'] = (string)$value;
        }

        return strtr($this->query, $replacements);
    }
}
