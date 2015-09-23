<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

class MinimumMaximumTest extends BaseTestCase
{
    public function getInvalidTests()
    {
        return array(
            array(
                '{
                  "value":2
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"integer","minimum":4}
                  }
                }'
            ),
            array(
                '{"value": 3}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "integer", "minimum": 3, "exclusiveMinimum": true}
                    }
                }'
            ),
            array(
                '{
                  "value":16
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"integer","maximum":8}
                  }
                }'
            ),
            array(
                '{"value": 8}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "integer", "maximum": 8, "exclusiveMaximum": true}
                    }
                }'
            ),
            array(
                '{"value": 4}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "integer", "exclusiveMinimum": true}
                    }
                }'
            ),
            array(
                '{"value": 4}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "integer", "exclusiveMaximum": true}
                    }
                }'
            ),
            array(
                '{"value": 4}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "integer", "minimum": 5, "exclusiveMinimum": false}
                    }
                }'
            ),
            array(
                '{"value": 4}',
                '{
                    "properties": {
                        "value": {"type": "integer", "maximum": 3, "exclusiveMaximum": false}
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
                  "value":6
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"integer","minimum":4}
                  }
                }'
            ),
            array(
                '{
                  "value":6
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"integer","maximum":8}
                  }
                }'
            ),
            array(
                '{"value": 6}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "integer", "minimum": 6, "exclusiveMinimum": false}
                    }
                }'
            ),
            array(
                '{"value": 6}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "integer", "maximum": 6, "exclusiveMaximum": false}
                    }
                }'
            ),
            array(
                '{"value": 6}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "integer", "minimum": 6}
                    }
                }'
            ),
            array(
                '{"value": 6}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "integer", "maximum": 6}
                    }
                }'
            )
        );
    }
}
