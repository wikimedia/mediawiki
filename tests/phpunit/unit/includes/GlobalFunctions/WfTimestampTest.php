<?php

use Wikimedia\Timestamp\ConvertibleTimestamp;
use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * @group GlobalFunctions
 * @covers ::wfTimestamp
 */
class WfTimestampTest extends MediaWikiUnitTestCase {
	/**
	 * @dataProvider provideNormalTimestamps
	 */
	public function testNormalTimestamps( $input, $format, $output, $desc ) {
		$this->assertEquals( $output, wfTimestamp( $format, $input ), $desc );
	}

	public static function provideNormalTimestamps() {
		$t = gmmktime( 12, 34, 56, 1, 15, 2001 );

		return [
			// TS::UNIX
			[ $t, TS::MW, '20010115123456', 'TS::UNIX to TS::MW' ],
			[ -30281104, TS::MW, '19690115123456', 'Negative TS::UNIX to TS::MW' ],
			[ $t, TS::UNIX, 979562096, 'TS::UNIX to TS::UNIX' ],
			[ $t, TS::DB, '2001-01-15 12:34:56', 'TS::UNIX to TS::DB' ],
			[ $t + 0.01, TS::MW, '20010115123456', 'TS::UNIX float to TS::MW' ],

			[ $t, TS::ISO_8601_BASIC, '20010115T123456Z', 'TS::ISO_8601_BASIC to TS::DB' ],

			// TS::MW
			[ '20010115123456', TS::MW, '20010115123456', 'TS::MW to TS::MW' ],
			[ '20010115123456', TS::UNIX, 979562096, 'TS::MW to TS::UNIX' ],
			[ '20010115123456', TS::DB, '2001-01-15 12:34:56', 'TS::MW to TS::DB' ],
			[ '20010115123456', TS::ISO_8601_BASIC, '20010115T123456Z', 'TS::MW to TS::ISO_8601_BASIC' ],

			// TS::DB
			[ '2001-01-15 12:34:56', TS::MW, '20010115123456', 'TS::DB to TS::MW' ],
			[ '2001-01-15 12:34:56', TS::UNIX, 979562096, 'TS::DB to TS::UNIX' ],
			[ '2001-01-15 12:34:56', TS::DB, '2001-01-15 12:34:56', 'TS::DB to TS::DB' ],
			[
				'2001-01-15 12:34:56',
				TS::ISO_8601_BASIC,
				'20010115T123456Z',
				'TS::DB to TS::ISO_8601_BASIC'
			],

			# rfc2822 section 3.3
			[ '20010115123456', TS::RFC2822, 'Mon, 15 Jan 2001 12:34:56 GMT', 'TS::MW to TS::RFC2822' ],
			[ 'Mon, 15 Jan 2001 12:34:56 GMT', TS::MW, '20010115123456', 'TS::RFC2822 to TS::MW' ],
			[
				' Mon, 15 Jan 2001 12:34:56 GMT',
				TS::MW,
				'20010115123456',
				'TS::RFC2822 with leading space to TS::MW'
			],
			[
				'15 Jan 2001 12:34:56 GMT',
				TS::MW,
				'20010115123456',
				'TS::RFC2822 without optional day-of-week to TS::MW'
			],

			# FWS = ([*WSP CRLF] 1*WSP) / obs-FWS ; Folding white space
			# obs-FWS = 1*WSP *(CRLF 1*WSP) ; Section 4.2
			[ 'Mon, 15         Jan 2001 12:34:56 GMT', TS::MW, '20010115123456', 'TS::RFC2822 to TS::MW' ],

			# WSP = SP / HTAB ; rfc2234
			[
				"Mon, 15 Jan\x092001 12:34:56 GMT",
				TS::MW,
				'20010115123456',
				'TS::RFC2822 with HTAB to TS::MW'
			],
			[
				"Mon, 15 Jan\x09 \x09  2001 12:34:56 GMT",
				TS::MW,
				'20010115123456',
				'TS::RFC2822 with HTAB and SP to TS::MW'
			],
			[
				'Sun, 6 Nov 94 08:49:37 GMT',
				TS::MW,
				'19941106084937',
				'TS::RFC2822 with obsolete year to TS::MW'
			],
		];
	}

	/**
	 * This test checks wfTimestamp() with values outside.
	 * It needs PHP 64 bits or PHP > 5.1.
	 * See r74778 and T27451
	 * @dataProvider provideOldTimestamps
	 */
	public function testOldTimestamps( $input, $outputType, $output, $message ) {
		$timestamp = wfTimestamp( $outputType, $input );
		if ( str_starts_with( $output, '/' ) ) {
			// T66946: Day of the week calculations for very old
			// timestamps varies from system to system.
			$this->assertMatchesRegularExpression( $output, $timestamp, $message );
		} else {
			$this->assertEquals( $output, $timestamp, $message );
		}
	}

	public static function provideOldTimestamps() {
		return [
			[
				'19011213204554',
				TS::RFC2822,
				'Fri, 13 Dec 1901 20:45:54 GMT',
				'Earliest time according to PHP documentation'
			],
			[ '20380119031407', TS::RFC2822, 'Tue, 19 Jan 2038 03:14:07 GMT', 'Latest 32 bit time' ],
			[ '19011213204552', TS::UNIX, '-2147483648', 'Earliest 32 bit unix time' ],
			[ '20380119031407', TS::UNIX, '2147483647', 'Latest 32 bit unix time' ],
			[ '19011213204552', TS::RFC2822, 'Fri, 13 Dec 1901 20:45:52 GMT', 'Earliest 32 bit time' ],
			[
				'19011213204551',
				TS::RFC2822,
				'Fri, 13 Dec 1901 20:45:51 GMT', 'Earliest 32 bit time - 1'
			],
			[ '20380119031408', TS::RFC2822, 'Tue, 19 Jan 2038 03:14:08 GMT', 'Latest 32 bit time + 1' ],
			[ '19011212000000', TS::MW, '19011212000000', 'Convert to itself r74778#c10645' ],
			[ '19011213204551', TS::UNIX, '-2147483649', 'Earliest 32 bit unix time - 1' ],
			[ '20380119031408', TS::UNIX, '2147483648', 'Latest 32 bit unix time + 1' ],
			[ '-2147483649', TS::MW, '19011213204551', '1901 negative unix time to MediaWiki' ],
			[ '-5331871504', TS::MW, '18010115123456', '1801 negative unix time to MediaWiki' ],
			[
				'0117-08-09 12:34:56',
				TS::RFC2822,
				'/, 09 Aug 0117 12:34:56 GMT$/',
				'Death of Roman Emperor [[Trajan]]'
			],

			/* @todo FIXME: 00 to 101 years are taken as being in [1970-2069] */
			[ '-58979923200', TS::RFC2822, '/, 01 Jan 0101 00:00:00 GMT$/', '1/1/101' ],
			[ '-62135596800', TS::RFC2822, 'Mon, 01 Jan 0001 00:00:00 GMT', 'Year 1' ],

			/* It is not clear if we should generate a year 0 or not
			 * We are completely off RFC2822 requirement of year being
			 * 1900 or later.
			 */
			[
				'-62142076800',
				TS::RFC2822,
				'Wed, 18 Oct 0000 00:00:00 GMT',
				'ISO 8601:2004 [[year 0]], also called [[1 BC]]'
			],
		];
	}

	/**
	 * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#sec3.3.1
	 * @dataProvider provideHttpDates
	 */
	public function testHttpDate( $input, $output, $desc ) {
		$this->assertEquals( $output, wfTimestamp( TS::MW, $input ), $desc );
	}

	public static function provideHttpDates() {
		return [
			[ 'Sun, 06 Nov 1994 08:49:37 GMT', '19941106084937', 'RFC 822 date' ],
			[ 'Sunday, 06-Nov-94 08:49:37 GMT', '19941106084937', 'RFC 850 date' ],
			[ 'Sun Nov  6 08:49:37 1994', '19941106084937', "ANSI C's asctime() format" ],
			// See http://www.squid-cache.org/mail-archive/squid-users/200307/0122.html and r77171
			[
				'Mon, 22 Nov 2010 14:12:42 GMT; length=52626',
				'20101122141242',
				'Netscape extension to HTTP/1.0'
			],
		];
	}

	/**
	 * There are a number of assumptions in our codebase where wfTimestamp()
	 * should give the current date but it is not given a 0 there. See r71751 CR
	 */
	public function testTimestampParameter() {
		$now = ConvertibleTimestamp::now( TS::UNIX );
		// We check that wfTimestamp doesn't return false (error) and use a LessThan assert
		// for the cases where the test is run in a second boundary.

		$zero = wfTimestamp( TS::UNIX, 0 );
		$this->assertIsString( $zero );
		$this->assertLessThan( 5, $zero - $now );

		$empty = wfTimestamp( TS::UNIX, '' );
		$this->assertIsString( $empty );
		$this->assertLessThan( 5, $empty - $now );

		$null = wfTimestamp( TS::UNIX, null );
		$this->assertIsString( $null );
		$this->assertLessThan( 5, $null - $now );
	}
}
