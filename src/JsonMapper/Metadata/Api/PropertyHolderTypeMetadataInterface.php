<?php

namespace JsonMapper\Metadata\Api;

/**
 * Base interface for objects that hold properties
 */
interface PropertyHolderTypeMetadataInterface extends TypeMetadataInterface, InstantiableTypeInterface
{
    /**
     * Returns list of property names
     *
     * @return string[]
     */
    public function getProperties();

    /**
     * Returns true if property exists on the type
     * @param $name
     *
     * @return boolean
     */
    public function hasProperty($name);

    /**
     * @param $propertyName
     *
     * @return \Closure a function f($target) that can be used to get property value
     */
    public function getPropertyAccessor($propertyName);

    /**
     * @param $propertyName
     *
     * @return \Closure a function f($target, $value) that can be used to set property value
     */
    public function getPropertyMutator($propertyName);

    /**
     * @param $propertyName
     *
     * @return TypeMetadataInterface
     */
    public function getPropertyTypeMetadata($propertyName);
}
