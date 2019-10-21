<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers Wikimedia\ParamValidator\TypeDef\IntegerDef
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
		$badinteger = new ValidationException( 'test', '...', [], 'badinteger', [] );
		$belowminimum = new ValidationException(
			'test', '...', [], 'belowminimum', [ 'min' => 0, 'max' => 2, 'max2' => '' ]
		);
		$abovemaximum = new ValidationException(
			'test', '...', [], 'abovemaximum', [ 'min' => 0, 'max' => 2, 'max2' => '' ]
		);
		$abovemaximum2 = new ValidationException(
			'test', '...', [], 'abovemaximum', [ 'min' => 0, 'max' => 2, 'max2' => 4 ]
		);
		$abovehighmaximum = new ValidationException(
			'test', '...', [], 'abovehighmaximum', [ 'min' => 0, 'max' => 2, 'max2' => 4 ]
		);
		$asWarn = function ( ValidationException $ex ) {
			return [ $ex->getFailureCode() ] + $ex->getFailureData();
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
			'Bogus value' => [ 'foo', $badinteger ],
			'Bogus value (2)' => [ '1foo', $badinteger ],
			'Hex value' => [ '0x123', $badinteger ],
			'Newline' => [ "1\n", $badinteger ],

			'Ok with range' => [ '1', 1, $minmax ],
			'Below minimum' => [ '-1', $belowminimum, $minmax ],
			'Below minimum, ignored' => [ '-1', 0, $minmax + $ignore, [], [ $asWarn( $belowminimum ) ] ],
			'Above maximum' => [ '3', $abovemaximum, $minmax ],
			'Above maximum, ignored' => [ '3', 2, $minmax + $ignore, [], [ $asWarn( $abovemaximum ) ] ],
			'Not above max2 but can\'t use it' => [ '3', $abovemaximum2, $minmax2, [] ],
			'Not above max2 but can\'t use it, ignored'
				=> [ '3', 2, $minmax2 + $ignore, [], [ $asWarn( $abovemaximum2 ) ] ],
			'Not above max2' => [ '3', 3, $minmax2, $usehigh ],
			'Above max2' => [ '5', $abovehighmaximum, $minmax2, $usehigh ],
			'Above max2, ignored'
				=> [ '5', 4, $minmax2 + $ignore, $usehigh, [ $asWarn( $abovehighmaximum ) ] ],
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

	public function provideDescribeSettings() {
		return [
			'Basic' => [ [], [], [] ],
			'Default' => [
				[ ParamValidator::PARAM_DEFAULT => 123 ],
				[ 'default' => '123' ],
				[ 'default' => [ 'value' => '123' ] ],
			],
			'Min' => [
				[ ParamValidator::PARAM_DEFAULT => 123, IntegerDef::PARAM_MIN => 0 ],
				[ 'default' => '123', 'min' => 0 ],
				[ 'default' => [ 'value' => '123' ], 'min' => [ 'min' => 0, 'max' => '', 'max2' => '' ] ],
			],
			'Max' => [
				[ IntegerDef::PARAM_MAX => 2 ],
				[ 'max' => 2 ],
				[ 'max' => [ 'min' => '', 'max' => 2, 'max2' => '' ] ],
			],
			'Max2' => [
				[ IntegerDef::PARAM_MAX => 2, IntegerDef::PARAM_MAX2 => 4 ],
				[ 'max' => 2, 'max2' => 4 ],
				[ 'max2' => [ 'min' => '', 'max' => 2, 'max2' => 4 ] ],
			],
			'Minmax' => [
				[ IntegerDef::PARAM_MIN => 0, IntegerDef::PARAM_MAX => 2 ],
				[ 'min' => 0, 'max' => 2 ],
				[ 'minmax' => [ 'min' => 0, 'max' => 2, 'max2' => '' ] ],
			],
			'Minmax2' => [
				[ IntegerDef::PARAM_MIN => 0, IntegerDef::PARAM_MAX => 2, IntegerDef::PARAM_MAX2 => 4 ],
				[ 'min' => 0, 'max' => 2, 'max2' => 4 ],
				[ 'minmax2' => [ 'min' => 0, 'max' => 2, 'max2' => 4 ] ],
			],
		];
	}

}
