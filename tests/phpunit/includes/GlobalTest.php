<?php

class GlobalTest extends MediaWikiTestCase {
	function setUp() {
		global $wgReadOnlyFile, $wgContLang, $wgLang;
		$this->originals['wgReadOnlyFile'] = $wgReadOnlyFile;
		$wgReadOnlyFile = tempnam( wfTempDir(), "mwtest_readonly" );
		unlink( $wgReadOnlyFile );
		$wgContLang = $wgLang = Language::factory( 'en' );
	}

	function tearDown() {
		global $wgReadOnlyFile;
		if ( file_exists( $wgReadOnlyFile ) ) {
			unlink( $wgReadOnlyFile );
		}
		$wgReadOnlyFile = $this->originals['wgReadOnlyFile'];
	}

	function testRandom() {
		# This could hypothetically fail, but it shouldn't ;)
		$this->assertFalse(
			wfRandom() == wfRandom() );
	}

	function testUrlencode() {
		$this->assertEquals(
			"%E7%89%B9%E5%88%A5:Contributions/Foobar",
			wfUrlencode( "\xE7\x89\xB9\xE5\x88\xA5:Contributions/Foobar" ) );
	}

	function testReadOnlyEmpty() {
		global $wgReadOnly;
		$wgReadOnly = null;

		$this->assertFalse( wfReadOnly() );
		$this->assertFalse( wfReadOnly() );
	}

	function testReadOnlySet() {
		global $wgReadOnly, $wgReadOnlyFile;

		$f = fopen( $wgReadOnlyFile, "wt" );
		fwrite( $f, 'Message' );
		fclose( $f );
		$wgReadOnly = null; # Check on $wgReadOnlyFile

		$this->assertTrue( wfReadOnly() );
		$this->assertTrue( wfReadOnly() ); # Check cached

		unlink( $wgReadOnlyFile );
		$wgReadOnly = null; # Clean cache

		$this->assertFalse( wfReadOnly() );
		$this->assertFalse( wfReadOnly() );
	}

	function testQuotedPrintable() {
		$this->assertEquals(
			"=?UTF-8?Q?=C4=88u=20legebla=3F?=",
			UserMailer::quotedPrintable( "\xc4\x88u legebla?", "UTF-8" ) );
	}

	function testTime() {
		$start = wfTime();
		$this->assertInternalType( 'float', $start );
		$end = wfTime();
		$this->assertTrue( $end > $start, "Time is running backwards!" );
	}

	function testArrayToCGI() {
		$this->assertEquals(
			"baz=AT%26T&foo=bar",
			wfArrayToCGI(
				array( 'baz' => 'AT&T', 'ignore' => '' ),
				array( 'foo' => 'bar', 'baz' => 'overridden value' ) ) );
	}

	function testMimeTypeMatch() {
		$this->assertEquals(
			'text/html',
			mimeTypeMatch( 'text/html',
				array( 'application/xhtml+xml' => 1.0,
				       'text/html'             => 0.7,
				       'text/plain'            => 0.3 ) ) );
		$this->assertEquals(
			'text/*',
			mimeTypeMatch( 'text/html',
				array( 'image/*' => 1.0,
				       'text/*'  => 0.5 ) ) );
		$this->assertEquals(
			'*/*',
			mimeTypeMatch( 'text/html',
				array( '*/*' => 1.0 ) ) );
		$this->assertNull(
			mimeTypeMatch( 'text/html',
				array( 'image/png'     => 1.0,
				       'image/svg+xml' => 0.5 ) ) );
	}

	function testNegotiateType() {
		$this->assertEquals(
			'text/html',
			wfNegotiateType(
				array( 'application/xhtml+xml' => 1.0,
				       'text/html'             => 0.7,
				       'text/plain'            => 0.5,
				       'text/*'                => 0.2 ),
				array( 'text/html'             => 1.0 ) ) );
		$this->assertEquals(
			'application/xhtml+xml',
			wfNegotiateType(
				array( 'application/xhtml+xml' => 1.0,
				       'text/html'             => 0.7,
				       'text/plain'            => 0.5,
				       'text/*'                => 0.2 ),
				array( 'application/xhtml+xml' => 1.0,
				       'text/html'             => 0.5 ) ) );
		$this->assertEquals(
			'text/html',
			wfNegotiateType(
				array( 'text/html'             => 1.0,
				       'text/plain'            => 0.5,
				       'text/*'                => 0.5,
				       'application/xhtml+xml' => 0.2 ),
				array( 'application/xhtml+xml' => 1.0,
				       'text/html'             => 0.5 ) ) );
		$this->assertEquals(
			'text/html',
			wfNegotiateType(
				array( 'text/*'                => 1.0,
				       'image/*'               => 0.7,
				       '*/*'                   => 0.3 ),
				array( 'application/xhtml+xml' => 1.0,
				       'text/html'             => 0.5 ) ) );
		$this->assertNull(
			wfNegotiateType(
				array( 'text/*'                => 1.0 ),
				array( 'application/xhtml+xml' => 1.0 ) ) );
	}

	function testTimestamp() {
		$t = gmmktime( 12, 34, 56, 1, 15, 2001 );
		$this->assertEquals(
			'20010115123456',
			wfTimestamp( TS_MW, $t ),
			'TS_UNIX to TS_MW' );
		$this->assertEquals(
			'19690115123456',
			wfTimestamp( TS_MW, -30281104 ),
			'Negative TS_UNIX to TS_MW' );
		$this->assertEquals(
			979562096,
			wfTimestamp( TS_UNIX, $t ),
			'TS_UNIX to TS_UNIX' );
		$this->assertEquals(
			'2001-01-15 12:34:56',
			wfTimestamp( TS_DB, $t ),
			'TS_UNIX to TS_DB' );
		$this->assertEquals(
			'20010115T123456Z',
			wfTimestamp( TS_ISO_8601_BASIC, $t ),
			'TS_ISO_8601_BASIC to TS_DB' );

		$this->assertEquals(
			'20010115123456',
			wfTimestamp( TS_MW, '20010115123456' ),
			'TS_MW to TS_MW' );
		$this->assertEquals(
			979562096,
			wfTimestamp( TS_UNIX, '20010115123456' ),
			'TS_MW to TS_UNIX' );
		$this->assertEquals(
			'2001-01-15 12:34:56',
			wfTimestamp( TS_DB, '20010115123456' ),
			'TS_MW to TS_DB' );
		$this->assertEquals(
			'20010115T123456Z',
			wfTimestamp( TS_ISO_8601_BASIC, '20010115123456' ),
			'TS_MW to TS_ISO_8601_BASIC' );

		$this->assertEquals(
			'20010115123456',
			wfTimestamp( TS_MW, '2001-01-15 12:34:56' ),
			'TS_DB to TS_MW' );
		$this->assertEquals(
			979562096,
			wfTimestamp( TS_UNIX, '2001-01-15 12:34:56' ),
			'TS_DB to TS_UNIX' );
		$this->assertEquals(
			'2001-01-15 12:34:56',
			wfTimestamp( TS_DB, '2001-01-15 12:34:56' ),
			'TS_DB to TS_DB' );
		$this->assertEquals(
			'20010115T123456Z',
			wfTimestamp( TS_ISO_8601_BASIC, '2001-01-15 12:34:56' ),
			'TS_DB to TS_ISO_8601_BASIC' );

		# rfc2822 section 3.3

		$this->assertEquals(
			'Mon, 15 Jan 2001 12:34:56 GMT',
			wfTimestamp( TS_RFC2822, '20010115123456' ),
			'TS_MW to TS_RFC2822' );

		$this->assertEquals(
			'20010115123456',
			wfTimestamp( TS_MW, 'Mon, 15 Jan 2001 12:34:56 GMT' ),
			'TS_RFC2822 to TS_MW' );

		$this->assertEquals(
			'20010115123456',
			wfTimestamp( TS_MW, ' Mon, 15 Jan 2001 12:34:56 GMT' ),
			'TS_RFC2822 with leading space to TS_MW' );

		$this->assertEquals(
			'20010115123456',
			wfTimestamp( TS_MW, '15 Jan 2001 12:34:56 GMT' ),
			'TS_RFC2822 without optional day-of-week to TS_MW' );

		# FWS = ([*WSP CRLF] 1*WSP) / obs-FWS ; Folding white space
		# obs-FWS = 1*WSP *(CRLF 1*WSP) ; Section 4.2
		$this->assertEquals(
			'20010115123456',
			wfTimestamp( TS_MW, 'Mon, 15         Jan 2001 12:34:56 GMT' ),
			'TS_RFC2822 to TS_MW' );

		# WSP = SP / HTAB ; rfc2234
		$this->assertEquals(
			'20010115123456',
			wfTimestamp( TS_MW, "Mon, 15 Jan\x092001 12:34:56 GMT" ),
			'TS_RFC2822 with HTAB to TS_MW' );

		$this->assertEquals(
			'20010115123456',
			wfTimestamp( TS_MW, "Mon, 15 Jan\x09 \x09  2001 12:34:56 GMT" ),
			'TS_RFC2822 with HTAB and SP to TS_MW' );

		$this->assertEquals(
			'19941106084937',
			wfTimestamp( TS_MW, "Sun, 6 Nov 94 08:49:37 GMT" ),
			'TS_RFC2822 with obsolete year to TS_MW' );
	}

	/**
	 * This test checks wfTimestamp() with values outside.
	 * It needs PHP 64 bits or PHP > 5.1.
	 * See r74778 and bug 25451
	 */
	function testOldTimestamps() {
		$this->assertEquals( 'Fri, 13 Dec 1901 20:45:54 GMT',
			wfTimestamp( TS_RFC2822, '19011213204554' ),
			'Earliest time according to php documentation' );

		$this->assertEquals( 'Tue, 19 Jan 2038 03:14:07 GMT',
			wfTimestamp( TS_RFC2822, '20380119031407' ),
			'Latest 32 bit time' );

		$this->assertEquals( '-2147483648',
			wfTimestamp( TS_UNIX, '19011213204552' ),
			'Earliest 32 bit unix time' );

		$this->assertEquals( '2147483647',
			wfTimestamp( TS_UNIX, '20380119031407' ),
			'Latest 32 bit unix time' );

		$this->assertEquals( 'Fri, 13 Dec 1901 20:45:52 GMT',
			wfTimestamp( TS_RFC2822, '19011213204552' ),
			'Earliest 32 bit time' );

		$this->assertEquals( 'Fri, 13 Dec 1901 20:45:51 GMT',
			wfTimestamp( TS_RFC2822, '19011213204551' ),
			'Earliest 32 bit time - 1' );

		$this->assertEquals( 'Tue, 19 Jan 2038 03:14:08 GMT',
			wfTimestamp( TS_RFC2822, '20380119031408' ),
			'Latest 32 bit time + 1' );

		$this->assertEquals( '19011212000000',
			wfTimestamp(TS_MW, '19011212000000'),
			'Convert to itself r74778#c10645' );

		$this->assertEquals( '-2147483649',
			wfTimestamp( TS_UNIX, '19011213204551' ),
			'Earliest 32 bit unix time - 1' );

		$this->assertEquals( '2147483648',
			wfTimestamp( TS_UNIX, '20380119031408' ),
			'Latest 32 bit unix time + 1' );

		$this->assertEquals( '19011213204551',
			wfTimestamp( TS_MW, '-2147483649' ),
			'1901 negative unix time to MediaWiki' );

		$this->assertEquals( '18010115123456',
			wfTimestamp( TS_MW, '-5331871504' ),
			'1801 negative unix time to MediaWiki' );

		$this->assertEquals( 'Tue, 09 Aug 0117 12:34:56 GMT',
			wfTimestamp( TS_RFC2822, '0117-08-09 12:34:56'),
			'Death of Roman Emperor [[Trajan]]');

		/* FIXME: 00 to 101 years are taken as being in [1970-2069] */

		$this->assertEquals( 'Sun, 01 Jan 0101 00:00:00 GMT',
			wfTimestamp( TS_RFC2822, '-58979923200'),
			'1/1/101');

		$this->assertEquals( 'Mon, 01 Jan 0001 00:00:00 GMT',
			wfTimestamp( TS_RFC2822, '-62135596800'),
			'Year 1');

		/* It is not clear if we should generate a year 0 or not
		 * We are completely off RFC2822 requirement of year being
		 * 1900 or later.
		 */
		$this->assertEquals( 'Wed, 18 Oct 0000 00:00:00 GMT',
			wfTimestamp( TS_RFC2822, '-62142076800'),
			'ISO 8601:2004 [[year 0]], also called [[1 BC]]');
	}

	function testHttpDate() {
		# The Resource Loader uses wfTimestamp() to convert timestamps
		# from If-Modified-Since header.
		# Thus it must be able to parse all rfc2616 date formats
		# http://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#sec3.3.1

		$this->assertEquals(
			'19941106084937',
			wfTimestamp( TS_MW, 'Sun, 06 Nov 1994 08:49:37 GMT' ),
			'RFC 822 date' );

		$this->assertEquals(
			'19941106084937',
			wfTimestamp( TS_MW, 'Sunday, 06-Nov-94 08:49:37 GMT' ),
			'RFC 850 date' );

		$this->assertEquals(
			'19941106084937',
			wfTimestamp( TS_MW, 'Sun Nov  6 08:49:37 1994' ),
			"ANSI C's asctime() format" );

		// See http://www.squid-cache.org/mail-archive/squid-users/200307/0122.html and r77171
		$this->assertEquals(
			'20101122141242',
			wfTimestamp( TS_MW, 'Mon, 22 Nov 2010 14:12:42 GMT; length=52626' ),
			"Netscape extension to HTTP/1.0" );

	}

	function testTimestampParameter() {
		// There are a number of assumptions in our codebase where wfTimestamp() should give 
		// the current date but it is not given a 0 there. See r71751 CR

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

	function testBasename() {
		$sets = array(
			'' => '',
			'/' => '',
			'\\' => '',
			'//' => '',
			'\\\\' => '',
			'a' => 'a',
			'aaaa' => 'aaaa',
			'/a' => 'a',
			'\\a' => 'a',
			'/aaaa' => 'aaaa',
			'\\aaaa' => 'aaaa',
			'/aaaa/' => 'aaaa',
			'\\aaaa\\' => 'aaaa',
			'\\aaaa\\' => 'aaaa',
			'/mnt/upload3/wikipedia/en/thumb/8/8b/Zork_Grand_Inquisitor_box_cover.jpg/93px-Zork_Grand_Inquisitor_box_cover.jpg' => '93px-Zork_Grand_Inquisitor_box_cover.jpg',
			'C:\\Progra~1\\Wikime~1\\Wikipe~1\\VIEWER.EXE' => 'VIEWER.EXE',
			'Östergötland_coat_of_arms.png' => 'Östergötland_coat_of_arms.png',
			);
		foreach ( $sets as $from => $to ) {
			$this->assertEquals( $to, wfBaseName( $from ),
				"wfBaseName('$from') => '$to'" );
		}
	}
	
	
	function testFallbackMbstringFunctions() {
		
		if( !extension_loaded( 'mbstring' ) ) {
			$this->markTestSkipped( "The mb_string functions must be installed to test the fallback functions" );
		}
		
		$sampleUTF = "Östergötland_coat_of_arms.png";
		
		
		//mb_substr
		$substr_params = array(
			array( 0, 0 ),
			array( 5, -4 ),
			array( 33 ),
			array( 100, -5 ),
			array( -8, 10 ),
			array( 1, 1 ),
			array( 2, -1 )
		);
		
		foreach( $substr_params as $param_set ) {
			$old_param_set = $param_set;
			array_unshift( $param_set, $sampleUTF );
			
			$this->assertEquals(
				call_user_func_array( 'mb_substr', $param_set ),
				call_user_func_array( 'fallback_mb_substr', $param_set ),
				'Fallback mb_substr with params ' . implode( ', ', $old_param_set )
			);			
		}
		
		
		//mb_strlen
		$this->assertEquals(
			mb_strlen( $sampleUTF ),
			fallback_mb_strlen( $sampleUTF ),
			'Fallback mb_strlen'
		);			
		
		
		//mb_str(r?)pos
		$strpos_params = array(
			//array( 'ter' ),
			//array( 'Ö' ),
			//array( 'Ö', 3 ),
			//array( 'oat_', 100 ),
			//array( 'c', -10 ),
			//Broken for now
		);
		
		foreach( $strpos_params as $param_set ) {
			$old_param_set = $param_set;
			array_unshift( $param_set, $sampleUTF );
			
			$this->assertEquals(
				call_user_func_array( 'mb_strpos', $param_set ),
				call_user_func_array( 'fallback_mb_strpos', $param_set ),
				'Fallback mb_strpos with params ' . implode( ', ', $old_param_set )
			);		
			
			$this->assertEquals(
				call_user_func_array( 'mb_strrpos', $param_set ),
				call_user_func_array( 'fallback_mb_strrpos', $param_set ),
				'Fallback mb_strrpos with params ' . implode( ', ', $old_param_set )
			);	
		}
		
	}
	
	
	function testDebugFunctionTest() {
		global $wgDebugLogFile;
		
		$old_log_file = $wgDebugLogFile;
		
		$wgDebugLogFile = tempnam( wfTempDir(), 'mw-' );
		
		
		wfDebug( "This is a normal string" );
		$this->assertEquals( "This is a normal string", file_get_contents( $wgDebugLogFile ) );
		unlink( $wgDebugLogFile );
		
		wfDebug( "This is nöt an ASCII string" );
		$this->assertEquals( "This is nöt an ASCII string", file_get_contents( $wgDebugLogFile ) );
		unlink( $wgDebugLogFile );
		
		wfDebug( "\00305This has böth UTF and control chars\003" );
		$this->assertEquals( " 05This has böth UTF and control chars ", file_get_contents( $wgDebugLogFile ) );
		unlink( $wgDebugLogFile );
		
		
		$wgDebugLogFile = $old_log_file;
		
	}

	/* TODO: many more! */
}


