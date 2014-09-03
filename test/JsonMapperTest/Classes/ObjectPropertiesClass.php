<?php

namespace JsonMapperTest\Classes;

/**
 *
 */
class ObjectPropertiesClass 
{
    const CLASS_PATH = __CLASS__;

    private $primitive;

    /**
     * @param \JsonMapperTest\Classes\PrimitivePropertiesClass $primitive
     *
     * @return $this
     */
    public function setPrimitive($primitive)
    {
        $this->primitive = $primitive;
        return $this;
    }

    /**
     * @return \JsonMapperTest\Classes\PrimitivePropertiesClass
     */
    public function getPrimitive()
    {
        return $this->primitive;
    }
}