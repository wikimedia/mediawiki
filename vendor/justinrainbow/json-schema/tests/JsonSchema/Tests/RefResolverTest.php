<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests;

/**
 * @group RefResolver
 */
class RefResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider resolveProvider
     */
    public function testResolve($input, $methods)
    {
        $resolver = $this->getMock('JsonSchema\RefResolver', array_keys($methods));
        foreach ($methods as $methodName => $methodInvocationCount) {
            $resolver->expects($this->exactly($methodInvocationCount))
                ->method($methodName);
        }
        $resolver->resolve($input);
    }

    public function resolveProvider() {
        return array(
            'non-object' => array(
                'string',
                array(
                    'resolveRef' => 0,
                    'resolveProperty' => 0,
                    'resolveArrayOfSchemas' => 0,
                    'resolveObjectOfSchemas' => 0
                )
            ),
            'empty object' => array(
                (object) array(),
                array(
                    'resolveRef' => 1,
                    'resolveProperty' => 4,
                    'resolveArrayOfSchemas' => 7,
                    'resolveObjectOfSchemas' => 3
                )
            )
        );
    }

    /**
     * Helper method for resolve* methods
     */
    public function helperResolveMethods($method, $input, $calls) {
        $resolver = $this->getMock('JsonSchema\RefResolver', array('resolve'));
        $resolver->expects($this->exactly($calls[$method]))
            ->method('resolve');
        $resolver->$method($input, 'testProp', 'http://example.com/');
    }

    /**
     * @dataProvider testSchemas
     */
    public function testResolveArrayOfSchemas($input, $calls) {
        $this->helperResolveMethods('resolveArrayOfSchemas', $input, $calls);
    }

    /**
     * @dataProvider testSchemas
     */
    public function testResolveObjectOfSchemas($input, $calls) {
        $this->helperResolveMethods('resolveObjectOfSchemas', $input, $calls);
    }

    public function testSchemas() {
        return array(
            'non-object' => array(
                (object) array(
                    'testProp' => 'string'
                ),
                array(
                    'resolveArrayOfSchemas' => 0,
                    'resolveObjectOfSchemas' => 0,
                    'resolveProperty' => 0
                )
            ),
            'undefined' => array(
                (object) array(
                ),
                array(
                    'resolveArrayOfSchemas' => 0,
                    'resolveObjectOfSchemas' => 0,
                    'resolveProperty' => 0
                )
            ),
            'empty object' => array(
                (object) array(
                    'testProp' => (object) array()
                ),
                array(
                    'resolveArrayOfSchemas' => 0,
                    'resolveObjectOfSchemas' => 0,
                    'resolveProperty' => 1
                )
            ),
            'filled object' => array(
                (object) array(
                    'testProp' => (object) array(
                        'one' => array(),
                        'two' => array()
                    )
                ),
                array(
                    'resolveArrayOfSchemas' => 0,
                    'resolveObjectOfSchemas' => 2,
                    'resolveProperty' => 1
                )
            ),
            'empty array' => array(
                (object) array(
                    'testProp' => array()
                ),
                array(
                    'resolveArrayOfSchemas' => 0,
                    'resolveObjectOfSchemas' => 0,
                    'resolveProperty' => 1
                )
            ),
            'filled array' => array(
                (object) array(
                    'testProp' => array(1, 2, 3)
                ),
                array(
                    'resolveArrayOfSchemas' => 3,
                    'resolveObjectOfSchemas' => 0,
                    'resolveProperty' => 1
                )
            )
        );
    }

    /**
     * @dataProvider refProvider
     */
    public function testResolveRef($expected, $input) {
        $resolver = $this->getMock('JsonSchema\RefResolver', array('fetchRef'));
        $resolver->expects($this->any())
            ->method('fetchRef')
            ->will($this->returnValue((object) array(
                'this was' => array('added', 'because'),
                'the' => (object) array('$ref resolved' => true)
            )));
        $resolver->resolveRef($input, 'http://example.com');
        $this->assertEquals($expected, $input);
    }

    public function refProvider() {
        return array(
            'no ref' => array(
                (object) array('test' => 'one'),
                (object) array('test' => 'one')
            ),
            // The $ref is not removed here
            'empty ref' => array(
                (object) array(
                    'test' => 'two',
                    '$ref' => ''
                ),
                (object) array(
                    'test' => 'two',
                    '$ref' => ''
                )
            ),
            // $ref is removed
            'qualified ref' => array(
                (object) array(
                    'this is' => 'another test',
                    'this was' => array('added', 'because'),
                    'the' => (object) array('$ref resolved' => true)
                ),
                (object) array(
                    '$ref' => 'http://example.com/',
                    'this is' => 'another test'
                )
            ),
        );
    }

    public function testFetchRefAbsolute()
    {
        $retr = new \JsonSchema\Uri\Retrievers\PredefinedArray(
            array(
                'http://example.org/schema' => <<<JSN
{
    "title": "schema",
    "type": "object",
    "id": "http://example.org/schema"
}
JSN
            )
        );

        $res = new \JsonSchema\RefResolver();
        $res->getUriRetriever()->setUriRetriever($retr);

        $this->assertEquals(
            (object) array(
                'title' => 'schema',
                'type'  => 'object',
                'id'    => 'http://example.org/schema'
            ),
            $res->fetchRef('http://example.org/schema', 'http://example.org/schema')
        );
    }

    public function testFetchRefAbsoluteAnchor()
    {
        $retr = new \JsonSchema\Uri\Retrievers\PredefinedArray(
            array(
                'http://example.org/schema' => <<<JSN
{
    "title": "schema",
    "type": "object",
    "id": "http://example.org/schema",
    "definitions": {
        "foo": {
            "type": "object",
            "title": "foo"
        }
    }
}
JSN
            )
        );

        $res = new \JsonSchema\RefResolver();
        $res->getUriRetriever()->setUriRetriever($retr);

        $this->assertEquals(
            (object) array(
                'title' => 'foo',
                'type'  => 'object',
                'id'    => 'http://example.org/schema#/definitions/foo',
            ),
            $res->fetchRef(
                'http://example.org/schema#/definitions/foo',
                'http://example.org/schema'
            )
        );
    }

    public function testFetchRefRelativeAnchor()
    {
        $retr = new \JsonSchema\Uri\Retrievers\PredefinedArray(
            array(
                'http://example.org/schema' => <<<JSN
{
    "title": "schema",
    "type": "object",
    "id": "http://example.org/schema",
    "definitions": {
        "foo": {
            "type": "object",
            "title": "foo"
        }
    }
}
JSN
            )
        );

        $res = new \JsonSchema\RefResolver();
        $res->getUriRetriever()->setUriRetriever($retr);

        $this->assertEquals(
            (object) array(
                'title' => 'foo',
                'type'  => 'object',
                'id'    => 'http://example.org/schema#/definitions/foo',
            ),
            $res->fetchRef(
                '#/definitions/foo',
                'http://example.org/schema'
            )
        );
    }

    public function testFetchRefArray()
    {
        $retr = new \JsonSchema\Uri\Retrievers\PredefinedArray(
            array(
                'http://example.org/array' => <<<JSN
[1,2,3]
JSN
            )
        );

        $res = new \JsonSchema\RefResolver();
        $res->getUriRetriever()->setUriRetriever($retr);

        $this->assertEquals(
            array(1, 2, 3),
            $res->fetchRef('http://example.org/array', 'http://example.org/array')
        );
    }

    public function testSetGetUriRetriever()
    {
        $retriever = new \JsonSchema\Uri\UriRetriever;
        $resolver  = new \JsonSchema\RefResolver;
        $this->assertInstanceOf('JsonSchema\Uri\UriRetriever', $resolver->getUriRetriever());
        $this->assertInstanceOf('JsonSchema\RefResolver', $resolver->setUriRetriever($retriever));
    }

    public function testFetchRef()
    {
        // stub schema
        $jsonSchema = new \stdClass;
        $jsonSchema->id = 'stub';
        $jsonSchema->additionalItems = 'stub';
        $ref        = 'ref';
        $sourceUri  = null;


        // mock retriever
        $retriever  = $this->getMock('JsonSchema\Uri\UriRetriever', array('retrieve'));
        $retriever->expects($this->any())->method('retrieve')->will($this->returnValue($jsonSchema));

        // stub resolver
        $resolver   = new \JsonSchema\RefResolver;
        $resolver->setUriRetriever($retriever);

        $this->assertEquals($jsonSchema, $resolver->fetchRef($ref, $sourceUri));
    }

    /**
     * @expectedException \JsonSchema\Exception\JsonDecodingException
     */
    public function testMaxDepthExceeded()
    {
        // stub schema
        $jsonSchema = new \stdClass;
        $jsonSchema->id = 'stub';
        $jsonSchema->additionalItems = 'stub';

        // mock retriever
        $retriever = $this->getMock('JsonSchema\Uri\UriRetriever', array('retrieve'));
        $retriever->expects($this->any())->method('retrieve')->will($this->returnValue($jsonSchema));

        // stub resolver
        \JsonSchema\RefResolver::$maxDepth = 0;
        $resolver = new \JsonSchema\RefResolver($retriever);

        $resolver->resolve($jsonSchema);
    }
}
