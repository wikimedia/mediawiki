<?php

/**
 * @group GlobalFunctions
 */
class GlobalTest extends MediaWikiTestCase {
	protected function setUp() {
		parent::setUp();

		$readOnlyFile = $this->getNewTempFile();
		unlink( $readOnlyFile );

		$this->setMwGlobals( array(
			'wgReadOnlyFile' => $readOnlyFile,
			'wgUrlProtocols' => array(
				'http://',
				'https://',
				'mailto:',
				'//',
				'file://', # Non-default
			),
		) );
	}

	/**
	 * @dataProvider provideForWfArrayDiff2
	 * @covers ::wfArrayDiff2
	 */
	public function testWfArrayDiff2( $a, $b, $expected ) {
		$this->assertEquals(
			wfArrayDiff2( $a, $b ), $expected
		);
	}

	// @todo Provide more tests
	public static function provideForWfArrayDiff2() {
		// $a $b $expected
		return array(
			array(
				array( 'a', 'b' ),
				array( 'a', 'b' ),
				array(),
			),
			array(
				array( array( 'a' ), array( 'a', 'b', 'c' ) ),
				array( array( 'a' ), array( 'a', 'b' ) ),
				array( 1 => array( 'a', 'b', 'c' ) ),
			),
		);
	}

	/*
	 * Test cases for random functions could hypothetically fail,
	 * even though they shouldn't.
	 */

	/**
	 * @covers ::wfRandom
	 */
	public function testRandom() {
		$this->assertFalse(
			wfRandom() == wfRandom()
		);
	}

	/**
	 * @covers ::wfRandomString
	 */
	public function testRandomString() {
		$this->assertFalse(
			wfRandomString() == wfRandomString()
		);
		$this->assertEquals(
			strlen( wfRandomString( 10 ) ), 10
		);
		$this->assertTrue(
			preg_match( '/^[0-9a-f]+$/i', wfRandomString() ) === 1
		);
	}

	/**
	 * @covers ::wfUrlencode
	 */
	public function testUrlencode() {
		$this->assertEquals(
			"%E7%89%B9%E5%88%A5:Contributions/Foobar",
			wfUrlencode( "\xE7\x89\xB9\xE5\x88\xA5:Contributions/Foobar" ) );
	}

	/**
	 * @covers ::wfExpandIRI
	 */
	public function testExpandIRI() {
		$this->assertEquals(
			"https://te.wikibooks.org/wiki/ఉబుంటు_వాడుకరి_మార్గదర్శని",
			wfExpandIRI( "https://te.wikibooks.org/wiki/"
				. "%E0%B0%89%E0%B0%AC%E0%B1%81%E0%B0%82%E0%B0%9F%E0%B1%81_"
				. "%E0%B0%B5%E0%B0%BE%E0%B0%A1%E0%B1%81%E0%B0%95%E0%B0%B0%E0%B0%BF_"
				. "%E0%B0%AE%E0%B0%BE%E0%B0%B0%E0%B1%8D%E0%B0%97%E0%B0%A6%E0%B0%B0"
				. "%E0%B1%8D%E0%B0%B6%E0%B0%A8%E0%B0%BF" ) );
	}

	/**
	 * @covers ::wfReadOnly
	 */
	public function testReadOnlyEmpty() {
		global $wgReadOnly;
		$wgReadOnly = null;

		$this->assertFalse( wfReadOnly() );
		$this->assertFalse( wfReadOnly() );
	}

	/**
	 * @covers ::wfReadOnly
	 */
	public function testReadOnlySet() {
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

	public static function provideArrayToCGI() {
		return array(
			array( array(), '' ), // empty
			array( array( 'foo' => 'bar' ), 'foo=bar' ), // string test
			array( array( 'foo' => '' ), 'foo=' ), // empty string test
			array( array( 'foo' => 1 ), 'foo=1' ), // number test
			array( array( 'foo' => true ), 'foo=1' ), // true test
			array( array( 'foo' => false ), '' ), // false test
			array( array( 'foo' => null ), '' ), // null test
			array( array( 'foo' => 'A&B=5+6@!"\'' ), 'foo=A%26B%3D5%2B6%40%21%22%27' ), // urlencoding test
			array(
				array( 'foo' => 'bar', 'baz' => 'is', 'asdf' => 'qwerty' ),
				'foo=bar&baz=is&asdf=qwerty'
			), // multi-item test
			array( array( 'foo' => array( 'bar' => 'baz' ) ), 'foo%5Bbar%5D=baz' ),
			array(
				array( 'foo' => array( 'bar' => 'baz', 'qwerty' => 'asdf' ) ),
				'foo%5Bbar%5D=baz&foo%5Bqwerty%5D=asdf'
			),
			array( array( 'foo' => array( 'bar', 'baz' ) ), 'foo%5B0%5D=bar&foo%5B1%5D=baz' ),
			array(
				array( 'foo' => array( 'bar' => array( 'bar' => 'baz' ) ) ),
				'foo%5Bbar%5D%5Bbar%5D=baz'
			),
		);
	}

	/**
	 * @dataProvider provideArrayToCGI
	 * @covers ::wfArrayToCgi
	 */
	public function testArrayToCGI( $array, $result ) {
		$this->assertEquals( $result, wfArrayToCgi( $array ) );
	}

	/**
	 * @covers ::wfArrayToCgi
	 */
	public function testArrayToCGI2() {
		$this->assertEquals(
			"baz=bar&foo=bar",
			wfArrayToCgi(
				array( 'baz' => 'bar' ),
				array( 'foo' => 'bar', 'baz' => 'overridden value' ) ) );
	}

	public static function provideCgiToArray() {
		return array(
			array( '', array() ), // empty
			array( 'foo=bar', array( 'foo' => 'bar' ) ), // string
			array( 'foo=', array( 'foo' => '' ) ), // empty string
			array( 'foo', array( 'foo' => '' ) ), // missing =
			array( 'foo=bar&qwerty=asdf', array( 'foo' => 'bar', 'qwerty' => 'asdf' ) ), // multiple value
			array( 'foo=A%26B%3D5%2B6%40%21%22%27', array( 'foo' => 'A&B=5+6@!"\'' ) ), // urldecoding test
			array( 'foo%5Bbar%5D=baz', array( 'foo' => array( 'bar' => 'baz' ) ) ),
			array(
				'foo%5Bbar%5D=baz&foo%5Bqwerty%5D=asdf',
				array( 'foo' => array( 'bar' => 'baz', 'qwerty' => 'asdf' ) )
			),
			array( 'foo%5B0%5D=bar&foo%5B1%5D=baz', array( 'foo' => array( 0 => 'bar', 1 => 'baz' ) ) ),
			array(
				'foo%5Bbar%5D%5Bbar%5D=baz',
				array( 'foo' => array( 'bar' => array( 'bar' => 'baz' ) ) )
			),
		);
	}

	/**
	 * @dataProvider provideCgiToArray
	 * @covers ::wfCgiToArray
	 */
	public function testCgiToArray( $cgi, $result ) {
		$this->assertEquals( $result, wfCgiToArray( $cgi ) );
	}

	public static function provideCgiRoundTrip() {
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
	 * @dataProvider provideCgiRoundTrip
	 * @covers ::wfArrayToCgi
	 */
	public function testCgiRoundTrip( $cgi ) {
		$this->assertEquals( $cgi, wfArrayToCgi( wfCgiToArray( $cgi ) ) );
	}

	/**
	 * @covers ::mimeTypeMatch
	 */
	public function testMimeTypeMatch() {
		$this->assertEquals(
			'text/html',
			mimeTypeMatch( 'text/html',
				array( 'application/xhtml+xml' => 1.0,
					'text/html' => 0.7,
					'text/plain' => 0.3 ) ) );
		$this->assertEquals(
			'text/*',
			mimeTypeMatch( 'text/html',
				array( 'image/*' => 1.0,
					'text/*' => 0.5 ) ) );
		$this->assertEquals(
			'*/*',
			mimeTypeMatch( 'text/html',
				array( '*/*' => 1.0 ) ) );
		$this->assertNull(
			mimeTypeMatch( 'text/html',
				array( 'image/png' => 1.0,
					'image/svg+xml' => 0.5 ) ) );
	}

	/**
	 * @covers ::wfNegotiateType
	 */
	public function testNegotiateType() {
		$this->assertEquals(
			'text/html',
			wfNegotiateType(
				array( 'application/xhtml+xml' => 1.0,
					'text/html' => 0.7,
					'text/plain' => 0.5,
					'text/*' => 0.2 ),
				array( 'text/html' => 1.0 ) ) );
		$this->assertEquals(
			'application/xhtml+xml',
			wfNegotiateType(
				array( 'application/xhtml+xml' => 1.0,
					'text/html' => 0.7,
					'text/plain' => 0.5,
					'text/*' => 0.2 ),
				array( 'application/xhtml+xml' => 1.0,
					'text/html' => 0.5 ) ) );
		$this->assertEquals(
			'text/html',
			wfNegotiateType(
				array( 'text/html' => 1.0,
					'text/plain' => 0.5,
					'text/*' => 0.5,
					'application/xhtml+xml' => 0.2 ),
				array( 'application/xhtml+xml' => 1.0,
					'text/html' => 0.5 ) ) );
		$this->assertEquals(
			'text/html',
			wfNegotiateType(
				array( 'text/*' => 1.0,
					'image/*' => 0.7,
					'*/*' => 0.3 ),
				array( 'application/xhtml+xml' => 1.0,
					'text/html' => 0.5 ) ) );
		$this->assertNull(
			wfNegotiateType(
				array( 'text/*' => 1.0 ),
				array( 'application/xhtml+xml' => 1.0 ) ) );
	}

	/**
	 * @covers ::wfDebug
	 * @covers ::wfDebugMem
	 */
	public function testDebugFunctionTest() {
		$debugLogFile = $this->getNewTempFile();

		$this->setMwGlobals( array(
			'wgDebugLogFile' => $debugLogFile,
			# @todo FIXME: $wgDebugTimestamps should be tested
			'wgDebugTimestamps' => false
		) );

		wfDebug( "This is a normal string" );
		$this->assertEquals( "This is a normal string\n", file_get_contents( $debugLogFile ) );
		unlink( $debugLogFile );

		wfDebug( "This is nöt an ASCII string" );
		$this->assertEquals( "This is nöt an ASCII string\n", file_get_contents( $debugLogFile ) );
		unlink( $debugLogFile );

		wfDebug( "\00305This has böth UTF and control chars\003" );
		$this->assertEquals(
			" 05This has böth UTF and control chars \n",
			file_get_contents( $debugLogFile )
		);
		unlink( $debugLogFile );

		wfDebugMem();
		$this->assertGreaterThan(
			1000,
			preg_replace( '/\D/', '', file_get_contents( $debugLogFile ) )
		);
		unlink( $debugLogFile );

		wfDebugMem( true );
		$this->assertGreaterThan(
			1000000,
			preg_replace( '/\D/', '', file_get_contents( $debugLogFile ) )
		);
		unlink( $debugLogFile );
	}

	/**
	 * @covers ::wfClientAcceptsGzip
	 */
	public function testClientAcceptsGzipTest() {

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

		if ( isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) ) {
			$old_server_setting = $_SERVER['HTTP_ACCEPT_ENCODING'];
		}

		foreach ( $settings as $encoding => $expect ) {
			$_SERVER['HTTP_ACCEPT_ENCODING'] = $encoding;

			$this->assertEquals( $expect, wfClientAcceptsGzip( true ),
				"'$encoding' => " . wfBoolToStr( $expect ) );
		}

		if ( isset( $old_server_setting ) ) {
			$_SERVER['HTTP_ACCEPT_ENCODING'] = $old_server_setting;
		}
	}

	/**
	 * @covers ::wfPercent
	 */
	public function testWfPercentTest() {

		$pcts = array(
			array( 6 / 7, '0.86%', 2, false ),
			array( 3 / 3, '1%' ),
			array( 22 / 7, '3.14286%', 5 ),
			array( 3 / 6, '0.5%' ),
			array( 1 / 3, '0%', 0 ),
			array( 10 / 3, '0%', -1 ),
			array( 3 / 4 / 5, '0.1%', 1 ),
			array( 6 / 7 * 8, '6.8571428571%', 10 ),
		);

		foreach ( $pcts as $pct ) {
			if ( !isset( $pct[2] ) ) {
				$pct[2] = 2;
			}
			if ( !isset( $pct[3] ) ) {
				$pct[3] = true;
			}

			$this->assertEquals( wfPercent( $pct[0], $pct[2], $pct[3] ), $pct[1], $pct[1] );
		}
	}

	/**
	 * test @see wfShorthandToInteger()
	 * @dataProvider provideShorthand
	 * @covers ::wfShorthandToInteger
	 */
	public function testWfShorthandToInteger( $shorthand, $expected ) {
		$this->assertEquals( $expected,
			wfShorthandToInteger( $shorthand )
		);
	}

	/** array( shorthand, expected integer ) */
	public static function provideShorthand() {
		return array(
			# Null, empty ...
			array( '', -1 ),
			array( '  ', -1 ),
			array( null, -1 ),

			# Failures returns 0 :(
			array( 'ABCDEFG', 0 ),
			array( 'Ak', 0 ),

			# Int, strings with spaces
			array( 1, 1 ),
			array( ' 1 ', 1 ),
			array( 1023, 1023 ),
			array( ' 1023 ', 1023 ),

			# kilo, Mega, Giga
			array( '1k', 1024 ),
			array( '1K', 1024 ),
			array( '1m', 1024 * 1024 ),
			array( '1M', 1024 * 1024 ),
			array( '1g', 1024 * 1024 * 1024 ),
			array( '1G', 1024 * 1024 * 1024 ),

			# Negatives
			array( -1, -1 ),
			array( -500, -500 ),
			array( '-500', -500 ),
			array( '-1k', -1024 ),

			# Zeroes
			array( '0', 0 ),
			array( '0k', 0 ),
			array( '0M', 0 ),
			array( '0G', 0 ),
			array( '-0', 0 ),
			array( '-0k', 0 ),
			array( '-0M', 0 ),
			array( '-0G', 0 ),
		);
	}

	/**
	 * @param string $old Text as it was in the database
	 * @param string $mine Text submitted while user was editing
	 * @param string $yours Text submitted by the user
	 * @param bool $expectedMergeResult Whether the merge should be a success
	 * @param string $expectedText Text after merge has been completed
	 *
	 * @dataProvider provideMerge()
	 * @group medium
	 * @covers ::wfMerge
	 */
	public function testMerge( $old, $mine, $yours, $expectedMergeResult, $expectedText ) {
		$this->checkHasDiff3();

		$mergedText = null;
		$isMerged = wfMerge( $old, $mine, $yours, $mergedText );

		$msg = 'Merge should be a ';
		$msg .= $expectedMergeResult ? 'success' : 'failure';
		$this->assertEquals( $expectedMergeResult, $isMerged, $msg );

		if ( $isMerged ) {
			// Verify the merged text
			$this->assertEquals( $expectedText, $mergedText,
				'is merged text as expected?' );
		}
	}

	public static function provideMerge() {
		$EXPECT_MERGE_SUCCESS = true;
		$EXPECT_MERGE_FAILURE = false;

		return array(
			// #0: clean merge
			array(
				// old:
				"one one one\n" . // trimmed
					"\n" .
					"two two two",

				// mine:
				"one one one ONE ONE\n" .
					"\n" .
					"two two two\n", // with tailing whitespace

				// yours:
				"one one one\n" .
					"\n" .
					"two two TWO TWO", // trimmed

				// ok:
				$EXPECT_MERGE_SUCCESS,

				// result:
				"one one one ONE ONE\n" .
					"\n" .
					"two two TWO TWO\n", // note: will always end in a newline
			),

			// #1: conflict, fail
			array(
				// old:
				"one one one", // trimmed

				// mine:
				"one one one ONE ONE\n" .
					"\n" .
					"bla bla\n" .
					"\n", // with tailing whitespace

				// yours:
				"one one one\n" .
					"\n" .
					"two two", // trimmed

				$EXPECT_MERGE_FAILURE,

				// result:
				null,
			),
		);
	}

	/**
	 * @dataProvider provideMakeUrlIndexes()
	 * @covers ::wfMakeUrlIndexes
	 */
	public function testMakeUrlIndexes( $url, $expected ) {
		$index = wfMakeUrlIndexes( $url );
		$this->assertEquals( $expected, $index, "wfMakeUrlIndexes(\"$url\")" );
	}

	public static function provideMakeUrlIndexes() {
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
	 * @covers ::wfMatchesDomainList
	 */
	public function testWfMatchesDomainList( $url, $domains, $expected, $description ) {
		$actual = wfMatchesDomainList( $url, $domains );
		$this->assertEquals( $expected, $actual, $description );
	}

	public static function provideWfMatchesDomainList() {
		$a = array();
		$protocols = array( 'HTTP' => 'http:', 'HTTPS' => 'https:', 'protocol-relative' => '' );
		foreach ( $protocols as $pDesc => $p ) {
			$a = array_merge( $a, array(
				array(
					"$p//www.example.com",
					array(),
					false,
					"No matches for empty domains array, $pDesc URL"
				),
				array(
					"$p//www.example.com",
					array( 'www.example.com' ),
					true,
					"Exact match in domains array, $pDesc URL"
				),
				array(
					"$p//www.example.com",
					array( 'example.com' ),
					true,
					"Match without subdomain in domains array, $pDesc URL"
				),
				array(
					"$p//www.example2.com",
					array( 'www.example.com', 'www.example2.com', 'www.example3.com' ),
					true,
					"Exact match with other domains in array, $pDesc URL"
				),
				array(
					"$p//www.example2.com",
					array( 'example.com', 'example2.com', 'example3,com' ),
					true,
					"Match without subdomain with other domains in array, $pDesc URL"
				),
				array(
					"$p//www.example4.com",
					array( 'example.com', 'example2.com', 'example3,com' ),
					false,
					"Domain not in array, $pDesc URL"
				),
				array(
					"$p//nds-nl.wikipedia.org",
					array( 'nl.wikipedia.org' ),
					false,
					"Non-matching substring of domain, $pDesc URL"
				),
			) );
		}

		return $a;
	}

	/**
	 * @covers ::wfMkdirParents
	 */
	public function testWfMkdirParents() {
		// Should not return true if file exists instead of directory
		$fname = $this->getNewTempFile();
		MediaWiki\suppressWarnings();
		$ok = wfMkdirParents( $fname );
		MediaWiki\restoreWarnings();
		$this->assertFalse( $ok );
	}

	/**
	 * @dataProvider provideWfShellWikiCmdList
	 * @covers ::wfShellWikiCmd
	 */
	public function testWfShellWikiCmd( $script, $parameters, $options,
		$expected, $description
	) {
		if ( wfIsWindows() ) {
			// Approximation that's good enough for our purposes just now
			$expected = str_replace( "'", '"', $expected );
		}
		$actual = wfShellWikiCmd( $script, $parameters, $options );
		$this->assertEquals( $expected, $actual, $description );
	}

	public function wfWikiID() {
		$this->setMwGlobals( array(
			'wgDBname' => 'example',
			'wgDBprefix' => '',
		) );
		$this->assertEquals(
			wfWikiID(),
			'example'
		);

		$this->setMwGlobals( array(
			'wgDBname' => 'example',
			'wgDBprefix' => 'mw_',
		) );
		$this->assertEquals(
			wfWikiID(),
			'example-mw_'
		);
	}

	public function testWfMemcKey() {
		// Just assert the exact output so we can catch unintentional changes to key
		// construction, which would effectively invalidate all existing cache.

		$this->setMwGlobals( array(
			'wgCachePrefix' => false,
			'wgDBname' => 'example',
			'wgDBprefix' => '',
		) );
		$this->assertEquals(
			wfMemcKey( 'foo', '123', 'bar' ),
			'example:foo:123:bar'
		);

		$this->setMwGlobals( array(
			'wgCachePrefix' => false,
			'wgDBname' => 'example',
			'wgDBprefix' => 'mw_',
		) );
		$this->assertEquals(
			wfMemcKey( 'foo', '123', 'bar' ),
			'example-mw_:foo:123:bar'
		);

		$this->setMwGlobals( array(
			'wgCachePrefix' => 'custom',
			'wgDBname' => 'example',
			'wgDBprefix' => 'mw_',
		) );
		$this->assertEquals(
			wfMemcKey( 'foo', '123', 'bar' ),
			'custom:foo:123:bar'
		);
	}

	public function testWfForeignMemcKey() {
		$this->setMwGlobals( array(
			'wgCachePrefix' => false,
			'wgDBname' => 'example',
			'wgDBprefix' => '',
		) );
		$local = wfMemcKey( 'foo', 'bar' );

		$this->setMwGlobals( array(
			'wgDBname' => 'other',
			'wgDBprefix' => 'mw_',
		) );
		$this->assertEquals(
			wfForeignMemcKey( 'example', '', 'foo', 'bar' ),
			$local,
			'Match output of wfMemcKey from local wiki'
		);
	}

	public function testWfGlobalCacheKey() {
		$this->setMwGlobals( array(
			'wgCachePrefix' => 'ignored',
			'wgDBname' => 'example',
			'wgDBprefix' => ''
		) );
		$one = wfGlobalCacheKey( 'some', 'thing' );
		$this->assertEquals(
			$one,
			'global:some:thing'
		);

		$this->setMwGlobals( array(
			'wgDBname' => 'other',
			'wgDBprefix' => 'mw_'
		) );
		$two = wfGlobalCacheKey( 'some', 'thing' );

		$this->assertEquals(
			$one,
			$two,
			'Not fragmented by wiki id'
		);
	}

	public static function provideWfShellWikiCmdList() {
		global $wgPhpCli;

		return array(
			array( 'eval.php', array( '--help', '--test' ), array(),
				"'$wgPhpCli' 'eval.php' '--help' '--test'",
				"Called eval.php --help --test" ),
			array( 'eval.php', array( '--help', '--test space' ), array( 'php' => 'php5' ),
				"'php5' 'eval.php' '--help' '--test space'",
				"Called eval.php --help --test with php option" ),
			array( 'eval.php', array( '--help', '--test', 'X' ), array( 'wrapper' => 'MWScript.php' ),
				"'$wgPhpCli' 'MWScript.php' 'eval.php' '--help' '--test' 'X'",
				"Called eval.php --help --test with wrapper option" ),
			array(
				'eval.php',
				array( '--help', '--test', 'y' ),
				array( 'php' => 'php5', 'wrapper' => 'MWScript.php' ),
				"'php5' 'MWScript.php' 'eval.php' '--help' '--test' 'y'",
				"Called eval.php --help --test with wrapper and php option"
			),
		);
	}
	/* @todo many more! */
}
