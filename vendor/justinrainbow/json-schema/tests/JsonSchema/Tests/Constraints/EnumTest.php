<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

class EnumTest extends BaseTestCase
{
    public function getInvalidTests()
    {
        return array(
            array(
                '{
                  "value":"Morango"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"string","enum":["Abacate","Manga","Pitanga"]}
                  },
                  "additionalProperties":false
                }'
            ),
            array(
                '{}',
                '{
                  "type":"object",
                  "properties":{
                    "value":{
                      "type":"string",
                      "enum":["Abacate","Manga","Pitanga"],
                      "required":true
                    }
                  },
                  "additionalProperties":false
                }'
            ),
            array(
                '{"value": 4}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {
                            "type": "integer", "enum": [1, 2, 3]
                        }
                    },
                    "additionalProperties": false
                }'
            ),
            array(
                '{"value": {"foo": false}}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {
                            "type": "any", "enum": [6, "foo", [], true, {"foo": 12}]
                        }
                    },
                    "additionalProperties": false
                }'
            )
        );
    }

    public function getValidTests()
    {
        return array(
            array(
                '{
                  "value":"Abacate"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"string","enum":["Abacate","Manga","Pitanga"]}
                  },
                  "additionalProperties":false
                }'
            ),
            array(
                '{}',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"string","enum":["Abacate","Manga","Pitanga"]}
                  },
                  "additionalProperties":false
                }'
            ),
            array(
                '{}',
                '{
                  "type":"object",
                  "properties":{
                    "value":{
                      "type":"string",
                      "enum":["Abacate","Manga","Pitanga"],
                      "required":false
                    }
                  },
                  "additionalProperties":false
                }'
            ),
            array(
                '{"value": 1}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "integer", "enum": [1, 2, 3]}
                    }
                }'
            ),
            array(
                '{"value": []}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "any", "enum": [6, "foo", [], true, {"foo": 12}]}
                    },
                    "additionalProperties": false
                }'
            ),
            array(
                '{"value": {"foo": 12}}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "any", "enum": [6, "foo", [], true, {"foo": 12}]}
                    },
                    "additionalProperties": false
                }'
            )
        );
    }
}
