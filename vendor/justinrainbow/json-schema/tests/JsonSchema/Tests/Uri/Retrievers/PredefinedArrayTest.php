<?php

namespace JsonSchema\Tests\Uri\Retrievers;

use JsonSchema\Uri\Retrievers\PredefinedArray;

/**
 * @group PredefinedArray
 */
class PredefinedArrayTest extends \PHPUnit_Framework_TestCase
{
    private $retriever;

    public function setUp()
    {
        $this->retriever = new PredefinedArray(
            array(
                'http://acme.com/schemas/person#'  => 'THE_PERSON_SCHEMA',
                'http://acme.com/schemas/address#' => 'THE_ADDRESS_SCHEMA',
            ),
            'THE_CONTENT_TYPE'
        );
    }

    public function testRetrieve()
    {
        $this->assertEquals('THE_PERSON_SCHEMA', $this->retriever->retrieve('http://acme.com/schemas/person#'));
        $this->assertEquals('THE_ADDRESS_SCHEMA', $this->retriever->retrieve('http://acme.com/schemas/address#'));
    }

    /**
     * @expectedException JsonSchema\Exception\ResourceNotFoundException
     */
    public function testRetrieveNonExistsingSchema()
    {
        $this->retriever->retrieve('http://acme.com/schemas/plop#');
    }

    public function testGetContentType()
    {
        $this->assertEquals('THE_CONTENT_TYPE', $this->retriever->getContentType());
    }
}
