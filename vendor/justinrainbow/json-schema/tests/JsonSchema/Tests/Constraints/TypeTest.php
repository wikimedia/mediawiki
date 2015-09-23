<?php

/*
 * This file is part of the JsonSchema package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JsonSchema\Tests\Constraints;

use JsonSchema\Constraints\TypeConstraint;

/**
 * Class TypeTest
 *
 * @package JsonSchema\Tests\Constraints
 * @author hakre <https://github.com/hakre>
 */
class TypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @see testIndefiniteArticleForTypeInTypeCheckErrorMessage
     * @return array
     */
    public function provideIndefiniteArticlesForTypes()
    {
        return array(
            array('integer', 'an',),
            array('number', 'a',),
            array('boolean', 'a',),
            array('object', 'an',),
            array('array', 'an',),
            array('string', 'a',),
            array('null', 'a', array(), 'array',),
        );
    }

    /**
     * @dataProvider provideIndefiniteArticlesForTypes
     */
    public function testIndefiniteArticleForTypeInTypeCheckErrorMessage($type, $wording, $value = null, $label = 'NULL')
    {
        $constraint = new TypeConstraint();
        $constraint->check($value, (object)array('type' => $type));
        $this->assertTypeConstraintError("$label value found, but $wording $type is required", $constraint);
    }

    /**
     * Helper to assert an error message
     *
     * @param string $expected
     * @param TypeConstraint $actual
     */
    private function assertTypeConstraintError($expected, TypeConstraint $actual)
    {
        $actualErrors = $actual->getErrors();

        $this->assertCount(1, $actualErrors, "Failed to assert that Type has exactly one error to assert the error message against.");

        $actualError = $actualErrors[0];

        $this->assertInternalType('array', $actualError, sprintf('Failed to assert that Type error is an array, %s given', gettype($actualError)));

        $messageKey = 'message';
        $this->assertArrayHasKey(
            $messageKey, $actualError,
            sprintf('Failed to assert that Type error has a message key %s.', var_export($messageKey, true))
        );

        $actualMessage = $actualError[$messageKey];

        $this->assertEquals($expected, $actualMessage); // first equal for the diff
        $this->assertSame($expected, $actualMessage); // the same for the strictness
    }
}
