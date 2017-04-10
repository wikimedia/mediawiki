<?php

/**
 * @group Database
 * @group GlobalFunctions
 */
class GlobalTest extends MediaWikiTestCase {
	protected function setUp() {
		parent::setUp();

		$readOnlyFile = $this->getNewTempFile();
		unlink( $readOnlyFile );

		$this->setMwGlobals( [
			'wgReadOnlyFile' => $readOnlyFile,
			'wgUrlProtocols' => [
				'http://',
				'https://',
				'mailto:',
				'//',
				'file://', # Non-default
			],
		] );
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
		return [
			[
				[ 'a', 'b' ],
				[ 'a', 'b' ],
				[],
			],
			[
				[ [ 'a' ], [ 'a', 'b', 'c' ] ],
				[ [ 'a' ], [ 'a', 'b' ] ],
				[ 1 => [ 'a', 'b', 'c' ] ],
			],
		];
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
	 * Intended to cover the relevant bits of ServiceWiring.php, as well as GlobalFunctions.php
	 * @covers ::wfReadOnly
	 */
	public function testReadOnlyEmpty() {
		global $wgReadOnly;
		$wgReadOnly = null;

		MediaWiki\MediaWikiServices::getInstance()->getReadOnlyMode()->clearCache();
		$this->assertFalse( wfReadOnly() );
		$this->assertFalse( wfReadOnly() );
	}

	/**
	 * Intended to cover the relevant bits of ServiceWiring.php, as well as GlobalFunctions.php
	 * @covers ::wfReadOnly
	 */
	public function testReadOnlySet() {
		global $wgReadOnly, $wgReadOnlyFile;

		$readOnlyMode = MediaWiki\MediaWikiServices::getInstance()->getReadOnlyMode();
		$readOnlyMode->clearCache();

		$f = fopen( $wgReadOnlyFile, "wt" );
		fwrite( $f, 'Message' );
		fclose( $f );
		$wgReadOnly = null; # Check on $wgReadOnlyFile

		$this->assertTrue( wfReadOnly() );
		$this->assertTrue( wfReadOnly() ); # Check cached

		unlink( $wgReadOnlyFile );
		$readOnlyMode->clearCache();
		$this->assertFalse( wfReadOnly() );
		$this->assertFalse( wfReadOnly() );
	}

	/**
	 * This behaviour could probably be deprecated. Several extensions rely on it as of 1.29.
	 * @covers ::wfReadOnlyReason
	 */
	public function testReadOnlyGlobalChange() {
		$this->assertFalse( wfReadOnlyReason() );
		$this->setMwGlobals( [
			'wgReadOnly' => 'reason'
		] );
		$this->assertSame( 'reason', wfReadOnlyReason() );
	}

	public static function provideArrayToCGI() {
		return [
			[ [], '' ], // empty
			[ [ 'foo' => 'bar' ], 'foo=bar' ], // string test
			[ [ 'foo' => '' ], 'foo=' ], // empty string test
			[ [ 'foo' => 1 ], 'foo=1' ], // number test
			[ [ 'foo' => true ], 'foo=1' ], // true test
			[ [ 'foo' => false ], '' ], // false test
			[ [ 'foo' => null ], '' ], // null test
			[ [ 'foo' => 'A&B=5+6@!"\'' ], 'foo=A%26B%3D5%2B6%40%21%22%27' ], // urlencoding test
			[
				[ 'foo' => 'bar', 'baz' => 'is', 'asdf' => 'qwerty' ],
				'foo=bar&baz=is&asdf=qwerty'
			], // multi-item test
			[ [ 'foo' => [ 'bar' => 'baz' ] ], 'foo%5Bbar%5D=baz' ],
			[
				[ 'foo' => [ 'bar' => 'baz', 'qwerty' => 'asdf' ] ],
				'foo%5Bbar%5D=baz&foo%5Bqwerty%5D=asdf'
			],
			[ [ 'foo' => [ 'bar', 'baz' ] ], 'foo%5B0%5D=bar&foo%5B1%5D=baz' ],
			[
				[ 'foo' => [ 'bar' => [ 'bar' => 'baz' ] ] ],
				'foo%5Bbar%5D%5Bbar%5D=baz'
			],
		];
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
				[ 'baz' => 'bar' ],
				[ 'foo' => 'bar', 'baz' => 'overridden value' ] ) );
	}

	public static function provideCgiToArray() {
		return [
			[ '', [] ], // empty
			[ 'foo=bar', [ 'foo' => 'bar' ] ], // string
			[ 'foo=', [ 'foo' => '' ] ], // empty string
			[ 'foo', [ 'foo' => '' ] ], // missing =
			[ 'foo=bar&qwerty=asdf', [ 'foo' => 'bar', 'qwerty' => 'asdf' ] ], // multiple value
			[ 'foo=A%26B%3D5%2B6%40%21%22%27', [ 'foo' => 'A&B=5+6@!"\'' ] ], // urldecoding test
			[ 'foo%5Bbar%5D=baz', [ 'foo' => [ 'bar' => 'baz' ] ] ],
			[
				'foo%5Bbar%5D=baz&foo%5Bqwerty%5D=asdf',
				[ 'foo' => [ 'bar' => 'baz', 'qwerty' => 'asdf' ] ]
			],
			[ 'foo%5B0%5D=bar&foo%5B1%5D=baz', [ 'foo' => [ 0 => 'bar', 1 => 'baz' ] ] ],
			[
				'foo%5Bbar%5D%5Bbar%5D=baz',
				[ 'foo' => [ 'bar' => [ 'bar' => 'baz' ] ] ]
			],
		];
	}

	/**
	 * @dataProvider provideCgiToArray
	 * @covers ::wfCgiToArray
	 */
	public function testCgiToArray( $cgi, $result ) {
		$this->assertEquals( $result, wfCgiToArray( $cgi ) );
	}

	public static function provideCgiRoundTrip() {
		return [
			[ '' ],
			[ 'foo=bar' ],
			[ 'foo=' ],
			[ 'foo=bar&baz=biz' ],
			[ 'foo=A%26B%3D5%2B6%40%21%22%27' ],
			[ 'foo%5Bbar%5D=baz' ],
			[ 'foo%5B0%5D=bar&foo%5B1%5D=baz' ],
			[ 'foo%5Bbar%5D%5Bbar%5D=baz' ],
		];
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
				[ 'application/xhtml+xml' => 1.0,
					'text/html' => 0.7,
					'text/plain' => 0.3 ] ) );
		$this->assertEquals(
			'text/*',
			mimeTypeMatch( 'text/html',
				[ 'image/*' => 1.0,
					'text/*' => 0.5 ] ) );
		$this->assertEquals(
			'*/*',
			mimeTypeMatch( 'text/html',
				[ '*/*' => 1.0 ] ) );
		$this->assertNull(
			mimeTypeMatch( 'text/html',
				[ 'image/png' => 1.0,
					'image/svg+xml' => 0.5 ] ) );
	}

	/**
	 * @covers ::wfNegotiateType
	 */
	public function testNegotiateType() {
		$this->assertEquals(
			'text/html',
			wfNegotiateType(
				[ 'application/xhtml+xml' => 1.0,
					'text/html' => 0.7,
					'text/plain' => 0.5,
					'text/*' => 0.2 ],
				[ 'text/html' => 1.0 ] ) );
		$this->assertEquals(
			'application/xhtml+xml',
			wfNegotiateType(
				[ 'application/xhtml+xml' => 1.0,
					'text/html' => 0.7,
					'text/plain' => 0.5,
					'text/*' => 0.2 ],
				[ 'application/xhtml+xml' => 1.0,
					'text/html' => 0.5 ] ) );
		$this->assertEquals(
			'text/html',
			wfNegotiateType(
				[ 'text/html' => 1.0,
					'text/plain' => 0.5,
					'text/*' => 0.5,
					'application/xhtml+xml' => 0.2 ],
				[ 'application/xhtml+xml' => 1.0,
					'text/html' => 0.5 ] ) );
		$this->assertEquals(
			'text/html',
			wfNegotiateType(
				[ 'text/*' => 1.0,
					'image/*' => 0.7,
					'*/*' => 0.3 ],
				[ 'application/xhtml+xml' => 1.0,
					'text/html' => 0.5 ] ) );
		$this->assertNull(
			wfNegotiateType(
				[ 'text/*' => 1.0 ],
				[ 'application/xhtml+xml' => 1.0 ] ) );
	}

	/**
	 * @covers ::wfDebug
	 * @covers ::wfDebugMem
	 */
	public function testDebugFunctionTest() {
		$debugLogFile = $this->getNewTempFile();

		$this->setMwGlobals( [
			'wgDebugLogFile' => $debugLogFile,
			#  @todo FIXME: $wgDebugTimestamps should be tested
			'wgDebugTimestamps' => false
		] );

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

		$settings = [
			'gzip' => true,
			'bzip' => false,
			'*' => false,
			'compress, gzip' => true,
			'gzip;q=1.0' => true,
			'foozip' => false,
			'foo*zip' => false,
			'gzip;q=abcde' => true, // is this REALLY valid?
			'gzip;q=12345678.9' => true,
			' gzip' => true,
		];

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

		$pcts = [
			[ 6 / 7, '0.86%', 2, false ],
			[ 3 / 3, '1%' ],
			[ 22 / 7, '3.14286%', 5 ],
			[ 3 / 6, '0.5%' ],
			[ 1 / 3, '0%', 0 ],
			[ 10 / 3, '0%', -1 ],
			[ 3 / 4 / 5, '0.1%', 1 ],
			[ 6 / 7 * 8, '6.8571428571%', 10 ],
		];

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

	public static function provideShorthand() {
		// Syntax: [ shorthand, expected integer ]
		return [
			# Null, empty ...
			[ '', -1 ],
			[ '  ', -1 ],
			[ null, -1 ],

			# Failures returns 0 :(
			[ 'ABCDEFG', 0 ],
			[ 'Ak', 0 ],

			# Int, strings with spaces
			[ 1, 1 ],
			[ ' 1 ', 1 ],
			[ 1023, 1023 ],
			[ ' 1023 ', 1023 ],

			# kilo, Mega, Giga
			[ '1k', 1024 ],
			[ '1K', 1024 ],
			[ '1m', 1024 * 1024 ],
			[ '1M', 1024 * 1024 ],
			[ '1g', 1024 * 1024 * 1024 ],
			[ '1G', 1024 * 1024 * 1024 ],

			# Negatives
			[ -1, -1 ],
			[ -500, -500 ],
			[ '-500', -500 ],
			[ '-1k', -1024 ],

			# Zeroes
			[ '0', 0 ],
			[ '0k', 0 ],
			[ '0M', 0 ],
			[ '0G', 0 ],
			[ '-0', 0 ],
			[ '-0k', 0 ],
			[ '-0M', 0 ],
			[ '-0G', 0 ],
		];
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
		$this->markTestSkippedIfNoDiff3();

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

		return [
			// #0: clean merge
			[
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
			],

			// #1: conflict, fail
			[
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
			],
		];
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
		return [
			// Testcase for T30627
			[
				'https://example.org/test.cgi?id=12345',
				[ 'https://org.example./test.cgi?id=12345' ]
			],
			[
				// mailtos are handled special
				// is this really right though? that final . probably belongs earlier?
				'mailto:wiki@wikimedia.org',
				[ 'mailto:org.wikimedia@wiki.' ]
			],

			// file URL cases per T30627...
			[
				// three slashes: local filesystem path Unix-style
				'file:///whatever/you/like.txt',
				[ 'file://./whatever/you/like.txt' ]
			],
			[
				// three slashes: local filesystem path Windows-style
				'file:///c:/whatever/you/like.txt',
				[ 'file://./c:/whatever/you/like.txt' ]
			],
			[
				// two slashes: UNC filesystem path Windows-style
				'file://intranet/whatever/you/like.txt',
				[ 'file://intranet./whatever/you/like.txt' ]
			],
			// Multiple-slash cases that can sorta work on Mozilla
			// if you hack it just right are kinda pathological,
			// and unreliable cross-platform or on IE which means they're
			// unlikely to appear on intranets.
			// Those will survive the algorithm but with results that
			// are less consistent.

			// protocol-relative URL cases per T31854...
			[
				'//example.org/test.cgi?id=12345',
				[
					'http://org.example./test.cgi?id=12345',
					'https://org.example./test.cgi?id=12345'
				]
			],
		];
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
		$a = [];
		$protocols = [ 'HTTP' => 'http:', 'HTTPS' => 'https:', 'protocol-relative' => '' ];
		foreach ( $protocols as $pDesc => $p ) {
			$a = array_merge( $a, [
				[
					"$p//www.example.com",
					[],
					false,
					"No matches for empty domains array, $pDesc URL"
				],
				[
					"$p//www.example.com",
					[ 'www.example.com' ],
					true,
					"Exact match in domains array, $pDesc URL"
				],
				[
					"$p//www.example.com",
					[ 'example.com' ],
					true,
					"Match without subdomain in domains array, $pDesc URL"
				],
				[
					"$p//www.example2.com",
					[ 'www.example.com', 'www.example2.com', 'www.example3.com' ],
					true,
					"Exact match with other domains in array, $pDesc URL"
				],
				[
					"$p//www.example2.com",
					[ 'example.com', 'example2.com', 'example3,com' ],
					true,
					"Match without subdomain with other domains in array, $pDesc URL"
				],
				[
					"$p//www.example4.com",
					[ 'example.com', 'example2.com', 'example3,com' ],
					false,
					"Domain not in array, $pDesc URL"
				],
				[
					"$p//nds-nl.wikipedia.org",
					[ 'nl.wikipedia.org' ],
					false,
					"Non-matching substring of domain, $pDesc URL"
				],
			] );
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
		$this->setMwGlobals( [
			'wgDBname' => 'example',
			'wgDBprefix' => '',
		] );
		$this->assertEquals(
			wfWikiID(),
			'example'
		);

		$this->setMwGlobals( [
			'wgDBname' => 'example',
			'wgDBprefix' => 'mw_',
		] );
		$this->assertEquals(
			wfWikiID(),
			'example-mw_'
		);
	}

	public function testWfMemcKey() {
		$cache = ObjectCache::getLocalClusterInstance();
		$this->assertEquals(
			$cache->makeKey( 'foo', 123, 'bar' ),
			wfMemcKey( 'foo', 123, 'bar' )
		);
	}

	public function testWfForeignMemcKey() {
		$cache = ObjectCache::getLocalClusterInstance();
		$keyspace = $this->readAttribute( $cache, 'keyspace' );
		$this->assertEquals(
			wfForeignMemcKey( $keyspace, '', 'foo', 'bar' ),
			$cache->makeKey( 'foo', 'bar' )
		);
	}

	public function testWfGlobalCacheKey() {
		$cache = ObjectCache::getLocalClusterInstance();
		$this->assertEquals(
			$cache->makeGlobalKey( 'foo', 123, 'bar' ),
			wfGlobalCacheKey( 'foo', 123, 'bar' )
		);
	}

	public static function provideWfShellWikiCmdList() {
		global $wgPhpCli;

		return [
			[ 'eval.php', [ '--help', '--test' ], [],
				"'$wgPhpCli' 'eval.php' '--help' '--test'",
				"Called eval.php --help --test" ],
			[ 'eval.php', [ '--help', '--test space' ], [ 'php' => 'php5' ],
				"'php5' 'eval.php' '--help' '--test space'",
				"Called eval.php --help --test with php option" ],
			[ 'eval.php', [ '--help', '--test', 'X' ], [ 'wrapper' => 'MWScript.php' ],
				"'$wgPhpCli' 'MWScript.php' 'eval.php' '--help' '--test' 'X'",
				"Called eval.php --help --test with wrapper option" ],
			[
				'eval.php',
				[ '--help', '--test', 'y' ],
				[ 'php' => 'php5', 'wrapper' => 'MWScript.php' ],
				"'php5' 'MWScript.php' 'eval.php' '--help' '--test' 'y'",
				"Called eval.php --help --test with wrapper and php option"
			],
		];
	}
	/* @todo many more! */
}
