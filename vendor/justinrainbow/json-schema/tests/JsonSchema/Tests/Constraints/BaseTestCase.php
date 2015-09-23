<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

use JsonSchema\RefResolver;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;

abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getInvalidTests
     */
    public function testInvalidCases($input, $schema, $checkMode = Validator::CHECK_MODE_NORMAL, $errors = array())
    {
        $schema = json_decode($schema);

        $refResolver = new RefResolver(new UriRetriever);
        $refResolver->resolve($schema);

        $validator = new Validator($checkMode);

        $validator->check(json_decode($input), $schema);

        if (array() !== $errors) {
            $this->assertEquals($errors, $validator->getErrors(), print_r($validator->getErrors(),true));
        }
        $this->assertFalse($validator->isValid(), print_r($validator->getErrors(), true));
    }

    /**
     * @dataProvider getValidTests
     */
    public function testValidCases($input, $schema, $checkMode = Validator::CHECK_MODE_NORMAL)
    {
        $schema = json_decode($schema);

        $refResolver = new RefResolver(new UriRetriever);
        $refResolver->resolve($schema);

        $validator = new Validator($checkMode);

        $validator->check(json_decode($input), $schema);
        $this->assertTrue($validator->isValid(), print_r($validator->getErrors(), true));
    }

    abstract public function getValidTests();

    abstract public function getInvalidTests();
}
