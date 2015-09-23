<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

class NotTest extends BaseTestCase
{
    public function getInvalidTests()
    {
        return array(
            array(
                '{
                    "x": [1, 2]
                }',
                '{
                    "properties": {
                        "x": {
                            "not": {
                                "type": "array",
                                "items": {"type": "integer"},
                                "minItems": 2
                            }
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
                    "x": [1]
                }',
                '{
                    "properties": {
                        "x": {
                            "not": {
                                "type": "array",
                                "items": {"type": "integer"},
                                "minItems": 2
                            }
                        }
                    }
                }'
            ),
            array(
                '{
                    "x": ["foo", 2]
                }',
                '{
                    "properties": {
                        "x": {
                            "not": {
                                "type": "array",
                                "items": {"type": "integer"},
                                "minItems": 2
                            }
                        }
                    }
                }'
            )
        );
    }
}
