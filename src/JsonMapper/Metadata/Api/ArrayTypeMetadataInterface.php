<?php

namespace JsonMapper\Metadata\Api;

/**
 * Base interface for all type metadata that has array nature
 */
interface ArrayTypeMetadataInterface extends TypeMetadataInterface, InstantiableTypeInterface
{
    /**
     * Returns type metadata of the array values
     * @return TypeMetadataInterface
     */
    public function getValueTypeMetadata();

    /**
     * Appends value to the array-like structures
     *
     * @param $array
     * @param $value
     *
     */
    public function append(&$array, &$value);
}
