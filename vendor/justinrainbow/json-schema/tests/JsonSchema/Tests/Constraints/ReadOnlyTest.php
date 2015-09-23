<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

class ReadOnlyTest extends BaseTestCase
{
    public function getInvalidTests()
    {
        //is readonly really required?
        return array(
            array(
                '{ "number": [] }',
                '{
                  "type":"object",
                  "properties":{
                    "number":{"type":"string","readonly":true}
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
                  "number": "1.4"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "number":{"type":"string","readonly":true}
                  }
                }'
            )
        );
    }
}
