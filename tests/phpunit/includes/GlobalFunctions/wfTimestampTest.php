<?php

/**
 * @group GlobalFunctions
 * @covers ::wfTimestamp
 */
class WfTimestampTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideNormalTimestamps
	 */
	public function testNormalTimestamps( $input, $format, $output, $desc ) {
		$this->assertEquals( $output, wfTimestamp( $format, $input ), $desc );
	}

	public static function provideNormalTimestamps() {
		$t = gmmktime( 12, 34, 56, 1, 15, 2001 );

		return array(
			// TS_UNIX
			array( $t, TS_MW, '20010115123456', 'TS_UNIX to TS_MW' ),
			array( -30281104, TS_MW, '19690115123456', 'Negative TS_UNIX to TS_MW' ),
			array( $t, TS_UNIX, 979562096, 'TS_UNIX to TS_UNIX' ),
			array( $t, TS_DB, '2001-01-15 12:34:56', 'TS_UNIX to TS_DB' ),
			array( $t + .01, TS_MW, '20010115123456', 'TS_UNIX float to TS_MW' ),

			array( $t, TS_ISO_8601_BASIC, '20010115T123456Z', 'TS_ISO_8601_BASIC to TS_DB' ),

			// TS_MW
			array( '20010115123456', TS_MW, '20010115123456', 'TS_MW to TS_MW' ),
			array( '20010115123456', TS_UNIX, 979562096, 'TS_MW to TS_UNIX' ),
			array( '20010115123456', TS_DB, '2001-01-15 12:34:56', 'TS_MW to TS_DB' ),
			array( '20010115123456', TS_ISO_8601_BASIC, '20010115T123456Z', 'TS_MW to TS_ISO_8601_BASIC' ),

			// TS_DB
			array( '2001-01-15 12:34:56', TS_MW, '20010115123456', 'TS_DB to TS_MW' ),
			array( '2001-01-15 12:34:56', TS_UNIX, 979562096, 'TS_DB to TS_UNIX' ),
			array( '2001-01-15 12:34:56', TS_DB, '2001-01-15 12:34:56', 'TS_DB to TS_DB' ),
			array(
				'2001-01-15 12:34:56',
				TS_ISO_8601_BASIC,
				'20010115T123456Z',
				'TS_DB to TS_ISO_8601_BASIC'
			),

			# rfc2822 section 3.3
			array( '20010115123456', TS_RFC2822, 'Mon, 15 Jan 2001 12:34:56 GMT', 'TS_MW to TS_RFC2822' ),
			array( 'Mon, 15 Jan 2001 12:34:56 GMT', TS_MW, '20010115123456', 'TS_RFC2822 to TS_MW' ),
			array(
				' Mon, 15 Jan 2001 12:34:56 GMT',
				TS_MW,
				'20010115123456',
				'TS_RFC2822 with leading space to TS_MW'
			),
			array(
				'15 Jan 2001 12:34:56 GMT',
				TS_MW,
				'20010115123456',
				'TS_RFC2822 without optional day-of-week to TS_MW'
			),

			# FWS = ([*WSP CRLF] 1*WSP) / obs-FWS ; Folding white space
			# obs-FWS = 1*WSP *(CRLF 1*WSP) ; Section 4.2
			array( 'Mon, 15         Jan 2001 12:34:56 GMT', TS_MW, '20010115123456', 'TS_RFC2822 to TS_MW' ),

			# WSP = SP / HTAB ; rfc2234
			array(
				"Mon, 15 Jan\x092001 12:34:56 GMT",
				TS_MW,
				'20010115123456',
				'TS_RFC2822 with HTAB to TS_MW'
			),
			array(
				"Mon, 15 Jan\x09 \x09  2001 12:34:56 GMT",
				TS_MW,
				'20010115123456',
				'TS_RFC2822 with HTAB and SP to TS_MW'
			),
			array(
				'Sun, 6 Nov 94 08:49:37 GMT',
				TS_MW,
				'19941106084937',
				'TS_RFC2822 with obsolete year to TS_MW'
			),
		);
	}

	/**
	 * This test checks wfTimestamp() with values outside.
	 * It needs PHP 64 bits or PHP > 5.1.
	 * See r74778 and bug 25451
	 * @dataProvider provideOldTimestamps
	 */
	public function testOldTimestamps( $input, $outputType, $output, $message ) {
		$timestamp = wfTimestamp( $outputType, $input );
		if ( substr( $output, 0, 1 ) === '/' ) {
			// Bug 64946: Day of the week calculations for very old
			// timestamps varies from system to system.
			$this->assertRegExp( $output, $timestamp, $message );
		} else {
			$this->assertEquals( $output, $timestamp, $message );
		}
	}

	public static function provideOldTimestamps() {
		return array(
			array(
				'19011213204554',
				TS_RFC2822,
				'Fri, 13 Dec 1901 20:45:54 GMT',
				'Earliest time according to PHP documentation'
			),
			array( '20380119031407', TS_RFC2822, 'Tue, 19 Jan 2038 03:14:07 GMT', 'Latest 32 bit time' ),
			array( '19011213204552', TS_UNIX, '-2147483648', 'Earliest 32 bit unix time' ),
			array( '20380119031407', TS_UNIX, '2147483647', 'Latest 32 bit unix time' ),
			array( '19011213204552', TS_RFC2822, 'Fri, 13 Dec 1901 20:45:52 GMT', 'Earliest 32 bit time' ),
			array(
				'19011213204551',
				TS_RFC2822,
				'Fri, 13 Dec 1901 20:45:51 GMT', 'Earliest 32 bit time - 1'
			),
			array( '20380119031408', TS_RFC2822, 'Tue, 19 Jan 2038 03:14:08 GMT', 'Latest 32 bit time + 1' ),
			array( '19011212000000', TS_MW, '19011212000000', 'Convert to itself r74778#c10645' ),
			array( '19011213204551', TS_UNIX, '-2147483649', 'Earliest 32 bit unix time - 1' ),
			array( '20380119031408', TS_UNIX, '2147483648', 'Latest 32 bit unix time + 1' ),
			array( '-2147483649', TS_MW, '19011213204551', '1901 negative unix time to MediaWiki' ),
			array( '-5331871504', TS_MW, '18010115123456', '1801 negative unix time to MediaWiki' ),
			array(
				'0117-08-09 12:34:56',
				TS_RFC2822,
				'/, 09 Aug 0117 12:34:56 GMT$/',
				'Death of Roman Emperor [[Trajan]]'
			),

			/* @todo FIXME: 00 to 101 years are taken as being in [1970-2069] */
			array( '-58979923200', TS_RFC2822, '/, 01 Jan 0101 00:00:00 GMT$/', '1/1/101' ),
			array( '-62135596800', TS_RFC2822, 'Mon, 01 Jan 0001 00:00:00 GMT', 'Year 1' ),

			/* It is not clear if we should generate a year 0 or not
			 * We are completely off RFC2822 requirement of year being
			 * 1900 or later.
			 */
			array(
				'-62142076800',
				TS_RFC2822,
				'Wed, 18 Oct 0000 00:00:00 GMT',
				'ISO 8601:2004 [[year 0]], also called [[1 BC]]'
			),
		);
	}

	/**
	 * The Resource Loader uses wfTimestamp() to convert timestamps
	 * from If-Modified-Since header. Thus it must be able to parse all
	 * rfc2616 date formats
	 * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#sec3.3.1
	 * @dataProvider provideHttpDates
	 */
	public function testHttpDate( $input, $output, $desc ) {
		$this->assertEquals( $output, wfTimestamp( TS_MW, $input ), $desc );
	}

	public static function provideHttpDates() {
		return array(
			array( 'Sun, 06 Nov 1994 08:49:37 GMT', '19941106084937', 'RFC 822 date' ),
			array( 'Sunday, 06-Nov-94 08:49:37 GMT', '19941106084937', 'RFC 850 date' ),
			array( 'Sun Nov  6 08:49:37 1994', '19941106084937', "ANSI C's asctime() format" ),
			// See http://www.squid-cache.org/mail-archive/squid-users/200307/0122.html and r77171
			array(
				'Mon, 22 Nov 2010 14:12:42 GMT; length=52626',
				'20101122141242',
				'Netscape extension to HTTP/1.0'
			),
		);
	}

	/**
	 * There are a number of assumptions in our codebase where wfTimestamp()
	 * should give the current date but it is not given a 0 there. See r71751 CR
	 */
	public function testTimestampParameter() {
		$now = wfTimestamp( TS_UNIX );
		// We check that wfTimestamp doesn't return false (error) and use a LessThan assert
		// for the cases where the test is run in a second boundary.

		$zero = wfTimestamp( TS_UNIX, 0 );
		$this->assertNotEquals( false, $zero );
		$this->assertLessThan( 5, $zero - $now );

		$empty = wfTimestamp( TS_UNIX, '' );
		$this->assertNotEquals( false, $empty );
		$this->assertLessThan( 5, $empty - $now );

		$null = wfTimestamp( TS_UNIX, null );
		$this->assertNotEquals( false, $null );
		$this->assertLessThan( 5, $null - $now );
	}
}
