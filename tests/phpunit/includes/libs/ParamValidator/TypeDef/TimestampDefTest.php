<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Message\DataMessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\ValidationException;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers Wikimedia\ParamValidator\TypeDef\TimestampDef
 */
class TimestampDefTest extends TypeDefTestCase {

	protected static $testClass = TimestampDef::class;

	protected function getInstance( SimpleCallbacks $callbacks, array $options ) {
		if ( static::$testClass === null ) {
			throw new \LogicException( 'Either assign static::$testClass or override ' . __METHOD__ );
		}

		return new static::$testClass( $callbacks, $options );
	}

	/** @dataProvider provideConstructorOptions */
	public function testConstructorOptions( array $options, $ok ) : void {
		if ( $ok ) {
			$this->assertTrue( true ); // dummy
		} else {
			$this->expectException( \InvalidArgumentException::class );
		}
		$this->getInstance( new SimpleCallbacks( [] ), $options );
	}

	public function provideConstructorOptions() : array {
		return [
			'Basic test' => [ [], true ],
			'Default format ConvertibleTimestamp' => [ [ 'defaultFormat' => 'ConvertibleTimestamp' ], true ],
			'Default format DateTime' => [ [ 'defaultFormat' => 'DateTime' ], true ],
			'Default format TS_ISO_8601' => [ [ 'defaultFormat' => TS_ISO_8601 ], true ],
			'Default format invalid (string)' => [ [ 'defaultFormat' => 'foobar' ], false ],
			'Default format invalid (int)' => [ [ 'defaultFormat' => 1000 ], false ],
			'Stringify format ConvertibleTimestamp' => [
				[ 'stringifyFormat' => 'ConvertibleTimestamp' ], false
			],
			'Stringify format DateTime' => [ [ 'stringifyFormat' => 'DateTime' ], false ],
			'Stringify format TS_ISO_8601' => [ [ 'stringifyFormat' => TS_ISO_8601 ], true ],
			'Stringify format invalid (string)' => [ [ 'stringifyFormat' => 'foobar' ], false ],
			'Stringify format invalid (int)' => [ [ 'stringifyFormat' => 1000 ], false ],
		];
	}

	/** @dataProvider provideValidate */
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
		$specific = new ConvertibleTimestamp( 1517630706 );
		$specificMs = new ConvertibleTimestamp( 1517630706.999 );
		$now = new ConvertibleTimestamp( 1559764242 );

		$formatDT = [ TimestampDef::PARAM_TIMESTAMP_FORMAT => 'DateTime' ];
		$formatMW = [ TimestampDef::PARAM_TIMESTAMP_FORMAT => TS_MW ];

		return [
			// We don't try to validate all formats supported by ConvertibleTimestamp, just
			// some of the interesting ones.
			'ISO format' => [ '2018-02-03T04:05:06Z', $specific ],
			'ISO format with TZ' => [ '2018-02-03T00:05:06-04:00', $specific ],
			'ISO format without punctuation' => [ '20180203T040506', $specific ],
			'ISO format with ms' => [ '2018-02-03T04:05:06.999000Z', $specificMs ],
			'ISO format with ms without punctuation' => [ '20180203T040506.999', $specificMs ],
			'MW format' => [ '20180203040506', $specific ],
			'Generic format' => [ '2018-02-03 04:05:06', $specific ],
			'Generic format + GMT' => [ '2018-02-03 04:05:06 GMT', $specific ],
			'Generic format + TZ +0100' => [ '2018-02-03 05:05:06+0100', $specific ],
			'Generic format + TZ -01' => [ '2018-02-03 03:05:06-01', $specific ],
			'Seconds-since-epoch format' => [ '1517630706', $specific ],
			'Seconds-since-epoch format with ms' => [ '1517630706.9990', $specificMs ],
			'Now' => [ 'now', $now ],

			// Warnings
			'Empty' => [ '', $now, [], [], [ [ 'code' => 'unclearnowtimestamp', 'data' => null ] ] ],
			'Zero' => [ '0', $now, [], [], [ [ 'code' => 'unclearnowtimestamp', 'data' => null ] ] ],

			// Error handling
			'Bad value' => [
				'bogus',
				new ValidationException(
					DataMessageValue::new( 'paramvalidator-badtimestamp', [], 'badtimestamp' ),
					'test', 'bogus', []
				),
			],

			// Formatting
			'=> DateTime' => [ 'now', $now->timestamp, $formatDT ],
			'=> TS_MW' => [ 'now', '20190605195042', $formatMW ],
			'=> TS_MW as default' => [ 'now', '20190605195042', [], [ 'defaultFormat' => TS_MW ] ],
			'=> TS_MW overriding default'
				=> [ 'now', '20190605195042', $formatMW, [ 'defaultFormat' => TS_ISO_8601 ] ],
		];
	}

	public function provideCheckSettings() {
		$keys = [ 'Y', TimestampDef::PARAM_TIMESTAMP_FORMAT ];

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
			'Test with format ConvertibleTimestamp' => [
				[ TimestampDef::PARAM_TIMESTAMP_FORMAT => 'ConvertibleTimestamp' ],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Test with format DateTime' => [
				[ TimestampDef::PARAM_TIMESTAMP_FORMAT => 'DateTime' ],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Test with format TS_ISO_8601' => [
				[ TimestampDef::PARAM_TIMESTAMP_FORMAT => TS_ISO_8601 ],
				self::STDRET,
				[
					'issues' => [ 'X' ],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Test with invalid format (string)' => [
				[ TimestampDef::PARAM_TIMESTAMP_FORMAT => 'foobar' ],
				self::STDRET,
				[
					'issues' => [
						'X',
						TimestampDef::PARAM_TIMESTAMP_FORMAT => 'Value for PARAM_TIMESTAMP_FORMAT is not valid',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
			'Test with invalid format (int)' => [
				[ TimestampDef::PARAM_TIMESTAMP_FORMAT => 1000 ],
				self::STDRET,
				[
					'issues' => [
						'X',
						TimestampDef::PARAM_TIMESTAMP_FORMAT => 'Value for PARAM_TIMESTAMP_FORMAT is not valid',
					],
					'allowedKeys' => $keys,
					'messages' => [],
				],
			],
		];
	}

	public function provideStringifyValue() {
		$specific = new ConvertibleTimestamp( '20180203040506' );

		return [
			[ '20180203040506', '2018-02-03T04:05:06Z' ],
			[ $specific, '2018-02-03T04:05:06Z' ],
			[ $specific->timestamp, '2018-02-03T04:05:06Z' ],
			[ $specific, '20180203040506', [], [ 'stringifyFormat' => TS_MW ] ],
		];
	}

	public function provideGetInfo() {
		return [
			'Basic test' => [
				[],
				[],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-timestamp"><text>1</text></message>',
				],
			],
			'Multi-valued' => [
				[ ParamValidator::PARAM_ISMULTI => true ],
				[],
				[
					// phpcs:ignore Generic.Files.LineLength.TooLong
					ParamValidator::PARAM_TYPE => '<message key="paramvalidator-help-type-timestamp"><text>2</text></message>',
				],
			],
		];
	}

}
