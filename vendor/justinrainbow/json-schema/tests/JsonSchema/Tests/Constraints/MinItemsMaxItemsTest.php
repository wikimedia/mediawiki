<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

class MinItemsMaxItemsTest extends BaseTestCase
{
    public function getInvalidTests()
    {
        return array(
            array(
                '{
                  "value":[2]
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"array","minItems":2,"maxItems":4}
                  }
                }'
            ),
            array(
                '{
                  "value":[2,2,5,8,5]
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"array","minItems":2,"maxItems":4}
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
                  "value":[2,2]
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"array","minItems":2,"maxItems":4}
                  }
                }'
            ),
            array(
                '{
                  "value":[2,2,5,8]
                }',
                '{
                  "type":"object",
                  "properties":{
                    "value":{"type":"array","minItems":2,"maxItems":4}
                  }
                }'
            )
        );
    }
}
