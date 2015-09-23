<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

class DivisibleByTest extends BaseTestCase
{
    public function getInvalidTests()
    {
        return array(
            array(
                '{"value": 5.6333}',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"number","divisibleBy":3}
                  }
                }'
            ),
            array(
                '{"value": 35}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "integer", "divisibleBy": 1.5}
                    }
                }'
            ),
            array(
                '{"value": 0.00751}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "number", "divisibleBy": 0.0001}
                    }
                }'
            ),
            array(
                '{"value": 7}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "integer", "divisibleBy": 2}
                    }
                }'
            )
        );
    }

    public function getValidTests()
    {
        return array(
            array(
                '{"value": 6}',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"number","divisibleBy":3}
                  }
                }'
            ),
            array(
                '{"value": 4.5}',
                '{
                    "type": "object",
                    "properties": {
                        "value": {"type": "number", "divisibleBy": 1.5}
                    }
                }'
            ),
            array(
                '{"value": 0.0075}',
                '{
                    "properties": {
                        "value": {"type": "number", "divisibleBy": 0.0001}
                    }
                }'
            ),
            array(
                '{"value": 1}',
                '{
                    "properties": {
                        "value": {"type": "number", "divisibleBy": 0.02}
                    }
                }'
            )
        );
    }
}
