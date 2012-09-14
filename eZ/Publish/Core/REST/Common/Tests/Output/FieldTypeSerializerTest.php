<?php
/**
 * File containing the FieldTypeSerializerTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\REST\Common\Tests\Output;

use eZ\Publish\Core\REST\Common;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\Repository\Values\ContentType\FieldDefinition;

/**
 * FieldTypeSerializer test
 */
class FieldTypeSerializerTest extends \PHPUnit_Framework_TestCase
{
    protected $fieldTypeServiceMock;

    protected $contentTypeMock;

    protected $fieldTypeMock;

    protected $generatorMock;

    public function testSerializeFieldValue()
    {
        $serializer = new Common\Output\FieldTypeSerializer(
            $this->getFieldTypeServiceMock()
        );

        $this->getGeneratorMock()->expects( $this->once() )
            ->method( 'generateFieldTypeHash' )
            ->with(
                $this->equalTo( 'fieldValue' ),
                $this->equalTo( array( 23, 42 ) )
            );

        $this->getContentTypeMock()->expects( $this->once() )
            ->method( 'getFieldDefinition' )
            ->with(
                $this->equalTo( 'some-field' )
            )->will(
                $this->returnValue(
                    new FieldDefinition( array(
                        'fieldTypeIdentifier' => 'myFancyFieldType',
                    ) )
                )
            );

        $fieldTypeMock = $this->getFieldTypeMock();
        $this->getFieldTypeServiceMock()->expects( $this->once() )
            ->method( 'getFieldType' )
            ->with( $this->equalTo( 'myFancyFieldType' ) )
            ->will( $this->returnCallback(
                function () use ( $fieldTypeMock )
                {
                    return $fieldTypeMock;
                }
            ) );

        $fieldTypeMock->expects( $this->once() )
            ->method( 'toHash' )
            ->with( $this->equalTo( 'my-field-value' ) )
            ->will( $this->returnValue( array( 23, 42 ) ) );

        $serializer->serializeFieldValue(
            $this->getGeneratorMock(),
            $this->getContentTypeMock(),
            new Field(
                array(
                    'fieldDefIdentifier' => 'some-field',
                    'value' => 'my-field-value'
                )
            )
        );
    }

    public function testSerializeFieldDefaultValue()
    {
        $serializer = new Common\Output\FieldTypeSerializer(
            $this->getFieldTypeServiceMock()
        );

        $this->getGeneratorMock()->expects( $this->once() )
            ->method( 'generateFieldTypeHash' )
            ->with(
                $this->equalTo( 'defaultValue' ),
                $this->equalTo( array( 23, 42 ) )
            );

        $fieldTypeMock = $this->getFieldTypeMock();
        $this->getFieldTypeServiceMock()->expects( $this->once() )
            ->method( 'getFieldType' )
            ->with( $this->equalTo( 'myFancyFieldType' ) )
            ->will( $this->returnCallback(
                function () use ( $fieldTypeMock )
                {
                    return $fieldTypeMock;
                }
            ) );

        $fieldTypeMock->expects( $this->once() )
            ->method( 'toHash' )
            ->with( $this->equalTo( 'my-field-value' ) )
            ->will( $this->returnValue( array( 23, 42 ) ) );

        $fieldDefinition = new FieldDefinition( array(
            'fieldTypeIdentifier' => 'myFancyFieldType',
        ) );

        $serializer->serializeFieldDefaultValue(
            $this->getGeneratorMock(),
            $fieldDefinition,
            'my-field-value'
        );
    }

    public function testSerializeFieldSettings()
    {
        $serializer = new Common\Output\FieldTypeSerializer(
            $this->getFieldTypeServiceMock()
        );

        $this->getGeneratorMock()->expects( $this->once() )
            ->method( 'generateFieldTypeHash' )
            ->with(
                $this->equalTo( 'fieldSettings' ),
                $this->equalTo( array( 'foo' => 'bar' ) )
            );

        $fieldTypeMock = $this->getFieldTypeMock();
        $this->getFieldTypeServiceMock()->expects( $this->once() )
            ->method( 'getFieldType' )
            ->with( $this->equalTo( 'myFancyFieldType' ) )
            ->will( $this->returnCallback(
                function () use ( $fieldTypeMock )
                {
                    return $fieldTypeMock;
                }
            ) );

        $fieldTypeMock->expects( $this->once() )
            ->method( 'fieldSettingsToHash' )
            ->with( $this->equalTo( 'my-field-settings' ) )
            ->will( $this->returnValue( array( 'foo'=> 'bar' ) ) );

        $fieldDefinition = new FieldDefinition( array(
            'fieldTypeIdentifier' => 'myFancyFieldType',
        ) );

        $serializer->serializeFieldSettings(
            $this->getGeneratorMock(),
            $fieldDefinition,
            'my-field-settings'
        );
    }

    public function testSerializeValidatorConfiguration()
    {
        $serializer = new Common\Output\FieldTypeSerializer(
            $this->getFieldTypeServiceMock()
        );

        $this->getGeneratorMock()->expects( $this->once() )
            ->method( 'generateFieldTypeHash' )
            ->with(
                $this->equalTo( 'validatorConfiguration' ),
                $this->equalTo( array( 'bar' => 'foo' ) )
            );

        $fieldTypeMock = $this->getFieldTypeMock();
        $this->getFieldTypeServiceMock()->expects( $this->once() )
            ->method( 'getFieldType' )
            ->with( $this->equalTo( 'myFancyFieldType' ) )
            ->will( $this->returnCallback(
                function () use ( $fieldTypeMock )
                {
                    return $fieldTypeMock;
                }
            ) );

        $fieldTypeMock->expects( $this->once() )
            ->method( 'validatorConfigurationToHash' )
            ->with( $this->equalTo( 'validator-config' ) )
            ->will( $this->returnValue( array( 'bar'=> 'foo' ) ) );

        $fieldDefinition = new FieldDefinition( array(
            'fieldTypeIdentifier' => 'myFancyFieldType',
        ) );

        $serializer->serializeValidatorConfiguration(
            $this->getGeneratorMock(),
            $fieldDefinition,
            'validator-config'
        );
    }

    protected function getFieldTypeServiceMock()
    {
        if ( !isset( $this->fieldTypeServiceMock ) )
        {
            $this->fieldTypeServiceMock = $this->getMock(
                'eZ\\Publish\\API\\Repository\\FieldTypeService',
                array(),
                array(),
                '',
                false
            );
        }
        return $this->fieldTypeServiceMock;
    }

    protected function getContentTypeMock()
    {
        if ( !isset( $this->contentTypeMock ) )
        {
            $this->contentTypeMock = $this->getMock(
                'eZ\\Publish\\API\\Repository\\Values\\ContentType\\ContentType',
                array(),
                array(),
                '',
                false
            );
        }
        return $this->contentTypeMock;
    }

    protected function getFieldTypeMock()
    {
        if ( !isset( $this->fieldTypeMock ) )
        {
            $this->fieldTypeMock = $this->getMock(
                'eZ\\Publish\\API\\Repository\\FieldType',
                array(),
                array(),
                '',
                false
            );
        }
        return $this->fieldTypeMock;
    }

    protected function getGeneratorMock()
    {
        if ( !isset( $this->generatorMock ) )
        {
            $this->generatorMock = $this->getMock(
                'eZ\\Publish\\Core\\REST\\Common\\Output\\Generator',
                array(),
                array(),
                '',
                false
            );
        }
        return $this->generatorMock;
    }
}
