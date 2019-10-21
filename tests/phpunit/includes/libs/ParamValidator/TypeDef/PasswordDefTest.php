<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\ParamValidator;

require_once __DIR__ . '/StringDefTest.php';

/**
 * @covers Wikimedia\ParamValidator\TypeDef\PasswordDef
 */
class PasswordDefTest extends StringDefTest {

	protected static $testClass = PasswordDef::class;

	public function provideNormalizeSettings() {
		return [
			[ [], [ ParamValidator::PARAM_SENSITIVE => true ] ],
			[ [ ParamValidator::PARAM_SENSITIVE => false ], [ ParamValidator::PARAM_SENSITIVE => true ] ],
		];
	}

}
