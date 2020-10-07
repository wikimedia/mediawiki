<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers Wikimedia\ParamValidator\TypeDef\IntegerDef
 * @covers Wikimedia\ParamValidator\TypeDef\NumericDef
 */
class IntegerDefTest extends TypeDefTestCase {

	protected static $testClass = IntegerDef::class;

	/**
	 * @param string $v Representing a positive integer
	 * @return string Representing $v + 1
	 */
	private static function plusOne( $v ) {
		for ( $i = strlen( $v ) - 1; $i >= 0; $i-- ) {
			if ( $v[$i] === '9' ) {
				$v[$i] = '0';
			} else {
				$v[$i] = $v[$i] + 1;
				return $v;
			}
		}
		return '1' . $v;
	}

	public function provideValidate() {
		$badinteger = new ValidationException(
			DataMessageValue::new( 'XXX', [], 'badinteger' ),
			'test', '...', []
		);
		$outofrange = new ValidationException(
			DataMessageValue::new( 'XXX', [], 'outofrange', [
				'min' => 0, 'curmax' => 2, 'max' => 2, 'highmax' => 2
			] ),
			'test', '...', []
		);
		$outofrange2 = new ValidationException(
			DataMessageValue::new( 'XXX', [], 'outofrange', [
				'min' => 0, 'curmax' => 2, 'max' => 2, 'highmax' => 4
			] ),
			'test', '...', []
		);
		$outofrange2h = new ValidationException(
			DataMessageValue::new( 'XXX', [], 'outofrange', [
				'min' => 0, 'curmax' => 4, 'max' => 2, 'highmax' => 4
			] ),
			'test', '...', []
		);
		$asWarn = function ( ValidationException $ex ) {
			return [
				'code' => $ex->getFailureMessage()->getCode(),
				'data' => $ex->getFailureMessage()->getData(),
			];
		};

		$minmax = [
			IntegerDef::PARAM_MIN => 0,
			IntegerDef::PARAM_MAX => 2,
		];
		$minmax2 = [
			IntegerDef::PARAM_MIN => 0,
			IntegerDef::PARAM_MAX => 2,
			IntegerDef::PARAM_MAX2 => 4,
		];
		$ignore = [
			IntegerDef::PARAM_IGNORE_RANGE => true,
		];
		$usehigh = [ 'useHighLimits' => true ];

		return [
			[ '123', 123 ],
			[ '-123', -123 ],
			[ '000123', 123 ],
			[ '000', 0 ],
			[ '-0', 0 ],
			[ (string)PHP_INT_MAX, PHP_INT_MAX ],
			[ '0000' . PHP_INT_MAX, PHP_INT_MAX ],
			[ (string)PHP_INT_MIN, PHP_INT_MIN ],
			[ '-0000' . substr( PHP_INT_MIN, 1 ), PHP_INT_MIN ],

			'Overflow' => [ self::plusOne( (string)PHP_INT_MAX ), $badinteger ],
			'Negative overflow' => [ '-' . self::plusOne( substr( PHP_INT_MIN, 1 ) ), $badinteger ],

			'Float' => [ '1.5', $badinteger ],
			'Float (e notation)' => [ '1e1', $badinteger ],
			'Bad sign (space)' => [ ' 1', $badinteger ],
			'Bad sign (newline)' => [ "\n1", $badinteger ],
			'Bogus value' => [ 'max', $badinteger ],
			'Bogus value (2)' => [ '1foo', $badinteger ],
			'Hex value' => [ '0x123', $badinteger ],
			'Newline' => [ "1\n", $badinteger ],

			'Ok with range' => [ '1', 1, $minmax ],
			'Below minimum' => [ '-1', $outofrange, $minmax ],
			'Below minimum, ignored' => [ '-1', 0, $minmax + $ignore, [], [ $asWarn( $outofrange ) ] ],
			'Above maximum' => [ '3', $outofrange, $minmax ],
			'Above maximum, ignored' => [ '3', 2, $minmax + $ignore, [], [ $asWarn( $outofrange ) ] ],
			'Not above max2 but can\'t use it' => [ '3', $outofrange2, $minmax2, [] ],
			'Not above max2 but can\'t use it, ignored'
				=> [ '3', 2, $minmax2 + $ignore, [], [ $asWarn( $outofrange2 ) ] ],
			'Not above max2' => [ '3', 3, $minmax2, $usehigh ],
			'Above max2' => [ '5', $outofrange2h, $minmax2, $usehigh ],
			'Above max2, ignored'
				=> [ '5', 4, $minmax2 + $ignore, $usehigh, [ $asWarn( $outofrange2h ) ] ],
		];
	}

	public function provideNormalizeSettings() {
		return [
			[ [], [] ],
			[
				[ IntegerDef::PARAM_MAX => 2 ],
				[ IntegerDef::PARAM_MAX => 2 ],
			],
			[
				[ IntegerDef::PARAM_MIN => 1, IntegerDef::PARAM_MAX => 2, IntegerDef::PARAM_MAX2 => 4 ],
				[ IntegerDef::PARAM_MIN => 1, IntegerDef::PARAM_MAX => 2, IntegerDef::PARAM_MAX2 => 4 ],
			],
			[
				[ IntegerDef::PARAM_MIN => 1, IntegerDef::PARAM_MAX => 4, IntegerDef::PARAM_MAX2 => 2 ],
				[ IntegerDef::PARAM_MIN => 1, IntegerDef::PARAM_MAX => 4, IntegerDef::PARAM_MAX2 => 4 ],
			],
			[
				[ IntegerDef::PARAM_MAX2 => 2 ],
				[],
			],
		];
	}

	public function provideCheckSettings() {
		$keys = [
			'Y', IntegerDef::PARAM_IGNORE_RANGE,
			IntegerDef::PARAM_MIN, IntegerDef::PARAM_MAX, IntegerDef::PARAM_MAX2
		];

		return [
			'Basic test' => [
				[],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Test with everything' => [
				[
					IntegerDef::PARAM_IGNORE_RANGE => true,
					IntegerDef::PARAM_MIN => -100,
					IntegerDef::PARAM_MAX => -90,
					IntegerDef::PARAM_MAX2 => -80,
				],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Bad types' => [
				[
					IntegerDef::PARAM_IGNORE_RANGE => 1,
					IntegerDef::PARAM_MIN => 1.0,
					IntegerDef::PARAM_MAX => '2',
					IntegerDef::PARAM_MAX2 => '3',
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						IntegerDef::PARAM_IGNORE_RANGE => 'PARAM_IGNORE_RANGE must be boolean, got integer',
						IntegerDef::PARAM_MIN => 'PARAM_MIN must be integer, got double',
						IntegerDef::PARAM_MAX => 'PARAM_MAX must be integer, got string',
						IntegerDef::PARAM_MAX2 => 'PARAM_MAX2 must be integer, got string',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Min == max' => [
				[
					IntegerDef::PARAM_MIN => 1,
					IntegerDef::PARAM_MAX => 1,
				],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Min > max' => [
				[
					IntegerDef::PARAM_MIN => 2,
					IntegerDef::PARAM_MAX => 1,
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						'PARAM_MIN must be less than or equal to PARAM_MAX, but 2 > 1',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Max2 without max' => [
				[
					IntegerDef::PARAM_MAX2 => 1,
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						'PARAM_MAX2 cannot be used without PARAM_MAX',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Max2 == max' => [
				[
					IntegerDef::PARAM_MAX => 1,
					IntegerDef::PARAM_MAX2 => 1,
				],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Max2 < max' => [
				[
					IntegerDef::PARAM_MAX => -10,
					IntegerDef::PARAM_MAX2 => -11,
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						'PARAM_MAX2 must be greater than or equal to PARAM_MAX, but -11 < -10',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
		];
	}

	public function provideGetInfo() {
		return [
			'Basic' => [
				[],
				[ 'min' => null, 'max' => null ],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-integer"><text>1</text></message>',
				],
			],
			'Min' => [
				[ IntegerDef::PARAM_MIN => 0, ParamValidator::PARAM_ISMULTI => true ],
				[ 'min' => 0, 'max' => null ],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					IntegerDef::PARAM_MIN => '<message key="paramvalidator-help-type-number-min"><text>2</text><num>0</num><text>∞</text></message>',
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-integer"><text>2</text></message>',
				],
			],
			'Max' => [
				[ IntegerDef::PARAM_MAX => 2 ],
				[ 'min' => null, 'max' => 2 ],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					IntegerDef::PARAM_MIN => '<message key="paramvalidator-help-type-number-max"><text>1</text><text>−∞</text><num>2</num></message>',
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-integer"><text>1</text></message>',
				],
			],
			'Max2' => [
				[ IntegerDef::PARAM_MAX => 2, IntegerDef::PARAM_MAX2 => 4 ],
				[ 'min' => null, 'max' => 2, 'highmax' => 4 ],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					IntegerDef::PARAM_MIN => '<message key="paramvalidator-help-type-number-max"><text>1</text><text>−∞</text><num>2</num></message>',
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-integer"><text>1</text></message>',
				],
			],
			'Max2, highlimits' => [
				[ IntegerDef::PARAM_MAX => 2, IntegerDef::PARAM_MAX2 => 4 ],
				[ 'min' => null, 'max' => 2, 'highmax' => 4 ],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					IntegerDef::PARAM_MIN => '<message key="paramvalidator-help-type-number-max"><text>1</text><text>−∞</text><num>4</num></message>',
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-integer"><text>1</text></message>',
				],
				[ 'useHighLimits' => true ],
			],
			'Minmax' => [
				[ IntegerDef::PARAM_MIN => 0, IntegerDef::PARAM_MAX => 2 ],
				[ 'min' => 0, 'max' => 2 ],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					IntegerDef::PARAM_MIN => '<message key="paramvalidator-help-type-number-minmax"><text>1</text><num>0</num><num>2</num></message>',
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-integer"><text>1</text></message>',
				],
			],
			'Minmax2' => [
				[ IntegerDef::PARAM_MIN => 0, IntegerDef::PARAM_MAX => 2, IntegerDef::PARAM_MAX2 => 4 ],
				[ 'min' => 0, 'max' => 2, 'highmax' => 4 ],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					IntegerDef::PARAM_MIN => '<message key="paramvalidator-help-type-number-minmax"><text>1</text><num>0</num><num>2</num></message>',
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-integer"><text>1</text></message>',
				],
			],
			'Minmax2, highlimits' => [
				[
					IntegerDef::PARAM_MIN => 0, IntegerDef::PARAM_MAX => 2, IntegerDef::PARAM_MAX2 => 4,
					ParamValidator::PARAM_ISMULTI => true
				],
				[ 'min' => 0, 'max' => 2, 'highmax' => 4 ],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					IntegerDef::PARAM_MIN => '<message key="paramvalidator-help-type-number-minmax"><text>2</text><num>0</num><num>4</num></message>',
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-integer"><text>2</text></message>',
				],
				[ 'useHighLimits' => true ],
			],
		];
	}

}
