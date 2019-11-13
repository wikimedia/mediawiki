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

	public function provideNormalizeSettings() {
		return [
			[ [], [ ParamValidator::PARAM_ISMULTI => false ] ],
			[ [ ParamValidator::PARAM_ISMULTI => true ], [ ParamValidator::PARAM_ISMULTI => false ] ],
		];
	}

	public function provideGetInfo() {
		return [
			'Basic test' => [
				[],
				[ 'default' => null ],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-presenceboolean"><text>1</text></message>',
					ParamValidator::PARAM_DEFAULT => null,
				],
			],
		];
	}

}
