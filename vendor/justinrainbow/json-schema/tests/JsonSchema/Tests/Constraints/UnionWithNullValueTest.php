<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

class UnionWithNullValueTest extends BaseTestCase
{
    public function getInvalidTests()
    {
        return array(
            array(
                '{
                  "stringOrNumber":null,
                  "booleanOrNull":null
                }',
                '{
                  "type":"object",
                  "properties":{
                    "stringOrNumber":{"type":["string","number"]},
                    "booleanOrNull":{"type":["boolean","null"]}
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
                  "stringOrNumber":12,
                  "booleanOrNull":null
                }',
                '{
                  "type":"object",
                  "properties":{
                    "stringOrNumber":{"type":["string","number"]},
                    "booleanOrNull":{"type":["boolean","null"]}
                  }
                }'
            )
        );
    }
}
