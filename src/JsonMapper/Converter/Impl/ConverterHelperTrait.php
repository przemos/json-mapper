<?php

namespace JsonMapper\Converter\Impl;


use JsonMapper\Converter\Exception\ConversionFailedConverterException;

trait ConverterHelperTrait
{
    protected function assertJsonToComplexPossible($expectedType, &$jsonStruct)
    {
        if (!is_array($jsonStruct)) {
            throw new ConversionFailedConverterException($expectedType, $jsonStruct);
        }
    }
}
