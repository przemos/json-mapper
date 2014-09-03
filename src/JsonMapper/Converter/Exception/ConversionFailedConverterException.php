<?php

namespace JsonMapper\Converter\Exception;

/**
 * Thrown when a value cannot be mapped to requested type
 */
class ConversionFailedConverterException extends \Exception
{
    /**
     * @param string $expectedType
     * @param object $value
     */
    public function __construct($expectedType, $value)
    {
        $valueString = print_r($value, true);
        parent::__construct("Conversion failed for \"$valueString\" to type $expectedType");
    }
}
