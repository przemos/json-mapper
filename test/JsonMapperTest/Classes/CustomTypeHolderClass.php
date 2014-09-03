<?php

namespace JsonMapperTest\Classes;

/**
 *
 */
class CustomTypeHolderClass 
{

    const CLASS_PATH = __CLASS__;

    /** @var  \JsonMapperTest\Type\CustomType */
    private $customType;

    /**
     * @param \JsonMapperTest\Type\CustomType $customType
     *
     * @return $this
     */
    public function setCustomType($customType)
    {
        $this->customType = $customType;
        return $this;
    }

    /**
     * @return \JsonMapperTest\Type\CustomType
     */
    public function getCustomType()
    {
        return $this->customType;
    }
}
