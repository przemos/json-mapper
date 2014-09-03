<?php

namespace JsonMapper\Converter\Exception;

use JsonMapper\Exception\JsonMapperConfigurationExceptionInterface;

/**
 * Thrown when unsupported type has been found
 */
class UnsupportedTypeMetadataException extends \Exception implements JsonMapperConfigurationExceptionInterface
{
    public function __construct($type)
    {
        parent::__construct("Unsupported type $type");
    }
}
