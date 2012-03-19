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

	function dataArrayToCGI() {
		return array(
			array( array(), '' ), // empty
			array( array( 'foo' => 'bar' ), 'foo=bar' ), // string test
			array( array( 'foo' => '' ), 'foo=' ), // empty string test
			array( array( 'foo' => 1 ), 'foo=1' ), // number test
			array( array( 'foo' => true ), 'foo=1' ), // true test
			array( array( 'foo' => false ), '' ), // false test
			array( array( 'foo' => null ), '' ), // null test
			array( array( 'foo' => 'A&B=5+6@!"\'' ), 'foo=A%26B%3D5%2B6%40%21%22%27' ), // urlencoding test
			array( array( 'foo' => 'bar', 'baz' => 'is', 'asdf' => 'qwerty' ), 'foo=bar&baz=is&asdf=qwerty' ), // multi-item test
			array( array( 'foo' => array( 'bar' => 'baz' ) ), 'foo%5Bbar%5D=baz' ),
			array( array( 'foo' => array( 'bar' => 'baz', 'qwerty' => 'asdf' ) ), 'foo%5Bbar%5D=baz&foo%5Bqwerty%5D=asdf' ),
			array( array( 'foo' => array( 'bar', 'baz' ) ), 'foo%5B0%5D=bar&foo%5B1%5D=baz' ),
			array( array( 'foo' => array( 'bar' => array( 'bar' => 'baz' ) ) ), 'foo%5Bbar%5D%5Bbar%5D=baz' ),
		);
	}

	/**
	 * @dataProvider dataArrayToCGI
	 */
	function testArrayToCGI( $array, $result ) {
		$this->assertEquals( $result, wfArrayToCGI( $array ) );
	}


	function testArrayToCGI2() {
		$this->assertEquals(
			"baz=bar&foo=bar",
			wfArrayToCGI(
				array( 'baz' => 'bar' ),
				array( 'foo' => 'bar', 'baz' => 'overridden value' ) ) );
	}

	function dataCgiToArray() {
		return array(
			array( '', array() ), // empty
			array( 'foo=bar', array( 'foo' => 'bar' ) ), // string
			array( 'foo=', array( 'foo' => '' ) ), // empty string
			array( 'foo', array( 'foo' => '' ) ), // missing =
			array( 'foo=bar&qwerty=asdf', array( 'foo' => 'bar', 'qwerty' => 'asdf' ) ), // multiple value
			array( 'foo=A%26B%3D5%2B6%40%21%22%27', array( 'foo' => 'A&B=5+6@!"\'' ) ), // urldecoding test
			array( 'foo%5Bbar%5D=baz', array( 'foo' => array( 'bar' => 'baz' ) ) ),
			array( 'foo%5Bbar%5D=baz&foo%5Bqwerty%5D=asdf', array( 'foo' => array( 'bar' => 'baz', 'qwerty' => 'asdf' ) ) ),
			array( 'foo%5B0%5D=bar&foo%5B1%5D=baz', array( 'foo' => array( 0 => 'bar', 1 => 'baz' ) ) ),
			array( 'foo%5Bbar%5D%5Bbar%5D=baz', array( 'foo' => array( 'bar' => array( 'bar' => 'baz' ) ) ) ),
		);
	}

	/**
	 * @dataProvider dataCgiToArray
	 */
	function testCgiToArray( $cgi, $result ) {
		$this->assertEquals( $result, wfCgiToArray( $cgi ) );
	}

	function dataCgiRoundTrip() {
		return array(
			array( '' ),
			array( 'foo=bar' ),
			array( 'foo=' ),
			array( 'foo=bar&baz=biz' ),
			array( 'foo=A%26B%3D5%2B6%40%21%22%27' ),
			array( 'foo%5Bbar%5D=baz' ),
			array( 'foo%5B0%5D=bar&foo%5B1%5D=baz' ),
			array( 'foo%5Bbar%5D%5Bbar%5D=baz' ),
		);
	}

	/**
	 * @dataProvider dataCgiRoundTrip
	 */
	function testCgiRoundTrip( $cgi ) {
		$this->assertEquals( $cgi, wfArrayToCGI( wfCgiToArray( $cgi ) ) );
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
	 * @dataProvider provideMakeUrlIndexes()
	 */
	function testMakeUrlIndexes( $url, $expected ) {
		$index = wfMakeUrlIndexes( $url );
		$this->assertEquals( $expected, $index, "wfMakeUrlIndexes(\"$url\")" );
	}

	function provideMakeUrlIndexes() {
		return array(
			array(
				// just a regular :)
				'https://bugzilla.wikimedia.org/show_bug.cgi?id=28627',
				array( 'https://org.wikimedia.bugzilla./show_bug.cgi?id=28627' )
			),
			array(
				// mailtos are handled special
				// is this really right though? that final . probably belongs earlier?
				'mailto:wiki@wikimedia.org',
				array( 'mailto:org.wikimedia@wiki.' )
			),

			// file URL cases per bug 28627...
			array(
				// three slashes: local filesystem path Unix-style
				'file:///whatever/you/like.txt',
				array( 'file://./whatever/you/like.txt' )
			),
			array(
				// three slashes: local filesystem path Windows-style
				'file:///c:/whatever/you/like.txt',
				array( 'file://./c:/whatever/you/like.txt' )
			),
			array(
				// two slashes: UNC filesystem path Windows-style
				'file://intranet/whatever/you/like.txt',
				array( 'file://intranet./whatever/you/like.txt' )
			),
			// Multiple-slash cases that can sorta work on Mozilla
			// if you hack it just right are kinda pathological,
			// and unreliable cross-platform or on IE which means they're
			// unlikely to appear on intranets.
			//
			// Those will survive the algorithm but with results that
			// are less consistent.

			// protocol-relative URL cases per bug 29854...
			array(
				'//bugzilla.wikimedia.org/show_bug.cgi?id=28627',
				array(
					'http://org.wikimedia.bugzilla./show_bug.cgi?id=28627',
					'https://org.wikimedia.bugzilla./show_bug.cgi?id=28627'
				)
			),
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
	/* TODO: many more! */
}


class MockOutputPage {
	
	public $message;
	
	function debug( $message ) {
		$this->message = "JAJA is a stupid error message. Anyway, here's your message: $message";
	}
}

