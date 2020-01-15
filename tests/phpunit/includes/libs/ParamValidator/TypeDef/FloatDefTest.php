<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\ValidationException;

/**
 * @covers Wikimedia\ParamValidator\TypeDef\FloatDef
 */
class FloatDefTest extends TypeDefTestCase {

	protected static $testClass = FloatDef::class;

	public function provideValidate() {
		return [
			[ '123', 123.0 ],
			[ '123.4', 123.4 ],
			[ '0.4', 0.4 ],
			[ '.4', 0.4 ],

			[ '+123', 123.0 ],
			[ '+123.4', 123.4 ],
			[ '+0.4', 0.4 ],
			[ '+.4', 0.4 ],

			[ '-123', -123.0 ],
			[ '-123.4', -123.4 ],
			[ '-.4', -0.4 ],
			[ '-.4', -0.4 ],

			[ '123e5', 12300000.0 ],
			[ '123E5', 12300000.0 ],
			[ '123.4e+5', 12340000.0 ],
			[ '123E5', 12300000.0 ],
			[ '-123.4e-5', -0.001234 ],
			[ '.4E-5', 0.000004 ],

			[ '0', 0 ],
			[ '000000', 0 ],
			[ '0000.0000', 0 ],
			[ '000001.0002000000', 1.0002 ],
			[ '1e0', 1 ],
			[ '1e-0000', 1 ],
			[ '1e+00010', 1e10 ],

			'Weird, but ok' => [ '-0', 0 ],
			'Underflow is ok' => [ '1e-9999', 0 ],

			'Empty decimal part' => [ '1.', new ValidationException(
				DataMessageValue::new( 'paramvalidator-badfloat', [], 'badfloat' ),
				'test', '1.', []
			) ],
			'Bad sign' => [ ' 1', new ValidationException(
				DataMessageValue::new( 'paramvalidator-badfloat', [], 'badfloat' ),
				'test', ' 1', []
			) ],
			'Comma as decimal separator or thousands grouping?' => [ '1,234', new ValidationException(
				DataMessageValue::new( 'paramvalidator-badfloat', [], 'badfloat' ),
				'test', '1,234', []
			) ],
			'U+2212 minus' => [ '−1', new ValidationException(
				DataMessageValue::new( 'paramvalidator-badfloat', [], 'badfloat' ),
				'test', '−1', []
			) ],
			'Overflow' => [ '1e9999', new ValidationException(
				DataMessageValue::new( 'paramvalidator-badfloat-notfinite', [], 'badfloat-notfinite' ),
				'test', '1e9999', []
			) ],
			'Overflow, -INF' => [ '-1e9999', new ValidationException(
				DataMessageValue::new( 'paramvalidator-badfloat-notfinite', [], 'badfloat-notfinite' ),
				'test', '-1e9999', []
			) ],
			'Bogus value' => [ 'foo', new ValidationException(
				DataMessageValue::new( 'paramvalidator-notfinite', [], 'badfloat' ),
				'test', 'foo', []
			) ],
			'Bogus value' => [ '123f4', new ValidationException(
				DataMessageValue::new( 'paramvalidator-notfinite', [], 'badfloat' ),
				'test', '123f4', []
			) ],
			'Newline' => [ "123\n", new ValidationException(
				DataMessageValue::new( 'paramvalidator-notfinite', [], 'badfloat' ),
				'test', "123\n", []
			) ],
		];
	}

	public function provideCheckSettings() {
		$keys = [
			'Y', FloatDef::PARAM_IGNORE_RANGE,
			FloatDef::PARAM_MIN, FloatDef::PARAM_MAX, FloatDef::PARAM_MAX2
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
					FloatDef::PARAM_IGNORE_RANGE => true,
					FloatDef::PARAM_MIN => -100.0,
					FloatDef::PARAM_MAX => -90.0,
					FloatDef::PARAM_MAX2 => -80.0,
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
					FloatDef::PARAM_IGNORE_RANGE => 1,
					FloatDef::PARAM_MIN => 1,
					FloatDef::PARAM_MAX => '2',
					FloatDef::PARAM_MAX2 => '3',
				],
				self::STDRET,
				[
					'issues' => [
						'X',
						FloatDef::PARAM_IGNORE_RANGE => 'PARAM_IGNORE_RANGE must be boolean, got integer',
						FloatDef::PARAM_MIN => 'PARAM_MIN must be double, got integer',
						FloatDef::PARAM_MAX => 'PARAM_MAX must be double, got string',
						FloatDef::PARAM_MAX2 => 'PARAM_MAX2 must be double, got string',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
		];
	}

	public function provideStringifyValue() {
		$digits = defined( 'PHP_FLOAT_DIG' ) ? PHP_FLOAT_DIG : 15;

		return [
			[ 1.2, '1.2' ],
			[ 10 / 3, '3.' . str_repeat( '3', $digits - 1 ) ],
			[ 1e100, '1.0e+100' ],
			[ 6.022e-23, '6.022e-23' ],
		];
	}

	/** @dataProvider provideLocales */
	public function testStringifyValue_localeWeirdness( $locale ) {
		static $cats = [ LC_ALL, LC_MONETARY, LC_NUMERIC ];

		$curLocales = [];
		foreach ( $cats as $c ) {
			$curLocales[$c] = setlocale( $c, '0' );
			if ( $curLocales[$c] === false ) {
				$this->markTestSkipped( 'Locale support is unavailable' );
			}
		}
		try {
			foreach ( $cats as $c ) {
				if ( setlocale( $c, $locale ) === false ) {
					$this->markTestSkipped( "Locale \"$locale\" is unavailable" );
				}
			}

			$typeDef = $this->getInstance( new SimpleCallbacks( [] ), [] );
			$this->assertSame( '123456.789', $typeDef->stringifyValue( 'test', 123456.789, [], [] ) );
			$this->assertSame( '-123456.789', $typeDef->stringifyValue( 'test', -123456.789, [], [] ) );
			$this->assertSame( '1.0e+20', $typeDef->stringifyValue( 'test', 1e20, [], [] ) );
			$this->assertSame( '1.0e-20', $typeDef->stringifyValue( 'test', 1e-20, [], [] ) );
		} finally {
			foreach ( $curLocales as $c => $v ) {
				setlocale( $c, $v );
			}
		}
	}

	public function provideLocales() {
		return [
			// May as well test these.
			[ 'C' ],
			[ 'C.UTF-8' ],

			// Some hopefullt-common locales with decimal_point = ',' and thousands_sep = '.'
			[ 'de_DE' ],
			[ 'de_DE.utf8' ],
			[ 'es_ES' ],
			[ 'es_ES.utf8' ],

			// This one, on my system at least, has decimal_point as U+066B.
			[ 'ps_AF' ],
		];
	}

	public function provideGetInfo() {
		return [
			'Basic test' => [
				[],
				[ 'min' => null, 'max' => null ],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-float"><text>1</text></message>',
				],
			],
			'Various settings' => [
				[
					FloatDef::PARAM_MIN => 1,
					FloatDef::PARAM_MAX => 100,
					ParamValidator::PARAM_ISMULTI => true
				],
				[ 'min' => 1, 'max' => 100 ],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					FloatDef::PARAM_MIN => '<message key="paramvalidator-help-type-number-minmax"><text>2</text><num>1</num><num>100</num></message>',
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-float"><text>2</text></message>',
				],
			],
		];
	}

}
