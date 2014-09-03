<?php

namespace JsonMapperTest\Classes;

/**
 *
 */
class ClassHavingClassWithInvalidType 
{
    const CLASS_PATH = __CLASS__;

    private $prop;
    /**
     * @param ClassWithInvalidType $invalidType
     * @return $this
     */
    public function setObjectWithInvalidType(ClassWithInvalidType $invalidType)
    {
        $this->prop = $invalidType;
        return $this;
    }

    /**
     * @return ClassWithInvalidType
     */
    public function getObjectWithInvalidType()
    {
        return $this->prop;
    }
}
 