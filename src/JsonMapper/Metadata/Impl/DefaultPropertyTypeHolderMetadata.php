<?php

namespace JsonMapper\Metadata\Impl;

use JsonMapper\Metadata\Api\MetadataProviderInterface;
use JsonMapper\Metadata\Api\PropertyHolderTypeMetadataInterface;
use JsonMapper\Metadata\Exception\PropertyTypeInfererenceMetadataException;
use JsonMapper\Metadata\Exception\UninstantiableTypeMetadataException;

/**
 * Default implementation for objects that are built of properties
 */
class DefaultPropertyTypeHolderMetadata implements PropertyHolderTypeMetadataInterface
{
    private $type;

    /**
     * @param MetadataProviderInterface $metadataProvider
     * @param                           $type
     */
    public function __construct(MetadataProviderInterface $metadataProvider, $type)
    {
        $this->type = $type;
        $class = new \ReflectionClass($type);
        $this->propertiesMetadata = $this->resolvePropertyMetadata($metadataProvider, $class);
    }


    public function getType()
    {
        return $this->type;
    }

    public function getPropertyTypeMetadata($name)
    {
        return $this->propertiesMetadata[$name];
    }

    public function getProperties()
    {
        return array_keys($this->propertiesMetadata);
    }


    public function instantiate()
    {
        $rc = new \ReflectionClass($this->type);
        if (!$rc->isInstantiable()
            || (!is_null($rc->getConstructor())
                && $rc->getConstructor()->getNumberOfParameters() !== 0)
        ) {
            throw new UninstantiableTypeMetadataException($this->type);
        }
        return $rc->newInstance();
    }

    public function hasProperty($propertyName)
    {
        return isset($this->propertiesMetadata[$propertyName]);
    }

    private static function resolvePropertyName(\ReflectionMethod $method)
    {
        $methodName = $method->getName();
        return lcfirst(substr($methodName, 3));
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

    protected static function parseAnnotations($docblock)
    {
        $annotations = [];
        $docblock = substr($docblock, 3, -2);

        // Strip away the docblock header and footer to ease parsing of one line annotations
        $re = '/@(?P<name>[A-Za-z_-]+)(?:[ \t]+(?P<value>.*?))?[ \t]*\r?$/m';
        if (preg_match_all($re, $docblock, $matches)) {
            $numMatches = count($matches[0]);

            for ($i = 0; $i < $numMatches; ++$i) {
                $annotations[$matches['name'][$i]][] = $matches['value'][$i];
            }
        }
        return $annotations;
    }

    /**
     * @param $propertyName
     *
     * @return \Closure a function f($target) that can be used to get property value
     */
    public function getPropertyAccessor($propertyName)
    {
        return function ($object) use (&$propertyName) {
            return (new \ReflectionClass($this->type))->getMethod('get' . ucfirst($propertyName))->invoke($object);
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
            (new \ReflectionClass($object))->getMethod('set' . ucfirst($propertyName))->invoke($object, $value);
        };
    }
}
