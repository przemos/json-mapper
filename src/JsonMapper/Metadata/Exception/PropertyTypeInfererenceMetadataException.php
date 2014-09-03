<?php

namespace JsonMapper\Metadata\Exception;

use JsonMapper\Exception\JsonMapperConfigurationExceptionInterface;

/**
 * Thrown when unable to infer type for the property
 */
class PropertyTypeInfererenceMetadataException extends \Exception implements JsonMapperConfigurationExceptionInterface
{
    public function __construct($propertyName)
    {
        parent::__construct("Unable to infer type for property: $propertyName");
    }
}
