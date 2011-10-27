<?php

class GlobalTest extends MediaWikiTestCase {
	function setUp() {
		global $wgReadOnlyFile, $wgUrlProtocols;
		$this->originals['wgReadOnlyFile'] = $wgReadOnlyFile;
		$this->originals['wgUrlProtocols'] = $wgUrlProtocols;
		$wgReadOnlyFile = tempnam( wfTempDir(), "mwtest_readonly" );
		$wgUrlProtocols[] = 'file://';
		unlink( $wgReadOnlyFile );
	}

	function tearDown() {
		global $wgReadOnlyFile, $wgUrlProtocols;
		if ( file_exists( $wgReadOnlyFile ) ) {
			unlink( $wgReadOnlyFile );
		}
		$wgReadOnlyFile = $this->originals['wgReadOnlyFile'];
		$wgUrlProtocols = $this->originals['wgUrlProtocols'];
	}

	/** @dataProvider provideForWfArrayDiff2 */
	public function testWfArrayDiff2( $a, $b, $expected ) {
		$this->assertEquals(
			wfArrayDiff2( $a, $b), $expected
		);
	}

	// @todo Provide more tests
	public function provideForWfArrayDiff2() {
		// $a $b $expected
		return array(
			array(
				array( 'a', 'b'),
				array( 'a', 'b'),
				array(),
			),
			array(
				array( array( 'a'), array( 'a', 'b', 'c' )),
				array( array( 'a'), array( 'a', 'b' )),
				array( 1 => array( 'a', 'b', 'c' ) ),
			),
		);
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
		$this->assertEquals(
			"path%5B0%5D=wiki&path%5B1%5D=test&cfg%5Bservers%5D%5Bhttp%5D=localhost",
			wfArrayToCGI( array(
				'path' => array( 'wiki', 'test' ),
				'cfg' => array( 'servers' => array( 'http' => 'localhost' ) ) ) ) );
	}

	function testCgiToArray() {
		$this->assertEquals(
			array( 'path' => array( 'wiki', 'test' ),
			'cfg' => array( 'servers' => array( 'http' => 'localhost' ) ) ),
			wfCgiToArray( 'path%5B0%5D=wiki&path%5B1%5D=test&cfg%5Bservers%5D%5Bhttp%5D=localhost' ) );
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

		/* @todo FIXME: 00 to 101 years are taken as being in [1970-2069] */

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
				MWFunction::callArray( 'mb_substr', $param_set ),
				MWFunction::callArray( 'Fallback::mb_substr', $param_set ),
				'Fallback mb_substr with params ' . implode( ', ', $old_param_set )
			);			
		}
		
		
		//mb_strlen
		$this->assertEquals(
			mb_strlen( $sampleUTF ),
			Fallback::mb_strlen( $sampleUTF ),
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
				MWFunction::callArray( 'mb_strpos', $param_set ),
				MWFunction::callArray( 'Fallback::mb_strpos', $param_set ),
				'Fallback mb_strpos with params ' . implode( ', ', $old_param_set )
			);		
			
			$this->assertEquals(
				MWFunction::callArray( 'mb_strrpos', $param_set ),
				MWFunction::callArray( 'Fallback::mb_strrpos', $param_set ),
				'Fallback mb_strrpos with params ' . implode( ', ', $old_param_set )
			);	
		}
		
	}
	
	
	function testDebugFunctionTest() {
	
		global $wgDebugLogFile, $wgOut, $wgShowDebug, $wgDebugTimestamps;
		
		$old_log_file = $wgDebugLogFile;
		$wgDebugLogFile = tempnam( wfTempDir(), 'mw-' );
		# @todo FIXME: This setting should be tested
		$wgDebugTimestamps = false;
		
		
		
		wfDebug( "This is a normal string" );
		$this->assertEquals( "This is a normal string", file_get_contents( $wgDebugLogFile ) );
		unlink( $wgDebugLogFile );
		
		
		wfDebug( "This is nöt an ASCII string" );
		$this->assertEquals( "This is nöt an ASCII string", file_get_contents( $wgDebugLogFile ) );
		unlink( $wgDebugLogFile );
		
		
		wfDebug( "\00305This has böth UTF and control chars\003" );
		$this->assertEquals( " 05This has böth UTF and control chars ", file_get_contents( $wgDebugLogFile ) );
		unlink( $wgDebugLogFile );
		
		
		
		$old_wgOut = $wgOut;
		$old_wgShowDebug = $wgShowDebug;
		
		$wgOut = new MockOutputPage;
		
		$wgShowDebug = true;
		
		$message = "\00305This has böth UTF and control chars\003";
		
		wfDebug( $message );
		
		if( $wgOut->message == "JAJA is a stupid error message. Anyway, here's your message: $message" ) {
			$this->assertTrue( true, 'MockOutputPage called, set the proper message.' );
		}
		else {
			$this->assertTrue( false, 'MockOutputPage was not called.' );
		}
		
		$wgOut = $old_wgOut;
		$wgShowDebug = $old_wgShowDebug;		
		unlink( $wgDebugLogFile );
		
		
		
		wfDebugMem();
		$this->assertGreaterThan( 5000, preg_replace( '/\D/', '', file_get_contents( $wgDebugLogFile ) ) );
		unlink( $wgDebugLogFile );
		
		wfDebugMem(true);
		$this->assertGreaterThan( 5000000, preg_replace( '/\D/', '', file_get_contents( $wgDebugLogFile ) ) );
		unlink( $wgDebugLogFile );
		
		
		
		$wgDebugLogFile = $old_log_file;
		
	}
	
	function testClientAcceptsGzipTest() {
		
		$settings = array(
			'gzip' => true,
			'bzip' => false,
			'*' => false,
			'compress, gzip' => true,
			'gzip;q=1.0' => true,
			'foozip' => false,
			'foo*zip' => false,
			'gzip;q=abcde' => true, //is this REALLY valid?
			'gzip;q=12345678.9' => true,
			' gzip' => true,
		);
		
		if( isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) ) $old_server_setting = $_SERVER['HTTP_ACCEPT_ENCODING'];
		
		foreach ( $settings as $encoding => $expect ) {
			$_SERVER['HTTP_ACCEPT_ENCODING'] = $encoding;
			
			$this->assertEquals( $expect, wfClientAcceptsGzip( true ),
				"'$encoding' => " . wfBoolToStr( $expect ) );
		}
		
		if( isset( $old_server_setting ) ) $_SERVER['HTTP_ACCEPT_ENCODING'] = $old_server_setting;

	}
	
	
	
	function testSwapVarsTest() {
	

		$var1 = 1;
		$var2 = 2;

		$this->assertEquals( $var1, 1, 'var1 is set originally' );
		$this->assertEquals( $var2, 2, 'var1 is set originally' );

		swap( $var1, $var2 );

		$this->assertEquals( $var1, 2, 'var1 is swapped' );
		$this->assertEquals( $var2, 1, 'var2 is swapped' );

	}


	function testWfPercentTest() {

		$pcts = array(
			array( 6/7, '0.86%', 2, false ),
			array( 3/3, '1%' ),
			array( 22/7, '3.14286%', 5 ),
			array( 3/6, '0.5%' ),
			array( 1/3, '0%', 0 ),
			array( 10/3, '0%', -1 ),
			array( 3/4/5, '0.1%', 1 ),
			array( 6/7*8, '6.8571428571%', 10 ),
		);
		
		foreach( $pcts as $pct ) {
			if( !isset( $pct[2] ) ) $pct[2] = 2;
			if( !isset( $pct[3] ) ) $pct[3] = true;
			
			$this->assertEquals( wfPercent( $pct[0], $pct[2], $pct[3] ), $pct[1], $pct[1] );
		}

	}


	function testInStringTest() {
	
		$this->assertTrue( in_string( 'foo', 'foobar' ), 'foo is in foobar' );
		$this->assertFalse( in_string( 'Bar', 'foobar' ), 'Case-sensitive by default' );
		$this->assertTrue( in_string( 'Foo', 'foobar', true ), 'Case-insensitive when asked' );
	
	}

	/**
	 * test @see wfShorthandToInteger()
	 * @dataProvider provideShorthand
	 */
	public function testWfShorthandToInteger( $shorthand, $expected ) {
		$this->assertEquals( $expected,
			wfShorthandToInteger( $shorthand )
		);	
	}

	/** array( shorthand, expected integer ) */
	public function provideShorthand() {
		return array(
			# Null, empty ... 
			array(     '', -1),
			array(   '  ', -1),
			array(   null, -1),

			# Failures returns 0 :(
			array( 'ABCDEFG', 0 ),
			array( 'Ak',      0 ),

			# Int, strings with spaces
			array(        1,    1 ),
			array(    ' 1 ',    1 ),
			array(     1023, 1023 ),
			array( ' 1023 ', 1023 ),

			# kilo, Mega, Giga
			array(   '1k', 1024 ),
			array(   '1K', 1024 ),
			array(   '1m', 1024 * 1024 ),
			array(   '1M', 1024 * 1024 ),
			array(   '1g', 1024 * 1024 * 1024 ),
			array(   '1G', 1024 * 1024 * 1024 ),

			# Negatives
			array(     -1,    -1 ),
			array(   -500,  -500 ),
			array( '-500',  -500 ),
			array(  '-1k', -1024 ),

			# Zeroes
			array(   '0', 0 ),
			array(  '0k', 0 ),
			array(  '0M', 0 ),
			array(  '0G', 0 ),
			array(  '-0', 0 ),
			array( '-0k', 0 ),
			array( '-0M', 0 ),
			array( '-0G', 0 ),
		);
	}


	/**
	 * test @see wfBCP47().
	 * Please note the BCP explicitly state that language codes are case
	 * insensitive, there are some exceptions to the rule :)
   	 * This test is used to verify our formatting against all lower and
	 * all upper cases language code.
	 *
	 * @see http://tools.ietf.org/html/bcp47
	 * @dataProvider provideLanguageCodes()
	 */
	function testBCP47( $code, $expected ) {
		$code = strtolower( $code );
		$this->assertEquals( $expected, wfBCP47($code),
			"Applying BCP47 standard to lower case '$code'"
		);

		$code = strtoupper( $code );
		$this->assertEquals( $expected, wfBCP47($code),
			"Applying BCP47 standard to upper case '$code'"
		);
	}

	/**
	 * Array format is ($code, $expected)
	 */
	function provideLanguageCodes() {
		return array(
			// Extracted from BCP47 (list not exhaustive)
			# 2.1.1
			array( 'en-ca-x-ca'    , 'en-CA-x-ca'     ),
			array( 'sgn-be-fr'     , 'sgn-BE-FR'      ),
			array( 'az-latn-x-latn', 'az-Latn-x-latn' ),
			# 2.2
			array( 'sr-Latn-RS', 'sr-Latn-RS' ),
			array( 'az-arab-ir', 'az-Arab-IR' ),

			# 2.2.5
			array( 'sl-nedis'  , 'sl-nedis'   ),
			array( 'de-ch-1996', 'de-CH-1996' ),

			# 2.2.6
			array(
				'en-latn-gb-boont-r-extended-sequence-x-private',
				'en-Latn-GB-boont-r-extended-sequence-x-private'
			),

			// Examples from BCP47 Appendix A
			# Simple language subtag:
			array( 'DE', 'de' ),
			array( 'fR', 'fr' ),
			array( 'ja', 'ja' ),

			# Language subtag plus script subtag:
			array( 'zh-hans', 'zh-Hans'),
			array( 'sr-cyrl', 'sr-Cyrl'),
			array( 'sr-latn', 'sr-Latn'),

			# Extended language subtags and their primary language subtag
			# counterparts:
			array( 'zh-cmn-hans-cn', 'zh-cmn-Hans-CN' ),
			array( 'cmn-hans-cn'   , 'cmn-Hans-CN'    ),
			array( 'zh-yue-hk'     , 'zh-yue-HK'      ),
			array( 'yue-hk'        , 'yue-HK'         ),

			# Language-Script-Region:
			array( 'zh-hans-cn', 'zh-Hans-CN' ),
			array( 'sr-latn-RS', 'sr-Latn-RS' ),

			# Language-Variant:
			array( 'sl-rozaj'      , 'sl-rozaj'       ),
			array( 'sl-rozaj-biske', 'sl-rozaj-biske' ),
			array( 'sl-nedis'      , 'sl-nedis'       ),

			# Language-Region-Variant:
			array( 'de-ch-1901'  , 'de-CH-1901'  ),
			array( 'sl-it-nedis' , 'sl-IT-nedis' ),

			# Language-Script-Region-Variant:
			array( 'hy-latn-it-arevela', 'hy-Latn-IT-arevela' ),

			# Language-Region:
			array( 'de-de' , 'de-DE' ),
			array( 'en-us' , 'en-US' ),
			array( 'es-419', 'es-419'),

			# Private use subtags:
			array( 'de-ch-x-phonebk'      , 'de-CH-x-phonebk' ),
			array( 'az-arab-x-aze-derbend', 'az-Arab-x-aze-derbend' ),
			/**
			 * Previous test does not reflect the BCP which states:
			 *  az-Arab-x-AZE-derbend
			 * AZE being private, it should be lower case, hence the test above
			 * should probably be:
			#array( 'az-arab-x-aze-derbend', 'az-Arab-x-AZE-derbend' ),
			 */

			# Private use registry values:
			array( 'x-whatever', 'x-whatever' ),
			array( 'qaa-qaaa-qm-x-southern', 'qaa-Qaaa-QM-x-southern' ),
			array( 'de-qaaa'   , 'de-Qaaa'    ),
			array( 'sr-latn-qm', 'sr-Latn-QM' ),
			array( 'sr-qaaa-rs', 'sr-Qaaa-RS' ),

			# Tags that use extensions
			array( 'en-us-u-islamcal', 'en-US-u-islamcal' ),
			array( 'zh-cn-a-myext-x-private', 'zh-CN-a-myext-x-private' ),
			array( 'en-a-myext-b-another', 'en-a-myext-b-another' ),

			# Invalid:
			// de-419-DE
			// a-DE
			// ar-a-aaa-b-bbb-a-ccc
	
		/*	
			// ISO 15924 :
			array( 'sr-Cyrl', 'sr-Cyrl' ),
			# @todo FIXME: Fix our function?
			array( 'SR-lATN', 'sr-Latn' ),
			array( 'fr-latn', 'fr-Latn' ),
			// Use lowercase for single segment
			// ISO 3166-1-alpha-2 code
			array( 'US', 'us' ),  # USA
			array( 'uS', 'us' ),  # USA
			array( 'Fr', 'fr' ),  # France
			array( 'va', 'va' ),  # Holy See (Vatican City State)
		 */);
	}

	/**
	 * @dataProvider provideMakeUrlIndex()
	 */
	function testMakeUrlIndex( $url, $expected ) {
		$index = wfMakeUrlIndex( $url );
		$this->assertEquals( $expected, $index, "wfMakeUrlIndex(\"$url\")" );
	}

	function provideMakeUrlIndex() {
		return array(
			array(
				// just a regular :)
				'https://bugzilla.wikimedia.org/show_bug.cgi?id=28627',
				'https://org.wikimedia.bugzilla./show_bug.cgi?id=28627'
			),
			array(
				// mailtos are handled special
				// is this really right though? that final . probably belongs earlier?
				'mailto:wiki@wikimedia.org',
				'mailto:org.wikimedia@wiki.',
			),

			// file URL cases per bug 28627...
			array(
				// three slashes: local filesystem path Unix-style
				'file:///whatever/you/like.txt',
				'file://./whatever/you/like.txt'
			),
			array(
				// three slashes: local filesystem path Windows-style
				'file:///c:/whatever/you/like.txt',
				'file://./c:/whatever/you/like.txt'
			),
			array(
				// two slashes: UNC filesystem path Windows-style
				'file://intranet/whatever/you/like.txt',
				'file://intranet./whatever/you/like.txt'
			),
			// Multiple-slash cases that can sorta work on Mozilla
			// if you hack it just right are kinda pathological,
			// and unreliable cross-platform or on IE which means they're
			// unlikely to appear on intranets.
			//
			// Those will survive the algorithm but with results that
			// are less consistent.
		);
	}
	
	/**
	 * @dataProvider provideWfMatchesDomainList
	 */
	function testWfMatchesDomainList( $url, $domains, $expected, $description ) {
		$actual = wfMatchesDomainList( $url, $domains );
		$this->assertEquals( $expected, $actual, $description );
	}
	
	function provideWfMatchesDomainList() {
		$a = array();
		$protocols = array( 'HTTP' => 'http:', 'HTTPS' => 'https:', 'protocol-relative' => '' );
		foreach ( $protocols as $pDesc => $p ) {
			$a = array_merge( $a, array(
				array( "$p//www.example.com", array(), false, "No matches for empty domains array, $pDesc URL" ),
				array( "$p//www.example.com", array( 'www.example.com' ), true, "Exact match in domains array, $pDesc URL" ),
				array( "$p//www.example.com", array( 'example.com' ), true, "Match without subdomain in domains array, $pDesc URL" ),
				array( "$p//www.example2.com", array( 'www.example.com', 'www.example2.com', 'www.example3.com' ), true, "Exact match with other domains in array, $pDesc URL" ),
				array( "$p//www.example2.com", array( 'example.com', 'example2.com', 'example3,com' ), true, "Match without subdomain with other domains in array, $pDesc URL" ),
				array( "$p//www.example4.com", array( 'example.com', 'example2.com', 'example3,com' ), false, "Domain not in array, $pDesc URL" ),
				
				// FIXME: This is a bug in wfMatchesDomainList(). If and when this is fixed, update this test case
				array( "$p//nds-nl.wikipedia.org", array( 'nl.wikipedia.org' ), true, "Substrings of domains match while they shouldn't, $pDesc URL" ),
			) );
		}
		return $a;
	}

	/**
	 * @dataProvider provideWfShellMaintenanceCmdList
	 */
	function testWfShellMaintenanceCmd( $script, $parameters, $options, $expected, $description ) {
		if( wfIsWindows() ) {
			// Approximation that's good enough for our purposes just now
			$expected = str_replace( "'", '"', $expected );
		}
		$actual = wfShellMaintenanceCmd( $script, $parameters, $options );
		$this->assertEquals( $expected, $actual, $description );
	}

	function provideWfShellMaintenanceCmdList() {
		global $wgPhpCli;
		return array(
			array( 'eval.php', array( '--help', '--test' ), array(),
				"'$wgPhpCli' 'eval.php' '--help' '--test'",
				"Called eval.php --help --test" ),
			array( 'eval.php', array( '--help', '--test space' ), array('php' => 'php5'),
				"'php5' 'eval.php' '--help' '--test space'",
				"Called eval.php --help --test with php option" ),
			array( 'eval.php', array( '--help', '--test', 'X' ), array('wrapper' => 'MWScript.php'),
				"'$wgPhpCli' 'MWScript.php' 'eval.php' '--help' '--test' 'X'",
				"Called eval.php --help --test with wrapper option" ),
			array( 'eval.php', array( '--help', '--test', 'y' ), array('php' => 'php5', 'wrapper' => 'MWScript.php'),
				"'php5' 'MWScript.php' 'eval.php' '--help' '--test' 'y'",
				"Called eval.php --help --test with wrapper and php option" ),
		);
	}

	/**
	 * @dataProvider provideWfIsBadImageList
	 */
	function testWfIsBadImage( $name, $title, $blacklist, $expected, $desc ) {
		$this->assertEquals( $expected, wfIsBadImage( $name, $title, $blacklist ), $desc );
	}

	function provideWfIsBadImageList() {
		$blacklist = '* [[File:Bad.jpg]] except [[Nasty page]]';
		return array(
			array( 'Bad.jpg', false, $blacklist, true,
				'Called on a bad image' ),
			array( 'Bad.jpg', Title::makeTitle( NS_MAIN, 'A page' ), $blacklist, true,
				'Called on a bad image' ),
			array( 'NotBad.jpg', false, $blacklist, false,
				'Called on a non-bad image' ),
			array( 'Bad.jpg', Title::makeTitle( NS_MAIN, 'Nasty page' ), $blacklist, false,
				'Called on a bad image but is on a whitelisted page' ),
			array( 'File:Bad.jpg', false, $blacklist, false,
				'Called on a bad image with File:' ),
		);
	}
	/* TODO: many more! */
}


class MockOutputPage {
	
	public $message;
	
	function debug( $message ) {
		$this->message = "JAJA is a stupid error message. Anyway, here's your message: $message";
	}
}

