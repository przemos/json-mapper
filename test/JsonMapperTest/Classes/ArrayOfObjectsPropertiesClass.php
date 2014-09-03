<?php

namespace JsonMapperTest\Classes;

/**
 *
 */
class ArrayOfObjectsPropertiesClass
{
    const CLASS_PATH = __CLASS__;

    /** @var  \JsonMapperTest\Classes\PrimitivePropertiesClass[] */
    private $primitives;

    /**
     * @param \JsonMapperTest\Classes\PrimitivePropertiesClass[] $primitives
     *
     * @return $this
     */
    public function setPrimitives($primitives)
    {
        $this->primitives = $primitives;
        return $this;
    }

    /**
     * @return \JsonMapperTest\Classes\PrimitivePropertiesClass[]
     */
    public function getPrimitives()
    {
        return $this->primitives;
    }
}