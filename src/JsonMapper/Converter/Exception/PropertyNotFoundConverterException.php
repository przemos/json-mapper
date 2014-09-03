<?php

namespace JsonMapper\Converter\Exception;

/**
 * Thrown when JSON property cannot be mapped to its equivalent in the object model
 */
class PropertyNotFoundConverterException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Property not found");
    }
}
