<?php

namespace JsonMapperTest\Classes;

/**
 *
 */
class ArrayOfCustomTypeHolderClass
{
    const CLASS_PATH = __CLASS__;

    /** @var  \JsonMapperTest\Type\CustomType[] */
    private $customTypes;

    /**
     * @param \JsonMapperTest\Type\CustomType[] $customTypes
     *
     * @return $this
     */
    public function setCustomTypes($customTypes)
    {
        $this->customTypes = $customTypes;
        return $this;
    }

    /**
     * @return \JsonMapperTest\Type\CustomType[]
     */
    public function getCustomTypes()
    {
        return $this->customTypes;
    }


}
 