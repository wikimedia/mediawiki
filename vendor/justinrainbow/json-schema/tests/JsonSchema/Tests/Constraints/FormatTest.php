<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

use JsonSchema\Constraints\FormatConstraint;

class FormatTest extends BaseTestCase
{
    public function setUp()
    {
        date_default_timezone_set('UTC');
    }
    
    public function testNullThing()
    {
        $validator = new FormatConstraint();
        $schema = new \stdClass;

        $validator->check('10', $schema);
        $this->assertEmpty($validator->getErrors());
    }

    public function testRegex()
    {
        $validator = new FormatConstraint();
        $schema = new \stdClass;
        $schema->format = 'regex';

        $validator->check('\d+', $schema);
        $this->assertEmpty($validator->getErrors());

        $validator->check('^(abc]', $schema);
        $this->assertCount(1, $validator->getErrors());
    }

    /**
     * @dataProvider getValidFormats
     */
    public function testValidFormat($string, $format)
    {
        $validator = new FormatConstraint();
        $schema = new \stdClass;
        $schema->format = $format;

        $validator->check($string, $schema);
        $this->assertEmpty($validator->getErrors());
    }

    /**
     * @dataProvider getInvalidFormats
     */
    public function testInvalidFormat($string, $format)
    {
        $validator = new FormatConstraint();
        $schema = new \stdClass;
        $schema->format = $format;

        $validator->check($string, $schema);
        $this->assertEquals(1, count($validator->getErrors()), 'Expected 1 error');
    }

    public function getValidFormats()
    {
        return array(
            array('2001-01-23', 'date'),
            array('2000-02-29', 'date'),

            array('12:22:01', 'time'),
            array('00:00:00', 'time'),
            array('23:59:59', 'time'),

            array('2000-05-01T12:12:12Z', 'date-time'),
            array('2000-05-01T12:12:12+0100', 'date-time'),
            array('2000-05-01T12:12:12+01:00', 'date-time'),
            array('2000-05-01T12:12:12.123456Z', 'date-time'),

            array('0', 'utc-millisec'),

            array('aqua', 'color'),
            array('black', 'color'),
            array('blue', 'color'),
            array('fuchsia', 'color'),
            array('gray', 'color'),
            array('green', 'color'),
            array('lime', 'color'),
            array('maroon', 'color'),
            array('navy', 'color'),
            array('olive', 'color'),
            array('orange', 'color'),
            array('purple', 'color'),
            array('red', 'color'),
            array('silver', 'color'),
            array('teal', 'color'),
            array('white', 'color'),
            array('yellow', 'color'),
            array('#fff', 'color'),
            array('#00cc00', 'color'),

            array('background: blue', 'style'),
            array('color: #000;', 'style'),

            array('555 320 1212', 'phone'),

            array('http://bluebox.org', 'uri'),

            array('info@something.edu', 'email'),

            array('10.10.10.10', 'ip-address'),
            array('127.0.0.1', 'ip-address'),

            array('::ff', 'ipv6'),

            array('www.example.com', 'host-name'),

            array('anything', '*'),
        );
    }

    public function getInvalidFormats()
    {
        return array(
            array('January 1st, 1910', 'date'),
            array('199-01-1', 'date'),
            array('2012-0-11', 'date'),
            array('2012-10-1', 'date'),

            array('24:01:00', 'time'),
            array('00:00:60', 'time'),
            array('25:00:00', 'time'),

            array('1999-1-11T00:00:00Z', 'date-time'),
            array('1999-01-11T00:00:00+100', 'date-time'),
            array('1999-01-11T00:00:00+1:00', 'date-time'),

            array('-1', 'utc-millisec'),
            array(PHP_INT_MAX, 'utc-millisec'),

            array('grey', 'color'),
            array('#HHH', 'color'),
            array('#000a', 'color'),
            array('#aa', 'color'),

            array('background; blue', 'style'),

            array('1 123 4424', 'phone'),

            array('htt:/bluebox.org', 'uri'),

            array('info@somewhere', 'email'),

            array('256.2.2.2', 'ip-address'),

            array(':::ff', 'ipv6'),

            array('localhost', 'host-name'),
        );
    }

    public function getValidTests()
    {
        return array(
            array(
                '{ "counter": "10" }',
                '{
                    "type": "object",
                    "properties": {
                        "counter": {
                            "type": "string",
                            "format": "regex",
                            "pattern": "[0-9]+"
                        }
                    }
                }'),
        );
    }

    public function getInvalidTests()
    {
        return array(
            array(
                '{ "counter": "blue" }',
                '{
                    "type": "object",
                    "properties": {
                        "counter": {
                            "type": "string",
                            "format": "regex",
                            "pattern": "[0-9]+"
                        }
                    }
                }'
            ),
            array(
                '{ "color": "blueberry" }',
                '{
                    "type": "object",
                    "properties": {
                        "color": {
                            "type": "string",
                            "format": "color"
                        }
                    }
                }'
            )
        );
    }
}
