<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\ParamValidator;

/**
 * @covers Wikimedia\ParamValidator\TypeDef\PresenceBooleanDef
 */
class PresenceBooleanDefTest extends TypeDefTestCase {

	protected static $testClass = PresenceBooleanDef::class;

	public function provideValidate() {
		return [
			[ null, false ],
			[ '', true ],
			[ '0', true ],
			[ '1', true ],
			[ 'anything really', true ],
		];
	}

	public function provideDescribeSettings() {
		return [
			[ [], [], [] ],
			[ [ ParamValidator::PARAM_DEFAULT => 'foo' ], [], [] ],
		];
	}

}
