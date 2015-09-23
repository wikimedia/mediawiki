<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

class DependenciesTest extends BaseTestCase
{
    public function getInvalidTests()
    {
        return array(
            array(
                '{"bar": 1}',
                '{
                    "dependencies": {"bar": "foo"}
                }'
            ),
            array(
                '{"bar": 1}',
                '{
                    "dependencies": {"bar": ["foo"]}
                }'
            ),
            array(
                '{"bar": 1, "foo": 1}',
                '{
                    "dependencies": {"bar": ["foo", "baz"]}
                }'
            ),
            array(
                '{"bar": 1, "foo": 1}',
                '{
                    "dependencies": {"bar": {
                        "properties": {
                            "foo": {"type": "string"}
                        }
                    }}
                }'
            ),
            array(
                '{"bar": 1}',
                '{
                    "dependencies": {"bar": {
                        "properties": {
                            "foo": {"type": "integer", "required": true}
                        }
                    }}
                }'
            ),
            array(
                '{"bar": 1}',
                '{
                    "dependencies": {"bar": {
                        "properties": {
                            "foo": {"type": "integer"}
                        },
                        "required": ["foo"]
                    }}
                }'
            ),
            array(
                '{"bar": true, "foo": "ick"}',
                '{
                    "dependencies": {"bar": {
                        "properties": {
                            "bar": {"type": "integer"},
                            "foo": {"type": "integer"}
                        }
                    }}
                }'
            )
        );
    }

    public function getValidTests()
    {
        return array(
            array(
                '{}',
                '{
                    "dependencies": {"bar": "foo"}
                }'
            ),
            array(
                '{"foo": 1}',
                '{
                    "dependencies": {"bar": "foo"}
                }'
            ),
            array(
                '"foo"',
                '{
                    "dependencies": {"bar": "foo"}
                }'
            ),
            array(
                '{"bar": 1, "foo": 1}',
                '{
                    "dependencies": {"bar": "foo"}
                }'
            ),
            array(
                '{"bar": 1, "foo": 1, "baz": 1}',
                '{
                    "dependencies": {"bar": ["foo", "baz"]}
                }'
            ),
            array(
                '{}',
                '{
                    "dependencies": {"bar": ["foo", "baz"]}
                }'
            ),
            array(
                '{"foo": 1, "baz": 1}',
                '{
                    "dependencies": {"bar": ["foo", "baz"]}
                }'
            ),
            array(
                '{"bar": 1}',
                '{
                    "dependencies": {"bar": {
                        "properties": {
                            "foo": {"type": "integer"}
                        }
                    }}
                }'
            ),
            array(
                '{"bar": 1, "foo": 1}',
                '{
                    "dependencies": {"bar": {
                        "properties": {
                            "bar": {"type": "integer"},
                            "foo": {"type": "integer"}
                        }
                    }}
                }'
            )
        );
    }
}