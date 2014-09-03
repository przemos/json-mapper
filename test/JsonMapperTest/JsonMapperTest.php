<?php

namespace JsonMapperTest;

use JsonMapper\Converter\Impl\DefaultConverterRegistry;
use JsonMapper\Converter\Impl\NotSupportedConverter;
use JsonMapper\Exception\JsonMapperException;
use JsonMapper\JsonMapper;
use JsonMapperTest\Classes\ArrayOfCustomTypeHolderClass;
use JsonMapperTest\Classes\ArrayOfObjectsPropertiesClass;
use JsonMapperTest\Classes\ClassHavingClassWithInvalidType;
use JsonMapperTest\Classes\ClassWithInvalidType;
use JsonMapperTest\Classes\CustomTypeHolderClass;
use JsonMapperTest\Classes\MissingTypePrimitivePropertiesClass;
use JsonMapperTest\Classes\ObjectPropertiesClass;
use JsonMapperTest\Classes\PrimitiveArrayPropertiesClass;
use JsonMapperTest\Classes\PrimitivePropertiesClass;
use JsonMapperTest\Classes\UninstatiableClass;
use JsonMapperTest\Converter\CustomTypeConverter;
use JsonMapperTest\Metadata\DummyMetadataProvider;
use JsonMapperTest\Type\CustomType;

/**
 *
 */
class JsonMapperTest extends \PHPUnit_Framework_TestCase
{

    const EX_UNSUPPORTED_TYPE_TMPL = '/Unsupported type (.*)/';
    const EX_PROP_NOT_FOUND_TMPL = '/Property not found/';
    const EX_UNINSTANTIABLE_TMPL = '/Unable to instantiate type: (.*)/';
    const EX_CONVERTION_FAILED_TMPL = '/Conversion failed for "(.*)" to type (.*)/';
    const EX_TYPE_INFERENCE_TMPL = '/Unable to infer type for property: (.*)/';

    /** @var  JsonMapper */
    private $jm;

    public function setUp()
    {
        $reg = new DefaultConverterRegistry();
        $reg->register(new CustomTypeConverter());
        $this->jm = new JsonMapper();
        $this->jm->setConverterRegistry($reg);
    }

    public function testToJson_singleObject_invalidTypeIndirect_shouldThrowException()
    {
        try {
            $invalidTypeClass = (new ClassWithInvalidType())->setInvalidProperty("prop");
            $parentClass = (new ClassHavingClassWithInvalidType())->setObjectWithInvalidType($invalidTypeClass);
            $this->jm->toJson($parentClass);
        } catch (JsonMapperException $e) {
            $this->assertExceptionMatches(
                $e,
                self::EX_UNSUPPORTED_TYPE_TMPL,
                ['invalidType']
            );
            $this->assertExceptionPathEquals($e, '::objectWithInvalidType.invalidProperty');
        }
    }


    public function testToJson_singleObject_primitiveProperties()
    {
        $primitiveClass = (new PrimitivePropertiesClass())
            ->setBoolProperty(true)
            ->setIntProperty(213)
            ->setStringProperty('abc')
            ->setDoubleProperty(123.456);

        $res = $this->jm->toJson($primitiveClass);

        $this->assertEquals(true, $res['boolProperty']);
        $this->assertEquals(213, $res['intProperty']);
        $this->assertEquals('abc', $res['stringProperty']);
        $this->assertEquals(123.456, $res['doubleProperty']);
    }

    public function testToJson_arrayOfObjects_objectProperties()
    {
        $primitiveObject1 = (new PrimitivePropertiesClass())->setStringProperty('abc');
        $primitiveObject2 = (new PrimitivePropertiesClass())->setStringProperty('xyz');
        $complexObject = (new ArrayOfObjectsPropertiesClass())->setPrimitives([$primitiveObject1, $primitiveObject2]);

        $res = $this->jm->toJson($complexObject);

        $this->assertCount(2, $res['primitives']);
        $this->assertEquals('abc', $res['primitives'][0]['stringProperty']);
        $this->assertEquals('xyz', $res['primitives'][1]['stringProperty']);
    }

    public function testToJson_singleObject_customType()
    {
        $customTypeHolder = (new CustomTypeHolderClass())->setCustomType(CustomType::outerCreate('1234'));

        $res = $this->jm->toJson($customTypeHolder);

        $this->assertEquals('1234JSON', $res['customType']);
    }


    public function testToJson_arrayOfObjects()
    {
        $primitiveObject1 = (new PrimitivePropertiesClass())->setStringProperty('abc');
        $primitiveObject2 = (new PrimitivePropertiesClass())->setStringProperty('xyz');
        $complexObject1 = (new ObjectPropertiesClass())->setPrimitive($primitiveObject1);
        $complexObject2 = (new ObjectPropertiesClass())->setPrimitive($primitiveObject2);

        $res = $this->jm->toJson([$complexObject1, $complexObject2], ObjectPropertiesClass::CLASS_PATH . '[]');

        $this->assertCount(2, $res);
        $this->assertEquals('abc', $res[0]['primitive']['stringProperty']);
        $this->assertEquals('xyz', $res[1]['primitive']['stringProperty']);
    }


    public function testFromJson_primitiveProperties()
    {
        $input = ['boolProperty' => true, 'intProperty' => 213, 'stringProperty' => 'abc', 'doubleProperty' => 123.456];

        $output = $this->jm->fromJson($input, PrimitivePropertiesClass::CLASS_PATH);

        $this->assertEquals(true, $output->getBoolProperty());
        $this->assertEquals(213, $output->getIntProperty());
        $this->assertEquals('abc', $output->getStringProperty());
        $this->assertEquals(123.456, $output->getDoubleProperty());
    }

    public function testFromJson_primitiveProperties_unableToInferType_shouldThrowException()
    {
        try {
            $input = [
                'boolProperty'   => true,
                'intProperty'    => 213,
                'stringProperty' => 'abc',
                'doubleProperty' => 123.456
            ];

            $this->jm->fromJson($input, MissingTypePrimitivePropertiesClass::CLASS_PATH);
        } catch (JsonMapperException $e) {
            $this->assertExceptionMatches($e, self::EX_TYPE_INFERENCE_TMPL, ['boolProperty']);
        }
    }

    public function testFromJson_primitiveProperties_incompatibleInput()
    {
        try {
            $input = [
                'boolProperty'   => 'true',
                'intProperty'    => 213,
                'stringProperty' => 'abc',
                'doubleProperty' => 123.456
            ];

            $this->jm->fromJson($input, PrimitivePropertiesClass::CLASS_PATH);
        } catch (JsonMapperException $e) {
            $this->assertExceptionMatches(
                $e,
                self::EX_CONVERTION_FAILED_TMPL,
                ['true', 'boolean']
            );
            $this->assertExceptionPathEquals($e, '::boolProperty');
        }
    }

    public function testFromJson_primitiveJsonInsteadOfIterable_shouldThrowException()
    {
        $input = ['primitives' => 4];

        try {
            $this->jm->fromJson($input, ArrayOfObjectsPropertiesClass::CLASS_PATH);
        } catch (JsonMapperException $e) {
            $this->assertExceptionMatches(
                $e,
                self::EX_CONVERTION_FAILED_TMPL,
                ['4', '\\' . PrimitivePropertiesClass::CLASS_PATH . '[]']
            );
            $this->assertExceptionPathEquals($e, '::primitives');
        }
    }

    public function testFromJson_singleObject_invalidTypeIndirect_shouldThrowException()
    {
        $input = ['objectWithInvalidType' => ['invalidProperty' => 'prop']];
        try {
            $this->jm->fromJson($input, ClassHavingClassWithInvalidType::CLASS_PATH);
        } catch (JsonMapperException $e) {
            $this->assertExceptionMatches($e, self::EX_UNSUPPORTED_TYPE_TMPL, ['invalidType']);
            $this->assertExceptionPathEquals($e, '::objectWithInvalidType.invalidProperty');
        }
    }

    public function testFromJson_complexObject_propertyNotFound_shouldThrowException()
    {
        $input = ['primitives' => [['stringProperty' => 'abc'], ['nonExistingProperty' => 'xyz']]];

        try {
            $this->jm->fromJson($input, ArrayOfObjectsPropertiesClass::CLASS_PATH);
        } catch (JsonMapperException $e) {
            $this->assertExceptionMatches($e, self::EX_PROP_NOT_FOUND_TMPL);
            $this->assertExceptionPathEquals($e, '::primitives[1].nonExistingProperty');
        }
    }

    public function testFromJson_singleObject_uninstatiableClass_shouldThrowException()
    {
        $input = ['prop' => 1];

        try {
            $this->jm->fromJson($input, UninstatiableClass::CLASS_PATH);
        } catch (JsonMapperException $e) {
            $this->assertExceptionMatches(
                $e,
                self::EX_UNINSTANTIABLE_TMPL
            );
            $this->assertExceptionPathEquals($e, '::');

        }
    }


    public function testFromJson_arrayOfObjects_objectProperties()
    {

        $input = ['primitives' => [['stringProperty' => 'abc'], ['stringProperty' => 'xyz']]];

        /** @var ArrayOfObjectsPropertiesClass $output */
        $output = $this->jm->fromJson($input, ArrayOfObjectsPropertiesClass::CLASS_PATH);

        $this->assertCount(2, $output->getPrimitives());
        $this->assertEquals('abc', $output->getPrimitives()[0]->getStringProperty());
        $this->assertEquals('xyz', $output->getPrimitives()[1]->getStringProperty());
    }

    public function testFromJson_singleObject_customType()
    {
        $input = ['customType' => '1234JSON'];

        $output = $this->jm->fromJson($input, CustomTypeHolderClass::CLASS_PATH);

        $this->assertEquals('1234', $output->getCustomType()->getInternal());
    }

    public function testFromJson_arrayOfObjects()
    {
        $input = [['primitive' => ['stringProperty' => 'abc']], ['primitive' => ['stringProperty' => 'xyz']]];

        /** @var ObjectPropertiesClass[] $res */
        $res = $this->jm->fromJson($input, ObjectPropertiesClass::CLASS_PATH . '[]');

        $this->assertCount(2, $res);
        $this->assertEquals('abc', $res[0]->getPrimitive()->getStringProperty());
        $this->assertEquals('xyz', $res[1]->getPrimitive()->getStringProperty());
    }

    public function testFromJson_primitiveArraysProperties()
    {

        $input = [
            'booleans' => [true, false],
            'integers' => [1, 2, 3, 4],
            'doubles'  => [123.456, 654.321],
            'strings'  => ['abc', 'xyz']
        ];

        $output = $this->jm->fromJson($input, PrimitiveArrayPropertiesClass::CLASS_PATH);
        $this->assertEquals($input['booleans'], $output->getBooleans());
        $this->assertEquals($input['doubles'], $output->getDoubles());
        $this->assertEquals($input['integers'], $output->getIntegers());
        $this->assertEquals($input['strings'], $output->getStrings());
    }


    public function testFromJson_arrayOfCustomTypes()
    {
        $input = [['customTypes' => ['789JSON', '987JSON']], ['customTypes' => ['123JSON', '321JSON']]];

        /** @var ArrayOfCustomTypeHolderClass[] $res */
        $res = $this->jm->fromJson($input, ArrayOfCustomTypeHolderClass::CLASS_PATH . '[]');

        $this->assertEquals('789', $res[0]->getCustomTypes()[0]->getInternal());
        $this->assertEquals('987', $res[0]->getCustomTypes()[1]->getInternal());
        $this->assertEquals('123', $res[1]->getCustomTypes()[0]->getInternal());
        $this->assertEquals('321', $res[1]->getCustomTypes()[1]->getInternal());
    }

    public function testToJson_overrideConverterRegistryWithNoConverters_shouldThrowUnsupportedType()
    {
        $reg = new DefaultConverterRegistry(false);
        $reg->register(new NotSupportedConverter());
        $jm = new JsonMapper();
        $jm->setConverterRegistry($reg);
        try {
            $input = [
                'boolProperty'   => 'true',
                'intProperty'    => 213,
                'stringProperty' => 'abc',
                'doubleProperty' => 123.456
            ];

            $jm->fromJson($input, PrimitivePropertiesClass::CLASS_PATH);
        } catch (JsonMapperException $e) {
            $this->assertExceptionMatches(
                $e,
                self::EX_UNSUPPORTED_TYPE_TMPL,
                [PrimitivePropertiesClass::CLASS_PATH]
            );
        }
    }

    public function testToJson_overrideMetadataProvider_shouldThrowUnsupportedType()
    {
        $jm = new JsonMapper();
        $jm->setMetadataProvider(new DummyMetadataProvider());
        try {
            $input = [
                'boolProperty'   => 'true',
                'intProperty'    => 213,
                'stringProperty' => 'abc',
                'doubleProperty' => 123.456
            ];

            $jm->fromJson($input, PrimitivePropertiesClass::CLASS_PATH);
            $this->fail("Exception exception");
        } catch (JsonMapperException $e) {
            $this->assertNotNull($e);
        }
    }

    private function assertExceptionPathEquals(JsonMapperException $e, $path)
    {
        $this->assertEquals($path, $e->getPath());
    }

    private function assertExceptionMatches(JsonMapperException $e, $regex, $terms = null)
    {
        $res = preg_match($regex, $e->getMessage(), $matches);
        if (!$res) {
            $exMessage = $e->getMessage();
            $this->fail("Expected exception message template: [$regex], but got [$exMessage}] ");
        } else {
            if ($terms !== null) {
                array_shift($matches);
                $this->assertEquals($terms, $matches);
            }
        }
    }
}
