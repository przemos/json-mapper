<?php

namespace JsonMapperTest\Classes;

/**
 *
 */
class UninstatiableClass
{
    const CLASS_PATH = __CLASS__;

    private function __construct()
    {

    }
}
 