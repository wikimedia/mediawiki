<?php

namespace Wikimedia\Tests\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\ParamValidator\TypeDef\LimitDef;

/**
 * @covers \Wikimedia\ParamValidator\TypeDef\LimitDef
 */
class LimitDefTest extends TypeDefTestCase {

	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		return new LimitDef( $callbacks, $options );
	}

	public static function provideValidate() {
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

	public static function provideNormalizeSettings() {
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

	public static function provideCheckSettings() {
		$keys = [
			'Y', IntegerDef::PARAM_IGNORE_RANGE,
			IntegerDef::PARAM_MIN, IntegerDef::PARAM_MAX, IntegerDef::PARAM_MAX2
		];

		return [
			'Basic test' => [
				[
					LimitDef::PARAM_MAX => 10,
				],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Test with everything' => [
				[
					LimitDef::PARAM_IGNORE_RANGE => true,
					LimitDef::PARAM_MIN => 0,
					LimitDef::PARAM_MAX => 10,
					LimitDef::PARAM_MAX2 => 100,
				],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'PARAM_ISMULTI not allowed' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					LimitDef::PARAM_MAX => 10,
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						ParamValidator::PARAM_ISMULTI => 'PARAM_ISMULTI cannot be used for limit-type parameters',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'PARAM_ISMULTI not allowed, but another ISMULTI issue was already logged' => [
				[
					ParamValidator::PARAM_ISMULTI => true,
					LimitDef::PARAM_MAX => 10,
				],
				[
					'issues' => [
						ParamValidator::PARAM_ISMULTI => 'XXX',
					],
					'allowedKeys' => [ 'Y' ],
					'messages' => [],
				],
				[
					'issues' => [
						ParamValidator::PARAM_ISMULTI => 'XXX',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'PARAM_MIN == 0' => [
				[
					LimitDef::PARAM_MIN => 0,
					LimitDef::PARAM_MAX => 2,
				],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'PARAM_MIN < 0' => [
				[
					LimitDef::PARAM_MIN => -1,
					LimitDef::PARAM_MAX => 2,
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						'PARAM_MIN must be greater than or equal to 0',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'PARAM_MAX is required' => [
				[],
				self::STDRET,
				[
					'issues' => [
						'X',
						'PARAM_MAX must be set',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
		];
	}

	public static function provideGetInfo() {
		return [
			'Basic' => [
				[],
				[ 'min' => 0, 'max' => null ],
				[
					LimitDef::PARAM_MIN => '<message key="paramvalidator-help-type-number-min"><text>1</text><num>0</num><text>∞</text></message>',
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-limit"><text>1</text></message>',
				],
			],
		];
	}

}
