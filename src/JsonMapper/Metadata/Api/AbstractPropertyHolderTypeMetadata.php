<?php

namespace JsonMapper\Metadata\Api;

use JsonMapper\Metadata\Exception\UninstantiableTypeMetadataException;

/**
 * Base property holder class abstracting away all details around properties (value retrieval, parsing, etc.)
 */
abstract class AbstractPropertyHolderTypeMetadata implements PropertyHolderTypeMetadataInterface
{
    private $type;

    private $propertiesMetadata;

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


    public function hasProperty($propertyName)
    {
        return isset($this->propertiesMetadata[$propertyName]);
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

    /**
     * @param $propertyName
     *
     * @return \Closure a function f($target) that can be used to get property value
     */
    abstract public function getPropertyAccessor($propertyName);


    /**
     * @param $propertyName
     *
     * @return \Closure a function f($target, $value) that can be used to set property value
     */
    abstract public function getPropertyMutator($propertyName);

    /**
     * @param MetadataProviderInterface $metadataProvider
     * @param \ReflectionClass          $class
     *
     * @return array a map keyed by property name and value holding type metadata
     */
    abstract protected function resolvePropertyMetadata(
        MetadataProviderInterface $metadataProvider,
        \ReflectionClass $class
    );

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
}
