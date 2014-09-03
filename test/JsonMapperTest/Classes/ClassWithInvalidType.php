<?php

namespace JsonMapperTest\Classes;

/**
 *
 */
class ClassWithInvalidType
{
    const CLASS_PATH = __CLASS__;
    /**
     * @param invalidType $propValue
     * @return $this
     */
    public function setInvalidProperty($propValue)
    {
        return $this;
    }

    public function getInvalidProperty()
    {
        return null;
    }
}
