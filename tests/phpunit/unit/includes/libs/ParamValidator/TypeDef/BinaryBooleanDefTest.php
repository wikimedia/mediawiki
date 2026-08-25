<?php

namespace Wikimedia\Tests\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\TypeDef\BinaryBooleanDef;

/**
 * BinaryBooleanDef behaves exactly like BooleanDef, except that it injects a
 * false default so an omitted parameter resolves to false rather than null.
 *
 * This test extends BooleanDefTest and overrides only the parts whose contract
 * differs, so the difference between the two types is what's visible here.
 *
 * @covers \Wikimedia\ParamValidator\TypeDef\BinaryBooleanDef
 */
class BinaryBooleanDefTest extends BooleanDefTest {

	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		return new BinaryBooleanDef( $callbacks, $options );
	}

	public static function provideNormalizeSettings() {
		// The one contract difference from BooleanDef: an implicit false default
		// is injected, so an omitted parameter resolves to false instead of null.
		// An explicit default (including null) is preserved.
		return [
			'Injects a false default' => [
				[],
				[ ParamValidator::PARAM_DEFAULT => false ],
			],
			'Adds the default alongside other settings' => [
				[ 'param-foo' => 'bar' ],
				[ 'param-foo' => 'bar', ParamValidator::PARAM_DEFAULT => false ],
			],
			'Preserves an explicit default' => [
				[ ParamValidator::PARAM_DEFAULT => true ],
				[ ParamValidator::PARAM_DEFAULT => true ],
			],
			'Preserves an explicit null default' => [
				[ ParamValidator::PARAM_DEFAULT => null ],
				[ ParamValidator::PARAM_DEFAULT => null ],
			],
		];
	}

}
