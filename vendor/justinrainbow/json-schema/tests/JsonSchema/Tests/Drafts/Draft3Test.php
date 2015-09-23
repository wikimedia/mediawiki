<?php

namespace JsonSchema\Tests\Drafts;

class Draft3Test extends BaseDraftTestCase
{
    protected function getFilePaths()
    {
        return array(
            realpath(__DIR__ . $this->relativeTestsRoot . '/draft3'),
            realpath(__DIR__ . $this->relativeTestsRoot . '/draft3/optional')
        );
    }

    protected function getSkippedTests()
    {
        return array(
            'ref.json',
            'refRemote.json',
            'bignum.json',
            'jsregex.json',
            'zeroTerminatedFloats.json'
        );
    }
}
