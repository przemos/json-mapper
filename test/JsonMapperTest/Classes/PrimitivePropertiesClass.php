<?php

namespace JsonMapperTest\Classes;


class PrimitivePropertiesClass
{
    const CLASS_PATH = __CLASS__;

    /** @var boolean */
    private $boolProperty;

    /** @var  integer */
    private $intProperty;

    /** @var  string */
    private $stringProperty;

    /** @var  double */
    private $doubleProperty;

    /**
     * @param boolean $boolProperty
     *
     * @return $this
     */
    public function setBoolProperty($boolProperty)
    {
        $this->boolProperty = $boolProperty;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getBoolProperty()
    {
        return $this->boolProperty;
    }

    /**
     * @param integer $intProperty
     *
     * @return $this
     */
    public function setIntProperty($intProperty)
    {
        $this->intProperty = $intProperty;
        return $this;
    }

    /**
     * @return int
     */
    public function getIntProperty()
    {
        return $this->intProperty;
    }

    /**
     * @param string $stringProperty
     *
     * @return $this
     */
    public function setStringProperty($stringProperty)
    {
        $this->stringProperty = $stringProperty;
        return $this;
    }

    /**
     * @return string
     */
    public function getStringProperty()
    {
        return $this->stringProperty;
    }

    /**
     * @param double $doubleProperty
     *
     * @return $this
     */
    public function setDoubleProperty($doubleProperty)
    {
        $this->doubleProperty = $doubleProperty;
        return $this;
    }

    /**
     * @return double
     */
    public function getDoubleProperty()
    {
        return $this->doubleProperty;
    }
}
