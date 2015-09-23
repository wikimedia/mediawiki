<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

class RequireTest extends BaseTestCase
{
    public function getInvalidTests()
    {
        return array(
            array(
                '{
                  "state":"DF"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "state":{"type":"string","requires":"city"},
                    "city":{"type":"string"}
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
                  "state":"DF",
                  "city":"Brasília"
                }',
                '{
                  "type":"object",
                  "properties":{
                    "state":{"type":"string","requires":"city"},
                    "city":{"type":"string"}
                  }
                }'
            )
        );
    }
}
