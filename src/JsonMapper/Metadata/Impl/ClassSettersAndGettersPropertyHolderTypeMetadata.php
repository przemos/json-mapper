<?php

namespace JsonMapper\Metadata\Impl;

use JsonMapper\Metadata\Api\AbstractPropertyHolderTypeMetadata;
use JsonMapper\Metadata\Api\MetadataProviderInterface;
use JsonMapper\Metadata\Exception\PropertyTypeInfererenceMetadataException;

/**
 * Default implementation for objects that are built of properties
 */
class ClassSettersAndGettersPropertyHolderTypeMetadata extends AbstractPropertyHolderTypeMetadata
{
    /**
     * @param $propertyName
     *
     * @return \Closure a function f($target) that can be used to get property value
     */
    public function getPropertyAccessor($propertyName)
    {
        return function ($object) use (&$propertyName) {
            return (new \ReflectionClass($this->getType()))->getMethod('get' . ucfirst($propertyName))->invoke($object);
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
            (new \ReflectionClass($this->getType()))->getMethod('set' . ucfirst($propertyName))->invoke(
                $object,
                $value
            );
        };
    }

    protected function resolvePropertyMetadata(
        MetadataProviderInterface $metadataProvider,
        \ReflectionClass $objectClass
    ) {
        $properties = [];
        $propertyMethods = array_filter(
            $objectClass->getMethods(\ReflectionMethod::IS_PUBLIC),
            function (\ReflectionMethod $method) {
                return
                    $method->isPublic()
                    && (strpos($method->getName(), 'set') === 0)
                    && $method->getNumberOfParameters() === 1;
            }
        );

        foreach ($propertyMethods as $propMethod) {
            $propName = self::resolvePropertyName($propMethod);
            $type = self::parseType($propMethod);
            if ($type === null) {
                throw new PropertyTypeInfererenceMetadataException($propName);
            }
            $typeMetadata = $metadataProvider->getTypeMetadata($type);
            $properties[$propName] = $typeMetadata;
        }

        return $properties;
    }

    private static function resolvePropertyName(\ReflectionMethod $method)
    {
        $methodName = $method->getName();
        return lcfirst(substr($methodName, 3));
    }

    private static function parseType(\ReflectionMethod $propMethod)
    {
        $propMethodParams = $propMethod->getParameters();
        $propMethodParamClass = $propMethodParams[0]->getClass();
        if ($propMethodParamClass !== null) {
            return $propMethodParamClass->getName();
        }

        $annotations = self::parseAnnotations($propMethod->getDocComment());
        if (!isset($annotations['param'][0])) {
            return null;
        }
        list($type) = explode(' ', trim($annotations['param'][0]));
        return $type;
    }
}
