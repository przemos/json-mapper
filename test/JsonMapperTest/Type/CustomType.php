<?php

namespace JsonMapperTest\Type;

/**
 *
 */
class CustomType
{
    const CLASS_PATH = __CLASS__;

    private $internal;

    private function __construct($string)
    {
        $this->internal = $string;
    }

    public static function outerCreate($string)
    {
        return new CustomType($string);
    }

    public static function fromString($string)
    {
        return new CustomType(substr($string, 0, strlen($string) - 4));
    }

    public function toString()
    {
        return $this->internal . "JSON";
    }

    public function getInternal()
    {
        return $this->internal;
    }
}
