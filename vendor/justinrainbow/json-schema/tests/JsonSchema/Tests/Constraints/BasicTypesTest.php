<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

class BasicTypesTest extends BaseTestCase
{
    public function getInvalidTests()
    {
        return array(
            array(
                '{
                  "string":null
                }',
                '{
                  "type":"object",
                  "properties": {
                    "string":{"type":"string"}
                  },
                  "additionalProperties":false
                }'
            ),
            array(
                '{
                  "number":null
                }',
                '{
                  "type":"object",
                  "properties": {
                    "number":{"type":"number"}
                  },
                  "additionalProperties":false
                }'
            ),
            array(
                '{
                  "integer":null
                }',
                '{
                  "type":"object",
                  "properties": {
                    "integer":{"type":"integer"}
                  },
                  "additionalProperties":false
                }'
            ),
            array(
                '{
                  "boolean":null
                }',
                '{
                  "type":"object",
                  "properties": {
                    "boolean":{"type":"boolean"}
                  },
                  "additionalProperties":false
                }'
            ),
            array(
                '{
                  "object":null
                }',
                '{
                  "type":"object",
                  "properties": {
                    "object":{"type":"object"}
                  },
                  "additionalProperties":false
                }'
            ),
            array(
                '{
                  "array":null
                }',
                '{
                  "type":"object",
                  "properties": {
                    "array":{"type":"array"}
                  },
                  "additionalProperties":false
                }'
            ),
            array(
                '{
                  "null":1
                }',
                '{
                  "type":"object",
                  "properties": {
                    "null":{"type":"null"}
                  },
                  "additionalProperties":false
                }'
            )
        );
    }

    public function getValidTests()
    {
        return array(
            array(
                '{
                  "string":"string test",
                  "number":1,
                  "integer":1,
                  "boolean":true,
                  "object":{},
                  "array":[],
                  "null":null,
                  "any": "string",
                  "any1": 2.6,
                  "any2": 4,
                  "any3": false,
                  "any4": {},
                  "any5": [],
                  "any6": null
                }',
                '{
                  "type":"object",
                  "properties":{
                    "string":{"type":"string"},
                    "number":{"type":"number"},
                    "integer":{"type":"integer"},
                    "boolean":{"type":"boolean"},
                    "object":{"type":"object"},
                    "array":{"type":"array"},
                    "null":{"type":"null"},
                    "any": {"type":"any"},
                    "any1": {"type":"any"},
                    "any2": {"type":"any"},
                    "any3": {"type":"any"},
                    "any4": {"type":"any"},
                    "any5": {"type":"any"},
                    "any6": {"type":"any"}
                  },
                  "additionalProperties":false
                }'
            )
        );
    }
}
