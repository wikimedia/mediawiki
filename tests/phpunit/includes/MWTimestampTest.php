<?php

/**
 * Tests timestamp parsing and output.
 */
class MWTimestampTest extends MediaWikiLangTestCase {

	protected function setUp() {
		parent::setUp();

		// Avoid 'GetHumanTimestamp' hook and others
		$this->setMwGlobals( 'wgHooks', [] );
	}

	/**
	 * @covers MWTimestamp::__construct
	 */
	public function testConstructWithNoTimestamp() {
		$timestamp = new MWTimestamp();
		$this->assertInternalType( 'string', $timestamp->getTimestamp() );
		$this->assertNotEmpty( $timestamp->getTimestamp() );
		$this->assertNotEquals( false, strtotime( $timestamp->getTimestamp( TS_MW ) ) );
	}

	/**
	 * @covers MWTimestamp::__toString
	 */
	public function testToString() {
		$timestamp = new MWTimestamp( '1406833268' ); // Equivalent to 20140731190108
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
	 * @covers MWTimestamp::diff
	 */
	public function testDiff( $timestamp1, $timestamp2, $expected ) {
		$timestamp1 = new MWTimestamp( $timestamp1 );
		$timestamp2 = new MWTimestamp( $timestamp2 );
		$diff = $timestamp1->diff( $timestamp2 );
		$this->assertEquals( $expected, $diff->format( '%D %H %I %S' ) );
	}

	/**
	 * Test parsing of valid timestamps and outputing to MW format.
	 * @dataProvider provideValidTimestamps
	 * @covers MWTimestamp::getTimestamp
	 */
	public function testValidParse( $format, $original, $expected ) {
		$timestamp = new MWTimestamp( $original );
		$this->assertEquals( $expected, $timestamp->getTimestamp( TS_MW ) );
	}

	/**
	 * Test outputting valid timestamps to different formats.
	 * @dataProvider provideValidTimestamps
	 * @covers MWTimestamp::getTimestamp
	 */
	public function testValidOutput( $format, $expected, $original ) {
		$timestamp = new MWTimestamp( $original );
		$this->assertEquals( $expected, (string)$timestamp->getTimestamp( $format ) );
	}

	/**
	 * Test an invalid timestamp.
	 * @expectedException TimestampException
	 * @covers MWTimestamp
	 */
	public function testInvalidParse() {
		new MWTimestamp( "This is not a timestamp." );
	}

	/**
	 * Test an out of range timestamp
	 * @dataProvider provideOutOfRangeTimestamps
	 * @expectedException TimestampException
	 * @covers MWTimestamp
	 */
	public function testOutOfRangeTimestamps( $format, $input ) {
		$timestamp = new MWTimestamp( $input );
		$timestamp->getTimestamp( $format );
	}

	/**
	 * Test requesting an invalid output format.
	 * @expectedException TimestampException
	 * @covers MWTimestamp::getTimestamp
	 */
	public function testInvalidOutput() {
		$timestamp = new MWTimestamp( '1343761268' );
		$timestamp->getTimestamp( 98 );
	}

	/**
	 * Returns a list of valid timestamps in the format:
	 * array( type, timestamp_of_type, timestamp_in_MW )
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
	 * array( type, timestamp_of_type )
	 */
	public static function provideOutOfRangeTimestamps() {
		return [
			// Various formats
			[ TS_MW, '-62167219201' ], // -0001-12-31T23:59:59Z
			[ TS_MW, '253402300800' ], // 10000-01-01T00:00:00Z
		];
	}

	/**
	 * @dataProvider provideHumanTimestampTests
	 * @covers MWTimestamp::getHumanTimestamp
	 */
	public function testHumanTimestamp(
		$tsTime, // The timestamp to format
		$currentTime, // The time to consider "now"
		$timeCorrection, // The time offset to use
		$dateFormat, // The date preference to use
		$expectedOutput, // The expected output
		$desc // Description
	) {
		$user = $this->getMock( 'User' );
		$user->expects( $this->any() )
			->method( 'getOption' )
			->with( 'timecorrection' )
			->will( $this->returnValue( $timeCorrection ) );

		$user->expects( $this->any() )
			->method( 'getDatePreference' )
			->will( $this->returnValue( $dateFormat ) );

		$tsTime = new MWTimestamp( $tsTime );
		$currentTime = new MWTimestamp( $currentTime );

		$this->assertEquals(
			$expectedOutput,
			$tsTime->getHumanTimestamp( $currentTime, $user ),
			$desc
		);
	}

	public static function provideHumanTimestampTests() {
		return [
			[
				'20111231170000',
				'20120101000000',
				'Offset|0',
				'mdy',
				'Yesterday at 17:00',
				'"Yesterday" across years',
			],
			[
				'20120717190900',
				'20120717190929',
				'Offset|0',
				'mdy',
				'just now',
				'"Just now"',
			],
			[
				'20120717190900',
				'20120717191530',
				'Offset|0',
				'mdy',
				'6 minutes ago',
				'X minutes ago',
			],
			[
				'20121006173100',
				'20121006173200',
				'Offset|0',
				'mdy',
				'1 minute ago',
				'"1 minute ago"',
			],
			[
				'20120617190900',
				'20120717190900',
				'Offset|0',
				'mdy',
				'June 17',
				'Another month'
			],
			[
				'19910130151500',
				'20120716193700',
				'Offset|0',
				'mdy',
				'15:15, January 30, 1991',
				'Different year',
			],
			[
				'20120101050000',
				'20120101080000',
				'Offset|-360',
				'mdy',
				'Yesterday at 23:00',
				'"Yesterday" across years with time correction',
			],
			[
				'20120714184300',
				'20120716184300',
				'Offset|-420',
				'mdy',
				'Saturday at 11:43',
				'Recent weekday with time correction',
			],
			[
				'20120714184300',
				'20120715040000',
				'Offset|-420',
				'mdy',
				'11:43',
				'Today at another time with time correction',
			],
			[
				'20120617190900',
				'20120717190900',
				'Offset|0',
				'dmy',
				'17 June',
				'Another month with dmy'
			],
			[
				'20120617190900',
				'20120717190900',
				'Offset|0',
				'ISO 8601',
				'06-17',
				'Another month with ISO-8601'
			],
			[
				'19910130151500',
				'20120716193700',
				'Offset|0',
				'ISO 8601',
				'1991-01-30T15:15:00',
				'Different year with ISO-8601',
			],
		];
	}

	/**
	 * @dataProvider provideRelativeTimestampTests
	 * @covers MWTimestamp::getRelativeTimestamp
	 */
	public function testRelativeTimestamp(
		$tsTime, // The timestamp to format
		$currentTime, // The time to consider "now"
		$timeCorrection, // The time offset to use
		$dateFormat, // The date preference to use
		$expectedOutput, // The expected output
		$desc // Description
	) {
		$user = $this->getMock( 'User' );
		$user->expects( $this->any() )
			->method( 'getOption' )
			->with( 'timecorrection' )
			->will( $this->returnValue( $timeCorrection ) );

		$tsTime = new MWTimestamp( $tsTime );
		$currentTime = new MWTimestamp( $currentTime );

		$this->assertEquals(
			$expectedOutput,
			$tsTime->getRelativeTimestamp( $currentTime, $user ),
			$desc
		);
	}

	public static function provideRelativeTimestampTests() {
		return [
			[
				'20111231170000',
				'20120101000000',
				'Offset|0',
				'mdy',
				'7 hours ago',
				'"Yesterday" across years',
			],
			[
				'20120717190900',
				'20120717190929',
				'Offset|0',
				'mdy',
				'29 seconds ago',
				'"Just now"',
			],
			[
				'20120717190900',
				'20120717191530',
				'Offset|0',
				'mdy',
				'6 minutes and 30 seconds ago',
				'Combination of multiple units',
			],
			[
				'20121006173100',
				'20121006173200',
				'Offset|0',
				'mdy',
				'1 minute ago',
				'"1 minute ago"',
			],
			[
				'19910130151500',
				'20120716193700',
				'Offset|0',
				'mdy',
				'2 decades, 1 year, 168 days, 2 hours, 8 minutes and 48 seconds ago',
				'A long time ago',
			],
			[
				'20120101050000',
				'20120101080000',
				'Offset|-360',
				'mdy',
				'3 hours ago',
				'"Yesterday" across years with time correction',
			],
			[
				'20120714184300',
				'20120716184300',
				'Offset|-420',
				'mdy',
				'2 days ago',
				'Recent weekday with time correction',
			],
			[
				'20120714184300',
				'20120715040000',
				'Offset|-420',
				'mdy',
				'9 hours and 17 minutes ago',
				'Today at another time with time correction',
			],
		];
	}
}
