<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

class NumberAndIntegerTypesTest extends BaseTestCase
{
    public function getInvalidTests()
    {
        return array(
            array(
                '{
                  "integer": 1.4
                }',
                '{
                  "type":"object",
                  "properties":{
                    "integer":{"type":"integer"}
                  }
                }'
            ),
            array(
                '{"number": "1.5"}',
                '{
                    "type": "object",
                    "properties": {
                        "number": {"type": "number"}
                    }
                }'
            ),
            array(
                '{"integer": "1"}',
                '{
                    "type": "object",
                    "properties": {
                        "integer": {"type": "integer"}
                    }
                }'
            ),
            array(
                '{"integer": 1.001}',
                '{
                    "type": "object",
                    "properties": {
                        "integer": {"type": "integer"}
                    }
                }'
            ),
            array(
                '{"integer": true}',
                '{
                    "type": "object",
                    "properties": {
                        "integer": {"type": "integer"}
                    }
                }'
            ),
            array(
                '{"number": "x"}',
                '{
                    "type": "object",
                    "properties": {
                        "number": {"type": "number"}
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
                  "integer": 1
                }',
                '{
                  "type":"object",
                  "properties":{
                    "integer":{"type":"integer"}
                  }
                }'
            ),
            array(
                '{
                  "number": 1.4
                }',
                '{
                  "type":"object",
                  "properties":{
                    "number":{"type":"number"}
                  }
                }'
            ),
            array(
                '{"number": 1e5}',
                '{
                    "type": "object",
                    "properties": {
                        "number": {"type": "number"}
                    }
                }'
            ),
            array(
                '{"number": 1}',
                '{
                    "type": "object",
                    "properties": {
                        "number": {"type": "number"}
                    }
                }'
            )
        );
    }
}
