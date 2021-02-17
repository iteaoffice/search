<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace Search\Solr\Expression;

use DateTime;
use DateTimeZone;

class DateTimeExpression extends Expression
{
    public const FORMAT_DEFAULT      = 'Y-m-d\TH:i:s\Z';
    public const FORMAT_START_OF_DAY = 'Y-m-d\T00:00:00\Z';
    public const FORMAT_END_OF_DAY   = 'Y-m-d\T23:59:59\Z';

    private static ?DateTimeZone $utcTimezone = null;
    private DateTime $date;
    private string $timezone;
    private string $format;

    public function __construct(DateTime $date, ?string $format = null, string $timezone = 'UTC')
    {
        $this->date     = clone $date;
        $this->format   = $format ?: static::FORMAT_DEFAULT;
        $this->timezone = $timezone;
    }

    public function __toString(): string
    {
        $date = $this->date;

        if ($this->timezone === 'UTC') {
            if (! self::$utcTimezone) {
                self::$utcTimezone = new DateTimeZone('UTC');
            }
            $date = $date->setTimeZone(self::$utcTimezone);
        } elseif ($this->timezone !== null) {
            if ($this->timezone instanceof DateTimeZone) {
                $date = $date->setTimeZone($this->timezone);
            } else {
                $date = $date->setTimeZone(new DateTimeZone($this->timezone));
            }
        }

        return $date->format($this->format);
    }
}
