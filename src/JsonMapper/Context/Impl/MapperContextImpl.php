<?php

namespace JsonMapper\Context\Impl;

use JsonMapper\Context\Api\MapperContextInterface;
use JsonMapper\Converter\Api\ConverterRegistryInterface;

/**
 * Internal implementation of mapper context
 */
class MapperContextImpl implements MapperContextInterface
{
    private $converterRegistry;
    private $context = [];

    public function __construct(
        ConverterRegistryInterface $converterRegistry
    ) {
        $this->converterRegistry = $converterRegistry;
    }

    /**
     * @return ConverterRegistryInterface
     */
    public function getConverterRegistry()
    {
        return $this->converterRegistry;
    }

    /**
     * Pushes new element to the context
     *
     * @param string $element
     */
    public function pushPathProperty($element)
    {
        array_push($this->context, $element);
    }

    /**
     * @param string|int $index
     *
     * @return mixed|void
     */
    public function pushPathIndex($index)
    {
        array_push($this->context, "[$index]");
    }

    /**
     * Pops current context
     */
    public function pathPop()
    {
        array_pop($this->context);
    }

    /**
     * Returns current context as string
     *
     * @return string
     */
    public function getContextPath()
    {
        return str_replace('.[', '[', '::' . implode('.', $this->context));
    }
}
