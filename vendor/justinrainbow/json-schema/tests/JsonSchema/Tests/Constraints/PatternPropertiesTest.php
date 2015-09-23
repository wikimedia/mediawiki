<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

class PatternPropertiesTest extends BaseTestCase
{
    public function getInvalidTests()
    {
        return array(
            // matches pattern but invalid schema for object
            array(
                json_encode(array(
                    'someobject' => array(
                        'foobar' => 'foo',
                        'barfoo' => 'bar',
                    )
                )),
                json_encode(array(
                    'type' => 'object',
                    'patternProperties' => array(
                        '^someobject$' => array(
                            'type' => 'object',
                            'additionalProperties' => false,
                            'properties' => array(
                                'barfoo' => array(
                                    'type' => 'string',
                                ),
                            )
                        )
                    )
                ))
            ),
            // Does not match pattern
            array(
                json_encode(array(
                        'regex_us' => false,
                    )),
                json_encode(array(
                        'type' => 'object',
                        'patternProperties' => array(
                            '^[a-z]+_(jp|de)$' => array(
                                'type' => array('boolean')
                            )
                        ),
                        "additionalProperties" => false
                    ))
            ),
            // An invalid regular expression pattern
            array(
                json_encode(array(
                        'regex_us' => false,
                    )),
                json_encode(array(
                        'type' => 'object',
                        'patternProperties' => array(
                            '^[a-z+_jp|de)$' => array(
                                'type' => array('boolean')
                            )
                        ),
                        "additionalProperties" => false
                    ))
            ),
        );
    }

    public function getValidTests()
    {
        return array(
            array(
                // validates pattern schema
                json_encode(array(
                    'someobject' => array(
                        'foobar' => 'foo',
                        'barfoo' => 'bar',
                    ),
                    'someotherobject' => array(
                        'foobar' => 1234,
                    )
                )),
                json_encode(array(
                    'type' => 'object',
                    'patternProperties' => array(
                        '^someobject$' => array(
                            'type' => 'object',
                            'properties' => array(
                                'foobar' => array('type' => 'string'),
                                'barfoo' => array('type' => 'string'),
                            ),
                        ),
                        '^someotherobject$' => array(
                            'type' => 'object',
                            'properties' => array(
                                'foobar' => array('type' => 'number'),
                            ),
                        ),
                    )
                ))
            ),
            array(
                json_encode(array(
                        'foobar' => true,
                        'regex_us' => 'foo',
                        'regex_de' => 1234
                    )),
                json_encode(array(
                        'type' => 'object',
                        'properties' => array(
                            'foobar' => array('type' => 'boolean')
                        ),
                        'patternProperties' => array(
                            '^[a-z]+_(us|de)$' => array(
                                'type' => array('string', 'integer')
                            )
                        ),
                        "additionalProperties" => false
                    ))
            ),
        );
    }
}

