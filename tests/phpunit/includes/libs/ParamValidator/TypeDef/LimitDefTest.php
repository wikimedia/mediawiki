<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\ParamValidator;

/**
 * @covers Wikimedia\ParamValidator\TypeDef\LimitDef
 */
class LimitDefTest extends TypeDefTestCase {

	protected static $testClass = LimitDef::class;

	public function provideValidate() {
		$useHigh = [ 'useHighLimits' => true ];
		$max = [ LimitDef::PARAM_MAX => 2 ];
		$max2 = [ LimitDef::PARAM_MAX => 2, LimitDef::PARAM_MAX2 => 4 ];

		yield 'Max' => [ 'max', 2, $max ];
		yield 'Max, use high' => [ 'max', 2, $max, $useHigh ];
		yield 'Max2' => [ 'max', 2, $max2 ];
		yield 'Max2, use high' => [ 'max', 4, $max2, $useHigh ];

		// Test an arbitrary number for coverage. Actual number handling is tested via
		// the base class IntegerDef's tests.
		yield 'A number' => [ '123', 123 ];
	}

	public function provideNormalizeSettings() {
		$def = [ LimitDef::PARAM_MIN => 0, ParamValidator::PARAM_ISMULTI => false ];

		return [
			[
				[],
				$def,
			],
			[
				[ ParamValidator::PARAM_ISMULTI => true ],
				[ ParamValidator::PARAM_ISMULTI => false ] + $def,
			],
			[
				[ LimitDef::PARAM_MAX => 2 ],
				[ LimitDef::PARAM_MAX => 2 ] + $def,
			],
			[
				[ LimitDef::PARAM_MIN => 1, LimitDef::PARAM_MAX => 2, LimitDef::PARAM_MAX2 => 4 ],
				[ LimitDef::PARAM_MIN => 1, LimitDef::PARAM_MAX => 2, LimitDef::PARAM_MAX2 => 4 ] + $def,
			],
			[
				[ LimitDef::PARAM_MIN => 1, LimitDef::PARAM_MAX => 4, LimitDef::PARAM_MAX2 => 2 ],
				[ LimitDef::PARAM_MIN => 1, LimitDef::PARAM_MAX => 4, LimitDef::PARAM_MAX2 => 4 ] + $def,
			],
			[
				[ LimitDef::PARAM_MAX2 => 2 ],
				$def,
			],
		];
	}

	public function provideGetInfo() {
		return [
			'Basic' => [
				[],
				[ 'min' => 0, 'max' => null ],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					LimitDef::PARAM_MIN => '<message key="paramvalidator-help-type-number-min"><text>1</text><num>0</num><text>∞</text></message>',
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-limit"><text>1</text></message>',
				],
			],
		];
	}

}
