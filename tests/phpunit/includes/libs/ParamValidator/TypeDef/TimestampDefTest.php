<?php

namespace Wikimedia\ParamValidator\TypeDef;

use Wikimedia\Timestamp\ConvertibleTimestamp;
use Wikimedia\ParamValidator\SimpleCallbacks;
use Wikimedia\ParamValidator\ValidationException;

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
			'Now' => [ 'now', $now ],

			// Warnings
			'Empty' => [ '', $now, [], [], [ [ 'unclearnowtimestamp' ] ] ],
			'Zero' => [ '0', $now, [], [], [ [ 'unclearnowtimestamp' ] ] ],

			// Error handling
			'Bad value' => [
				'bogus',
				new ValidationException( 'test', 'bogus', [], 'badtimestamp', [] ),
			],

			// Formatting
			'=> DateTime' => [ 'now', $now->timestamp, $formatDT ],
			'=> TS_MW' => [ 'now', '20190605195042', $formatMW ],
			'=> TS_MW as default' => [ 'now', '20190605195042', [], [ 'defaultFormat' => TS_MW ] ],
			'=> TS_MW overriding default'
				=> [ 'now', '20190605195042', $formatMW, [ 'defaultFormat' => TS_ISO_8601 ] ],
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

}
