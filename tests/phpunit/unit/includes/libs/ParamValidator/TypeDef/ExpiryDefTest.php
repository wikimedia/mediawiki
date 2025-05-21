<?php

namespace Wikimedia\Tests\ParamValidator\TypeDef;

use InvalidArgumentException;
use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;
use Wikimedia\ParamValidator\ValidationException;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \Wikimedia\ParamValidator\TypeDef\ExpiryDef
 */
class ExpiryDefTest extends TypeDefTestCase {

	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		return new ExpiryDef( $callbacks, $options );
	}

	/**
	 * Get an entry for the provideValidate() provider, where a given value
	 * is asserted to cause a ValidationException with the given message.
	 * @param string $value
	 * @param string $msg
	 * @param array $settings
	 * @return array
	 */
	private static function getValidationAssertion( string $value, string $msg, array $settings = [] ) {
		return [
			$value,
			new ValidationException(
				DataMessageValue::new( $msg ),
				'expiry',
				$value,
				[]
			),
			$settings
		];
	}

	/**
	 * @dataProvider provideValidate
	 */
	public function testValidate(
		$value, $expect, array $settings = [], array $options = [], array $expectConds = []
	) {
		ConvertibleTimestamp::setFakeTime( 1559764242 );
		parent::testValidate( $value, $expect, $settings, $options, $expectConds );
	}

	public static function provideValidate() {
		$settings = [
			ExpiryDef::PARAM_MAX => '6 months',
			ExpiryDef::PARAM_USE_MAX => true,
		];

		return [
			'Valid infinity' => [ 'indefinite', 'infinity' ],
			'Invalid expiry' => self::getValidationAssertion( 'foobar', 'badexpiry' ),
			'Expiry in past' => self::getValidationAssertion( '20150123T12:34:56Z', 'badexpiry-past' ),
			'Expiry in past with unix 0' => self::getValidationAssertion(
				'1970-01-01T00:00:00Z',
				'badexpiry-past'
			),
			'Expiry in past with negative unix time' => self::getValidationAssertion(
				'1969-12-31T23:59:59Z',
				'badexpiry-past',
				$settings
			),
			'Valid expiry' => [
				'99990123123456',
				'9999-01-23T12:34:56Z'
			],
			'Valid relative expiry' => [
				'1 month',
				'2019-07-05T19:50:42Z'
			],
			'Expiry less than max' => [ '20190701123456', '2019-07-01T12:34:56Z', $settings ],
			'Relative expiry less than max' => [ '1 day', '2019-06-06T19:50:42Z', $settings ],
			'Infinity less than max' => [ 'indefinite', 'infinity', $settings ],
			'Expiry exceeds max' => [
				'9999-01-23T12:34:56Z',
				'2019-12-05T19:50:42Z',
				$settings,
				[],
				[
					[
						'code' => 'paramvalidator-badexpiry-duration-max',
						'data' => null,
					]
				],
			],
			'Relative expiry exceeds max' => [
				'10 years',
				'2019-12-05T19:50:42Z',
				$settings,
				[],
				[
					[
						'code' => 'paramvalidator-badexpiry-duration-max',
						'data' => null,
					]
				],
			],
			'Expiry exceeds max, fatal' => self::getValidationAssertion(
				'9999-01-23T12:34:56Z',
				'paramvalidator-badexpiry-duration',
				[
					ExpiryDef::PARAM_MAX => '6 months',
				]
			),
			'Expiry exceeds Y10K' => self::getValidationAssertion(
				'50000 years',
				'badexpiry'
			),
			'Not a string' => [
				[ 1, 2, 3 ],
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-needstring', [], 'needstring' ),
					'test', '', []
				)
			],
		];
	}

	public function testNormalizeExpiry() {
		$this->assertNull( ExpiryDef::normalizeExpiry( null ) );
		$this->assertSame(
			'infinity',
			ExpiryDef::normalizeExpiry( 'indefinite' )
		);
		$this->assertSame(
			'2050-01-01T00:00:00Z',
			ExpiryDef::normalizeExpiry( '205001010000', TS_ISO_8601 )
		);
		$this->assertSame(
			'1970-01-01T00:00:00Z',
			ExpiryDef::normalizeExpiry( '1970-01-01T00:00:00Z', TS_ISO_8601 )
		);
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Invalid expiry value: 0' );
		ExpiryDef::normalizeExpiry( 0, TS_ISO_8601 );
	}

	public static function provideGetInfo() {
		return [
			'Basic' => [
				[],
				[],
				[
					'param-type' => '<message key="paramvalidator-help-type-expiry"><text>1</text><list listType="text"><text>&quot;infinite&quot;</text><text>&quot;indefinite&quot;</text><text>&quot;infinity&quot;</text><text>&quot;never&quot;</text></list></message>'
				]
			]
		];
	}

	public static function provideCheckSettings() {
		$keys = [ 'Y', ExpiryDef::PARAM_USE_MAX, ExpiryDef::PARAM_MAX ];
		return [
			'Basic test' => [
				[],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			]
		];
	}

	/**
	 * @covers \Wikimedia\ParamValidator\TypeDef\ExpiryDef::normalizeUsingMaxExpiry
	 */
	public function testNormalizeUsingMaxExpiry() {
		// Fake current time to be 2020-05-27T00:00:00Z
		ConvertibleTimestamp::setFakeTime( '20200527000000' );
		$this->assertSame(
			'2020-11-27T00:00:00Z',
			ExpiryDef::normalizeUsingMaxExpiry( '10 months', '6 months', TS_ISO_8601 )
		);
		$this->assertSame(
			'2020-10-27T00:00:00Z',
			ExpiryDef::normalizeUsingMaxExpiry( '2020-10-27T00:00:00Z', '6 months', TS_ISO_8601 )
		);
		$this->assertSame(
			'infinity',
			ExpiryDef::normalizeUsingMaxExpiry( 'infinity', '6 months', TS_ISO_8601 )
		);
		$this->assertNull( ExpiryDef::normalizeUsingMaxExpiry( null, '6 months', TS_ISO_8601 ) );

		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Invalid expiry value: invalid expiry' );
		ExpiryDef::normalizeUsingMaxExpiry( 'invalid expiry', '6 months', TS_ISO_8601 );
	}
}
