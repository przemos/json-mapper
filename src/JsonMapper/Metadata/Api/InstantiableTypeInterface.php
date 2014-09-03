<?php

namespace JsonMapper\Metadata\Api;

/**
 * Interface for type metadata that is able to instantiate the type it represent
 */
interface InstantiableTypeInterface
{
    /**
     * Instantiates object of the type represented by implementing type metadata
     *
     * @return object
     */
    public function instantiate();
}
