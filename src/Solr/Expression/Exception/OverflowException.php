<?php

/**
 * Jield BV all rights reserved
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c) 2021 Jield BV (https://jield.nl)
 */

namespace Search\Solr\Expression\Exception;

use OverflowException as BaseOverflowException;

class OverflowException extends BaseOverflowException implements ExceptionInterface
{
}
