<?php

/**
 * Tests timestamp parsing and output.
 */
class ConvertibleTimestampTest extends PHPUnit_Framework_TestCase {
	/**
	 * @covers ConvertibleTimestamp::__construct
	 */
	public function testConstructWithNoTimestamp() {
		$timestamp = new ConvertibleTimestamp();
		$this->assertInternalType( 'string', $timestamp->getTimestamp() );
		$this->assertNotEmpty( $timestamp->getTimestamp() );
		$this->assertNotEquals( false, strtotime( $timestamp->getTimestamp( TS_MW ) ) );
	}

	/**
	 * @covers ConvertibleTimestamp::__toString
	 */
	public function testToString() {
		$timestamp = new ConvertibleTimestamp( '1406833268' ); // Equivalent to 20140731190108
		$this->assertEquals( '1406833268', $timestamp->__toString() );
	}

	public static function provideValidTimestampDifferences() {
		return [
			[ '1406833268', '1406833269', '00 00 00 01' ],
			[ '1406833268', '1406833329', '00 00 01 01' ],
			[ '1406833268', '1406836929', '00 01 01 01' ],
			[ '1406833268', '1406923329', '01 01 01 01' ],
		];
	}

	/**
	 * @dataProvider provideValidTimestampDifferences
	 * @covers ConvertibleTimestamp::diff
	 */
	public function testDiff( $timestamp1, $timestamp2, $expected ) {
		$timestamp1 = new ConvertibleTimestamp( $timestamp1 );
		$timestamp2 = new ConvertibleTimestamp( $timestamp2 );
		$diff = $timestamp1->diff( $timestamp2 );
		$this->assertEquals( $expected, $diff->format( '%D %H %I %S' ) );
	}

	/**
	 * Test parsing of valid timestamps and outputing to MW format.
	 * @dataProvider provideValidTimestamps
	 * @covers ConvertibleTimestamp::getTimestamp
	 */
	public function testValidParse( $format, $original, $expected ) {
		$timestamp = new ConvertibleTimestamp( $original );
		$this->assertEquals( $expected, $timestamp->getTimestamp( TS_MW ) );
	}

	/**
	 * Test outputting valid timestamps to different formats.
	 * @dataProvider provideValidTimestamps
	 * @covers ConvertibleTimestamp::getTimestamp
	 */
	public function testValidOutput( $format, $expected, $original ) {
		$timestamp = new ConvertibleTimestamp( $original );
		$this->assertEquals( $expected, (string)$timestamp->getTimestamp( $format ) );
	}

	/**
	 * Test an invalid timestamp.
	 * @expectedException TimestampException
	 * @covers ConvertibleTimestamp
	 */
	public function testInvalidParse() {
		new ConvertibleTimestamp( "This is not a timestamp." );
	}

	/**
	 * @dataProvider provideValidTimestamps
	 * @covers ConvertibleTimestamp::convert
	 */
	public function testConvert( $format, $expected, $original ) {
		$this->assertSame( $expected, ConvertibleTimestamp::convert( $format, $original ) );
	}

	/**
	 * Format an invalid timestamp.
	 * @covers ConvertibleTimestamp::convert
	 */
	public function testConvertInvalid() {
		$this->assertSame( false, ConvertibleTimestamp::convert( 'Not a timestamp', 0 ) );
	}

	/**
	 * Test an out of range timestamp
	 * @dataProvider provideOutOfRangeTimestamps
	 * @expectedException TimestampException
	 * @covers       ConvertibleTimestamp
	 */
	public function testOutOfRangeTimestamps( $format, $input ) {
		$timestamp = new ConvertibleTimestamp( $input );
		$timestamp->getTimestamp( $format );
	}

	/**
	 * Test requesting an invalid output format.
	 * @expectedException TimestampException
	 * @covers ConvertibleTimestamp::getTimestamp
	 */
	public function testInvalidOutput() {
		$timestamp = new ConvertibleTimestamp( '1343761268' );
		$timestamp->getTimestamp( 98 );
	}

	/**
	 * Returns a list of valid timestamps in the format:
	 * [ type, timestamp_of_type, timestamp_in_MW ]
	 */
	public static function provideValidTimestamps() {
		return [
			// Various formats
			[ TS_UNIX, '1343761268', '20120731190108' ],
			[ TS_MW, '20120731190108', '20120731190108' ],
			[ TS_DB, '2012-07-31 19:01:08', '20120731190108' ],
			[ TS_ISO_8601, '2012-07-31T19:01:08Z', '20120731190108' ],
			[ TS_ISO_8601_BASIC, '20120731T190108Z', '20120731190108' ],
			[ TS_EXIF, '2012:07:31 19:01:08', '20120731190108' ],
			[ TS_RFC2822, 'Tue, 31 Jul 2012 19:01:08 GMT', '20120731190108' ],
			[ TS_ORACLE, '31-07-2012 19:01:08.000000', '20120731190108' ],
			[ TS_POSTGRES, '2012-07-31 19:01:08 GMT', '20120731190108' ],
			// Some extremes and weird values
			[ TS_ISO_8601, '9999-12-31T23:59:59Z', '99991231235959' ],
			[ TS_UNIX, '-62135596801', '00001231235959' ]
		];
	}

	/**
	 * Returns a list of out of range timestamps in the format:
	 * [ type, timestamp_of_type ]
	 */
	public static function provideOutOfRangeTimestamps() {
		return [
			// Various formats
			[ TS_MW, '-62167219201' ], // -0001-12-31T23:59:59Z
			[ TS_MW, '253402300800' ], // 10000-01-01T00:00:00Z
		];
	}
}
