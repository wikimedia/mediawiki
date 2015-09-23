<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

use JsonSchema\Validator;

class AdditionalPropertiesTest extends BaseTestCase
{
    public function getInvalidTests()
    {
        return array(
            array(
                '{
                  "prop":"1",
                  "patternProp":"3",
                  "additionalProp":"2"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "prop":{"type":"string"}
                  },
                  "patternProperties":{
                      "^patternProp$":{"type":"string"}
                  },
                  "additionalProperties": false
                }',
                null,
                array(
                    array(
                        'property' => '',
                        'message'  => 'The property - additionalProp - is not defined and the definition does not allow additional properties'
                    )
                )
            ),
            array(
                '{
                  "prop":"1",
                  "additionalProp":"2"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "prop":{"type":"string"}
                  },
                  "additionalProperties": false
                }',
                Validator::CHECK_MODE_TYPE_CAST
            ),
            array(
                '{
                  "prop":"1",
                  "additionalProp":2
                }',
                '{
                  "type":"object",
                  "properties":{
                    "prop":{"type":"string"}
                  },
                  "additionalProperties": {"type":"string"}
                }'
            ),
            array(
                '{
                  "prop":"1",
                  "additionalProp":2
                }',
                '{
                  "type":"object",
                  "properties":{
                    "prop":{"type":"string"}
                  },
                  "additionalProperties": {"type":"string"}
                }',
                Validator::CHECK_MODE_TYPE_CAST
            )
        );
    }

    public function getValidTests()
    {
        return array(
            array(
                '{
                  "prop":"1",
                  "additionalProp":"2"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "prop":{"type":"string"}
                  }
                }'
            ),
            array(
                '{
                  "prop":"1",
                  "additionalProp":"2"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "prop":{"type":"string"}
                  }
                }',
                Validator::CHECK_MODE_TYPE_CAST
            ),
            array(
                '{
                  "prop":"1",
                  "additionalProp":"2"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "prop":{"type":"string"}
                  },
                  "additionalProperties": {"type":"string"}
                }'
            ),
            array(
                '{
                  "prop":"1",
                  "additionalProp":[]
                }',
                '{
                  "type":"object",
                  "properties":{
                    "prop":{"type":"string"}
                  },
                  "additionalProperties": true
                }'
            )
        );
    }
}
