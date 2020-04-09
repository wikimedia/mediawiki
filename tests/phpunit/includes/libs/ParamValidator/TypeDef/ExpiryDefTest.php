<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\ValidationException;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \Wikimedia\ParamValidator\TypeDef\ExpiryDef
 */
class ExpiryDefTest extends TypeDefTestCase {

	protected static $testClass = ExpiryDef::class;

	/**
	 * Get an entry for the provideValidate() provider, where a given value
	 * is asserted to cause a ValidationException with the given message.
	 * @param string $value
	 * @param string $msg
	 * @return array
	 */
	private function getValidationAssertion( string $value, string $msg ) {
		return [
			$value,
			new ValidationException(
				DataMessageValue::new( $msg ),
				'expiry',
				$value,
				[]
			)
		];
	}

	/**
	 * @dataProvider provideValidate
	 * @inheritDoc
	 */
	public function testValidate(
		$value, $expect, array $settings = [], array $options = [], array $expectConds = []
	) {
		$reset = ConvertibleTimestamp::setFakeTime( 1559764242 );
		try {
			parent::testValidate( $value, $expect, $settings, $options, $expectConds );
		} finally {
			ConvertibleTimestamp::setFakeTime( $reset );
		}
	}

	public function provideValidate() {
		return [
			'Valid infinity' => [ 'indefinite', 'infinity' ],
			'Invalid expiry' => $this->getValidationAssertion( 'foobar', 'badexpiry' ),
			'Expiry in past' => $this->getValidationAssertion( '20150123T12:34:56Z', 'badexpiry-past' ),
			'Expiry in past with unix 0' => $this->getValidationAssertion(
				'1970-01-01T00:00:00Z',
				'badexpiry-past'
			),
			'Expiry in past with negative unix time' => $this->getValidationAssertion(
				'1969-12-31T23:59:59Z',
				'badexpiry-past'
			),
			'Valid expiry' => [
				'99990123123456',
				'9999-01-23T12:34:56Z'
			],
			'Valid relative expiry' => [
				'1 month',
				'2019-07-05T19:50:42Z'
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
			ExpiryDef::normalizeExpiry( '205001010000' )
		);
		$this->assertSame(
			'1970-01-01T00:00:00Z',
			ExpiryDef::normalizeExpiry( '1970-01-01T00:00:00Z' )
		);
		$this->assertFalse( ExpiryDef::normalizeExpiry( 0 ) );
	}

	public function provideGetInfo() {
		return [
			'Basic' => [
				[],
				[],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					'param-type' => '<message key="paramvalidator-help-type-expiry"><text>1</text><list listType="text"><text>&quot;infinite&quot;</text><text>&quot;indefinite&quot;</text><text>&quot;infinity&quot;</text><text>&quot;never&quot;</text></list></message>'
				]
			]
		];
	}

}
