<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers Wikimedia\ParamValidator\TypeDef\EnumDef
 */
class EnumDefTest extends TypeDefTestCase {

	protected static $testClass = EnumDef::class;

	public function provideValidate() {
		$settings = [
			ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c', 'd' ],
			EnumDef::PARAM_DEPRECATED_VALUES => [
				'b' => [ 'not-to-be' ],
				'c' => true,
			],
		];

		return [
			'Basic' => [ 'a', 'a', $settings ],
			'Deprecated' => [ 'c', 'c', $settings, [], [ [ 'deprecated-value', 'flag' => true ] ] ],
			'Deprecated with message' => [
				'b', 'b', $settings, [],
				[ [ 'deprecated-value', 'flag' => [ 'not-to-be' ] ] ],
			],
			'Bad value, non-multi' => [
				'x', new ValidationException( 'test', 'x', $settings, 'badvalue', [] ),
				$settings,
			],
			'Bad value, non-multi but looks like it' => [
				'x|y', new ValidationException( 'test', 'x|y', $settings, 'notmulti', [] ),
				$settings,
			],
			'Bad value, multi' => [
				'x|y', new ValidationException( 'test', 'x|y', $settings, 'badvalue', [] ),
				$settings + [ ParamValidator::PARAM_ISMULTI => true ],
				[ 'values-list' => [ 'x|y' ] ],
			],
		];
	}

	public function provideGetEnumValues() {
		return [
			'Basic test' => [
				[ ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c', 'd' ] ],
				[ 'a', 'b', 'c', 'd' ],
			],
		];
	}

	public function provideStringifyValue() {
		return [
			'Basic test' => [ 123, '123' ],
			'Array' => [ [ 1, 2, 3 ], '1|2|3' ],
			'Array with pipes' => [ [ 1, 2, '3|4', 5 ], "\x1f1\x1f2\x1f3|4\x1f5" ],
		];
	}

}
