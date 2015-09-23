<?php
/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JsonSchema\Tests\Constraints;

use JsonSchema\Validator;

/**
 * Class OfPropertiesTest
 */
class OfPropertiesTest extends BaseTestCase
{

    public function getValidTests()
    {
        return array(
            array(
                '{"prop1": "abc"}',
                '{
                  "type": "object",
                  "properties": {
                    "prop1": {"type": "string"},
                    "prop2": {
                      "oneOf": [
                        {"type": "number"},
                        {"type": "string"}
                      ]
                    }
                  },
                  "required": ["prop1"]
                }'
            ),
            array(
                '{"prop1": "abc", "prop2": 23}',
                '{
                  "type": "object",
                  "properties": {
                    "prop1": {"type": "string"},
                    "prop2": {
                      "oneOf": [
                        {"type": "number"},
                        {"type": "string"}
                      ]
                    }
                  },
                  "required": ["prop1"]
                }'
            ),
        );
    }

    public function getInvalidTests()
    {
        return array(
            array(
                '{"prop1": "abc", "prop2": []}',
                '{
                  "type": "object",
                  "properties": {
                    "prop1": {"type": "string"},
                    "prop2": {
                      "oneOf": [
                        {"type": "number"},
                        {"type": "string"}
                      ]
                    }
                  },
                  "required": ["prop1"]
                }',
                Validator::CHECK_MODE_NORMAL,
                array(
                    array(
                        "property" => "prop2",
                        "message"  => "array value found, but a string is required",

                    ),
                    array(
                        "property" => "prop2",
                        "message"  => "array value found, but a number is required",
                    ),
                    array(
                        "property" => "prop2",
                        "message"  => "failed to match exactly one schema",
                    ),
                ),
            ),
            array(
                '{"prop1": [1,2]}',
                '{
                  "type": "object",
                  "properties": {
                    "prop1": {
                      "oneOf": [
                        {
                          "type": "string",
                          "pattern": "^[a-z]*$"
                        },
                        {
                          "type": "string",
                          "pattern": "^[A-Z]*$"
                        }
                      ]
                    }
                  }
                }'
            ),
            array(
                '{"prop1": [1,2]}',
                '{
                  "type": "object",
                  "properties": {
                    "prop1": {
                      "anyOf": [
                        {
                          "type": "string",
                          "pattern": "^[A-Z]*$"
                        }
                      ]
                    }
                  }
                }'
            ),
            array(
                '{"prop1": [1,2]}',
                '{
                  "type": "object",
                  "properties": {
                    "prop1": {
                      "anyOf": [
                        {
                          "type": "number"
                        },
                        {
                          "type": "string",
                          "pattern": "^[A-Z]*$"
                        }
                      ]
                    }
                  }
                }'
            ),
            array(
                '{"prop1": [1,2]}',
                '{
                  "type": "object",
                  "properties": {
                    "prop1": {
                      "anyOf": [
                        {
                          "type": "string"
                        },
                        {
                          "type": "string",
                          "pattern": "^[A-Z]*$"
                        }
                      ]
                    }
                  }
                }'
            ),
            array(
                '{"prop1": [1,2]}',
                '{
                  "type": "object",
                  "properties": {
                    "prop1": {
                      "anyOf": [
                        {
                          "type": "string",
                          "pattern": "^[a-z]*$"
                        },
                        {
                          "type": "string",
                          "pattern": "^[A-Z]*$"
                        }
                      ]
                    }
                  }
                }'
            ),
            array(
                '{"prop1": [1,2]}',
                '{
                  "type": "object",
                  "properties": {
                    "prop1": {
                      "anyOf": [
                        {
                          "type": "number"
                        },
                        {
                          "type": "string"
                        },
                        {
                          "type": "string"
                        }
                      ]
                    }
                  }
                }'
            )
        );
    }
}
