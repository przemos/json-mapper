<?php

namespace JsonMapperTest\Classes;

class PrimitiveArrayPropertiesClass
{
    const CLASS_PATH = __CLASS__;

    /** @var  integer[] */
    private $integers;

    /** @var  double[] */
    private $doubles;

    /** @var boolean[] */
    private $booleans;

    /** @var  string[] */
    private $strings;

    /**
     * @param boolean[] $booleans
     *
     * @return $this
     */
    public function setBooleans($booleans)
    {
        $this->booleans = $booleans;
        return $this;
    }

    /**
     * @return boolean[]
     */
    public function getBooleans()
    {
        return $this->booleans;
    }

    /**
     * @param double[] $doubles
     *
     * @return $this
     */
    public function setDoubles($doubles)
    {
        $this->doubles = $doubles;
        return $this;
    }

    /**
     * @return float[]
     */
    public function getDoubles()
    {
        return $this->doubles;
    }

    /**
     * @param integer[] $integers
     *
     * @return $this
     */
    public function setIntegers($integers)
    {
        $this->integers = $integers;
        return $this;
    }

    /**
     * @return integer[]
     */
    public function getIntegers()
    {
        return $this->integers;
    }

    /**
     * @param string[] $strings
     *
     * @return $this
     */
    public function setStrings($strings)
    {
        $this->strings = $strings;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getStrings()
    {
        return $this->strings;
    }
}
