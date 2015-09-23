<?php

namespace JsonSchema\Tests\Uri\Retrievers;

use JsonSchema\Uri\Retrievers\FileGetContents;

/**
 * @group FileGetContents
 */
class FileGetContentsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException JsonSchema\Exception\ResourceNotFoundException
     */
    public function testFetchMissingFile()
    {
        $res = new FileGetContents();
        $res->retrieve(__DIR__.'/Fixture/missing.json');
    }

    public function testFetchFile()
    {
        $res = new FileGetContents();
        $result = $res->retrieve(__DIR__.'/../Fixture/child.json');
        $this->assertNotEmpty($result);
    }
}
