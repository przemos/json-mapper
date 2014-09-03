<?php

namespace JsonMapper\Metadata\Impl;

use JsonMapper\Metadata\Api\AbstractPropertyHolderTypeMetadata;
use JsonMapper\Metadata\Api\MetadataProviderInterface;
use JsonMapper\Metadata\Exception\PropertyTypeInfererenceMetadataException;

/**
 * PropertyTypeHolder based on class fields annotation rather than setters and getters
 */
class ClassFieldPropertyHolderTypeMetadata extends AbstractPropertyHolderTypeMetadata
{
    /**
     * @param $propertyName
     *
     * @return \Closure a function f($target) that can be used to get property value
     */
    public function getPropertyAccessor($propertyName)
    {
        return function ($object) use (&$propertyName) {
            return (new \ReflectionClass($this->getType()))->getProperty($propertyName)->getValue($object);
        };
    }

    /**
     * @param $propertyName
     *
     * @return \Closure a function f($target, $value) that can be used to set property value
     */
    public function getPropertyMutator($propertyName)
    {
        return function ($object, $value) use (&$propertyName) {
            (new \ReflectionClass($this->getType()))->getProperty($propertyName)->setValue($object, $value);
        };
    }

    protected function resolvePropertyMetadata(
        MetadataProviderInterface $metadataProvider,
        \ReflectionClass $objectClass
    ) {
        $properties = [];
        /** @var \ReflectionProperty[] $propertyFields */
        $propertyFields = array_filter(
            $objectClass->getProperties(\ReflectionProperty::IS_PUBLIC),
            function (\ReflectionProperty $property) {
                return !$property->isStatic();
            }
        );

        foreach ($propertyFields as $propField) {
            $propName = $propField->getName();
            $type = self::parseType($propField);
            if ($type === null) {
                throw new PropertyTypeInfererenceMetadataException($propName);
            }
            $typeMetadata = $metadataProvider->getTypeMetadata($type);
            $properties[$propName] = $typeMetadata;
        }

        return $properties;
    }


    private static function parseType(\ReflectionProperty $propMethod)
    {
        $annotations = self::parseAnnotations($propMethod->getDocComment());
        if (!isset($annotations['var'][0])) {
            return null;
        }
        list($type) = explode(' ', $annotations['var'][0]);

        return $type;
    }
}
