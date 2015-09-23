<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

class MinLengthMaxLengthMultiByteTest extends BaseTestCase
{
    protected function setUp()
    {
        if (! extension_loaded('mbstring')) {
            $this->markTestSkipped('mbstring extension is not available');
        }
    }

    public function getInvalidTests()
    {
        return array(
            array(
                '{
                  "value":"☀"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"string","minLength":2,"maxLength":4}
                  }
                }'
            ),
            array(
                '{
                  "value":"☀☁☂☃☺"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"string","minLength":2,"maxLength":4}
                  }
                }'
            )
        );
    }

    public function getValidTests()
    {
        return array(
            array(
                '{
                  "value":"☀☁"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"string","minLength":2,"maxLength":4}
                  }
                }'
            ),
            array(
                '{
                  "value":"☀☁☂☃"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"string","minLength":2,"maxLength":4}
                  }
                }'
            )
        );
    }
}
