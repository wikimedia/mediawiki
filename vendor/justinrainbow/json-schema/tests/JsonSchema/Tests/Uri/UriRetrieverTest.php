<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Uri;

use JsonSchema\Exception\JsonDecodingException;
use JsonSchema\Validator;

/**
 * @group UriRetriever
 */
class UriRetrieverTest extends \PHPUnit_Framework_TestCase
{
    protected $validator;

    protected function setUp()
    {
        $this->validator = new Validator();
    }

    private function getRetrieverMock($returnSchema, $returnMediaType = Validator::SCHEMA_MEDIA_TYPE)
    {

        $jsonSchema = json_decode($returnSchema);

        if (JSON_ERROR_NONE < $error = json_last_error()) {
            throw new JsonDecodingException($error);
        }

        $retriever = $this->getMock('JsonSchema\Uri\UriRetriever', array('retrieve'));

        $retriever->expects($this->at(0))
                  ->method('retrieve')
                  ->with($this->equalTo(null), $this->equalTo('http://some.host.at/somewhere/parent'))
                  ->will($this->returnValue($jsonSchema));

        return $retriever;
    }

    /**
     * @dataProvider jsonProvider
     */
    public function testChildExtendsParentValidTest($childSchema, $parentSchema)
    {
        $retrieverMock = $this->getRetrieverMock($parentSchema);

        $json = '{"childProp":"infant", "parentProp":false}';
        $decodedJson = json_decode($json);
        $decodedJsonSchema = json_decode($childSchema);

        $this->validator->setUriRetriever($retrieverMock);
        $this->validator->check($decodedJson, $decodedJsonSchema);
        $this->assertTrue($this->validator->isValid());
    }

    /**
     * @dataProvider jsonProvider
     */
    public function testChildExtendsParentInvalidChildTest($childSchema, $parentSchema)
    {
        $retrieverMock = $this->getRetrieverMock($parentSchema);

        $json = '{"childProp":1, "parentProp":false}';
        $decodedJson = json_decode($json);
        $decodedJsonSchema = json_decode($childSchema);

        $this->validator->setUriRetriever($retrieverMock);
        $this->validator->check($decodedJson, $decodedJsonSchema);
        $this->assertFalse($this->validator->isValid());
    }

    /**
     * @dataProvider jsonProvider
     */
    public function testChildExtendsParentInvalidParentTest($childSchema, $parentSchema)
    {
        $retrieverMock = $this->getRetrieverMock($parentSchema);

        $json = '{"childProp":"infant", "parentProp":1}';
        $decodedJson = json_decode($json);
        $decodedJsonSchema = json_decode($childSchema);

        $this->validator->setUriRetriever($retrieverMock);
        $this->validator->check($decodedJson, $decodedJsonSchema);
        $this->assertFalse($this->validator->isValid());
    }

    /**
     * @dataProvider jsonProvider
     */
    public function testResolveRelativeUri($childSchema, $parentSchema)
    {
        self::setParentSchemaExtendsValue($parentSchema, 'grandparent');
        $retrieverMock = $this->getRetrieverMock($parentSchema);
        $json = '{"childProp":"infant", "parentProp":false}';
        $decodedJson = json_decode($json);
        $decodedJsonSchema = json_decode($childSchema);

        $this->validator->setUriRetriever($retrieverMock);
        $this->validator->check($decodedJson, $decodedJsonSchema);
        $this->assertTrue($this->validator->isValid());
    }

    private static function setParentSchemaExtendsValue(&$parentSchema, $value)
    {
        $parentSchemaDecoded = json_decode($parentSchema, true);
        $parentSchemaDecoded['extends'] = $value;
        $parentSchema = json_encode($parentSchemaDecoded);
    }

    public function jsonProvider()
    {
        $childSchema = <<<EOF
{
    "type":"object",
    "title":"child",
    "extends":"http://some.host.at/somewhere/parent",
    "properties":
    {
        "childProp":
        {
            "type":"string"
        }
    }
}
EOF;
        $parentSchema = <<<EOF
{
    "type":"object",
    "title":"parent",
    "properties":
    {
        "parentProp":
        {
            "type":"boolean"
        }
    }
}
EOF;
        return array(
            array($childSchema, $parentSchema)
        );
    }

    public function testResolvePointerNoFragment()
    {
        $schema = (object) array(
            'title' => 'schema'
        );

        $retriever = new \JsonSchema\Uri\UriRetriever();
        $this->assertEquals(
            $schema,
            $retriever->resolvePointer(
                $schema, 'http://example.org/schema.json'
            )
        );
    }

    public function testResolvePointerFragment()
    {
        $schema = (object) array(
            'definitions' => (object) array(
                'foo' => (object) array(
                    'title' => 'foo'
                )
            ),
            'title' => 'schema'
        );

        $retriever = new \JsonSchema\Uri\UriRetriever();
        $this->assertEquals(
            $schema->definitions->foo,
            $retriever->resolvePointer(
                $schema, 'http://example.org/schema.json#/definitions/foo'
            )
        );
    }

    /**
     * @expectedException JsonSchema\Exception\ResourceNotFoundException
     */
    public function testResolvePointerFragmentNotFound()
    {
        $schema = (object) array(
            'definitions' => (object) array(
                'foo' => (object) array(
                    'title' => 'foo'
                )
            ),
            'title' => 'schema'
        );

        $retriever = new \JsonSchema\Uri\UriRetriever();
        $retriever->resolvePointer(
            $schema, 'http://example.org/schema.json#/definitions/bar'
        );
    }

    /**
     * @expectedException JsonSchema\Exception\ResourceNotFoundException
     */
    public function testResolvePointerFragmentNoArray()
    {
        $schema = (object) array(
            'definitions' => (object) array(
                'foo' => array(
                    'title' => 'foo'
                )
            ),
            'title' => 'schema'
        );

        $retriever = new \JsonSchema\Uri\UriRetriever();
        $retriever->resolvePointer(
            $schema, 'http://example.org/schema.json#/definitions/foo'
        );
    }
    
    /**
     * @expectedException JsonSchema\Exception\UriResolverException
     */
    public function testResolveExcessLevelUp()
    {
        $retriever = new \JsonSchema\Uri\UriRetriever();
        $retriever->resolve(
            '../schema.json#', 'http://example.org/schema.json#'
        );
    }
}
