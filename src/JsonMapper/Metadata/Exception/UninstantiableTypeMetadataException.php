<?php

namespace JsonMapper\Metadata\Exception;

use JsonMapper\Exception\JsonMapperConfigurationExceptionInterface;

/**
 * Thrown when the type cannot be instantiate
 */
class UninstantiableTypeMetadataException extends \Exception implements JsonMapperConfigurationExceptionInterface
{
    public function __construct($type)
    {
        parent::__construct("Unable to instantiate type: $type");
    }
}
