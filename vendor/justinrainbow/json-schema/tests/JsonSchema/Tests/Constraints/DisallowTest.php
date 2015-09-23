<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

class DisallowTest extends BaseTestCase
{
    public function getInvalidTests()
    {
        return array(
            array(
                '{
                  "value":"The xpto is weird"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{
                      "type":"any",
                      "disallow":{"type":"string","pattern":"xpto"}
                    }
                  }
                }'
            ),
            array(
                '{
                  "value":null
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{
                      "type":"any",
                      "disallow":{"type":"null"}
                    }
                  }
                }'
            ),
            array(
                '{"value": 1}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "any", "disallow": "integer"}
                    }
                }'
            ),
            array(
                '{"value": true}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "any", "disallow": ["integer", "boolean"]}
                    }
                }'
            ),
            array(
                '{"value": "foo"}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {
                            "type": "any",
                            "disallow":
                                ["string", {
                                    "type": "object",
                                    "properties": {
                                        "foo": {"type": "string"}
                                    }
                                }]
                        }
                    }
                }'
            ),
            array(
                '{"value": {"foo": "bar"}}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {
                            "type": "any",
                            "disallow":
                                ["string", {
                                    "type": "object",
                                    "properties": {
                                        "foo": {"type": "string"}
                                    }
                                }]
                        }
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
                  "value":"The xpto is weird"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{
                      "type":"any",
                      "disallow":{"type":"string","pattern":"^xpto"}
                    }
                  }
                }'
            ),
            array(
                '{
                  "value":1
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{
                      "type":"any",
                      "disallow":{"type":"null"}
                    }
                  }
                }'
            ),
            array(
                '{"value": {"foo": 1}}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {
                            "type": "any",
                            "disallow":
                                ["string", {
                                    "type": "object",
                                    "properties": {
                                        "foo": {"type": "string"}
                                    }
                                }]
                        }
                    }
                }'
            ),
            array(
                '{"value": true}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "any", "disallow": "string"}
                    }
                }'
            )
        );
    }
}
