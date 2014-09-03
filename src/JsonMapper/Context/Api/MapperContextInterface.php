<?php

namespace JsonMapper\Context\Api;

use JsonMapper\Converter\Api\ConverterRegistryInterface;

/**
 * Provides access to mapping process context
 */
interface MapperContextInterface
{

    /**
     * @return ConverterRegistryInterface
     */
    public function getConverterRegistry();

    /**
     * Pushes new property to the context
     *
     * @param string $element
     */
    public function pushPathProperty($element);

    /**
     * Pushes new property index to the context
     *
     * @param $index
     *
     * @return mixed
     */
    public function pushPathIndex($index);

    /**
     * Pops current context
     */
    public function pathPop();

    /**
     * Returns current context as a string
     *
     * @return string
     */
    public function getContextPath();
}
