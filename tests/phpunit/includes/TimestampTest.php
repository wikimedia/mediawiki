<?php

/**
 * Tests timestamp parsing and output.
 */
class TimestampTest extends MediaWikiTestCase {
	/**
	 * Test parsing of valid timestamps and outputing to MW format.
	 * @dataProvider provideValidTimestamps
	 */
	function testValidParse( $format, $original, $expected ) {
		$timestamp = new MWTimestamp( $original );
		$this->assertEquals( $expected, $timestamp->getTimestamp( TS_MW ) );
	}

	/**
	 * Test outputting valid timestamps to different formats.
	 * @dataProvider provideValidTimestamps
	 */
	function testValidOutput( $format, $expected, $original ) {
		$timestamp = new MWTimestamp( $original );
		$this->assertEquals( $expected, (string) $timestamp->getTimestamp( $format ) );
	}

	/**
	 * Test an invalid timestamp.
	 * @expectedException TimestampException
	 */
	function testInvalidParse() {
		$timestamp = new MWTimestamp( "This is not a timestamp." );
	}

	/**
	 * Test requesting an invalid output format.
	 * @expectedException TimestampException
	 */
	function testInvalidOutput() {
		$timestamp = new MWTimestamp( '1343761268' );
		$timestamp->getTimestamp( 98 );
	}

	/**
	 * Test human readable timestamp format.
	 */
	function testHumanOutput() {
		$timestamp = new MWTimestamp( time() - 3600 );
		$this->assertEquals( "1 hour ago", $timestamp->getHumanTimestamp()->toString() );
	}

	/**
	 * Returns a list of valid timestamps in the format:
	 * array( type, timestamp_of_type, timestamp_in_MW )
	 */
	function provideValidTimestamps() {
		return array(
			// Various formats
			array( TS_UNIX, '1343761268', '20120731190108' ),
			array( TS_MW, '20120731190108', '20120731190108' ),
			array( TS_DB, '2012-07-31 19:01:08', '20120731190108' ),
			array( TS_ISO_8601, '2012-07-31T19:01:08Z', '20120731190108' ),
			array( TS_ISO_8601_BASIC, '20120731T190108Z', '20120731190108' ),
			array( TS_EXIF, '2012:07:31 19:01:08', '20120731190108' ),
			array( TS_RFC2822, 'Tue, 31 Jul 2012 19:01:08 GMT', '20120731190108' ),
			array( TS_ORACLE, '31-07-2012 19:01:08.000000', '20120731190108' ),
			array( TS_POSTGRES, '2012-07-31 19:01:08 GMT', '20120731190108' ),
			array( TS_DB2, '2012-07-31 19:01:08', '20120731190108' ),
			// Some extremes and weird values
			array( TS_ISO_8601, '9999-12-31T23:59:59Z', '99991231235959' ),
			array( TS_UNIX, '-62135596801', '00001231235959' )
		);
	}
}
