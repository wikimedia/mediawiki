<?php

use MediaWiki\Logger\LegacyLogger;
use MediaWiki\MainConfigNames;

/**
 * @group Database
 * @group GlobalFunctions
 */
class GlobalTest extends MediaWikiIntegrationTestCase {
	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::UrlProtocols => [
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
		$this->expectDeprecationAndContinue( '/wfArrayDiff2/' );
		$this->assertEquals(
			$expected, wfArrayDiff2( $a, $b )
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
		$this->assertSame( 10, strlen( wfRandomString( 10 ) ), 'length' );
		$this->assertSame( 1, preg_match( '/^[0-9a-f]+$/i', wfRandomString() ), 'pattern' );
	}

	/**
	 * @covers ::wfUrlencode
	 */
	public function testUrlencode() {
		$this->assertEquals(
			"%E7%89%B9%E5%88%A5:Contributions/Foobar",
			wfUrlencode( "\xE7\x89\xB9\xE5\x88\xA5:Contributions/Foobar" ) );
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
			[ 'foo[bar]=baz', [ 'foo' => [ 'bar' => 'baz' ] ] ],
			[ 'foo%5Bbar%5D=baz', [ 'foo' => [ 'bar' => 'baz' ] ] ], // urldecoding test 2
			[
				'foo%5Bbar%5D=baz&foo%5Bqwerty%5D=asdf',
				[ 'foo' => [ 'bar' => 'baz', 'qwerty' => 'asdf' ] ]
			],
			[ 'foo%5B0%5D=bar&foo%5B1%5D=baz', [ 'foo' => [ 0 => 'bar', 1 => 'baz' ] ] ],
			[
				'foo%5Bbar%5D%5Bbar%5D=baz',
				[ 'foo' => [ 'bar' => [ 'bar' => 'baz' ] ] ]
			],
			[ 'foo[]=x&foo[]=y', [ 'foo' => [ '' => 'y' ] ] ], // implicit keys are NOT handled like in PHP (bug?)
			[ 'foo=x&foo[]=y', [ 'foo' => [ '' => 'y' ] ] ], // mixed value/array doesn't cause errors
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
	 * @covers ::wfDebug
	 */
	public function testDebugFunctionTest() {
		$debugLogFile = $this->getNewTempFile();

		$this->overrideConfigValue( MainConfigNames::DebugLogFile, $debugLogFile );
		$this->setLogger( 'wfDebug', new LegacyLogger( 'wfDebug' ) );

		unlink( $debugLogFile );
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
	 * @dataProvider provideWfPercentTest
	 */
	public function testWfPercentTest( float $input,
		string $expected,
		int $accuracy = 2,
		bool $round = true
	) {
		$this->assertSame( $expected, wfPercent( $input, $accuracy, $round ) );
	}

	public static function provideWfPercentTest() {
		return [
			[ 6 / 7, '0.86%', 2, false ],
			[ 3 / 3, '1%' ],
			[ 22 / 7, '3.14286%', 5 ],
			[ 3 / 6, '0.5%' ],
			[ 1 / 3, '0%', 0 ],
			[ 10 / 3, '0%', -1 ],
			[ 123.456, '120%', -1 ],
			[ 3 / 4 / 5, '0.1%', 1 ],
			[ 6 / 7 * 8, '6.8571428571%', 10 ],
		];
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
	 * @covers ::wfMerge
	 */
	public function testMerge_worksWithLessParameters() {
		$this->markTestSkippedIfNoDiff3();

		$mergedText = null;
		$successfulMerge = wfMerge( "old1\n\nold2", "old1\n\nnew2", "new1\n\nold2", $mergedText );

		$mergedText = null;
		$conflictingMerge = wfMerge( 'old', 'old and mine', 'old and yours', $mergedText );

		$this->assertTrue( $successfulMerge );
		$this->assertFalse( $conflictingMerge );
	}

	/**
	 * @param string $old Text as it was in the database
	 * @param string $mine Text submitted while user was editing
	 * @param string $yours Text submitted by the user
	 * @param bool $expectedMergeResult Whether the merge should be a success
	 * @param string $expectedText Text after merge has been completed
	 * @param string $expectedMergeAttemptResult Diff3 output if conflicts occur
	 *
	 * @dataProvider provideMerge
	 * @group medium
	 * @covers ::wfMerge
	 */
	public function testMerge(
		$old, $mine, $yours, $expectedMergeResult, $expectedText, $expectedMergeAttemptResult
	) {
		$this->markTestSkippedIfNoDiff3();

		$mergedText = null;
		$mergeAttemptResult = null;
		$isMerged = wfMerge( $old, $mine, $yours, $mergedText, $mergeAttemptResult );

		$msg = 'Merge should be a ';
		$msg .= $expectedMergeResult ? 'success' : 'failure';
		$this->assertEquals( $expectedMergeResult, $isMerged, $msg );
		$this->assertEquals( $expectedMergeAttemptResult, $mergeAttemptResult );

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

				// mergeAttemptResult:
				"",
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

				// mergeAttemptResult:
				"1,3c\n" .
				"one one one\n" .
				"\n" .
				"two two\n" .
				".\n",
			],
		];
	}

	/**
	 * Same tests as the UrlUtils method to ensure they don't fall out of sync
	 * @dataProvider UrlUtilsProviders::provideMatchesDomainList
	 * @covers ::wfMatchesDomainList
	 */
	public function testWfMatchesDomainList( $url, $domains, $expected ) {
		$this->hideDeprecated( 'wfMatchesDomainList' );

		$actual = wfMatchesDomainList( $url, $domains );
		$this->assertEquals( $expected, $actual );
	}

	/**
	 * @covers ::wfMkdirParents
	 */
	public function testWfMkdirParents() {
		// Should not return true if file exists instead of directory
		$fname = $this->getNewTempFile();
		$this->assertFalse( @wfMkdirParents( $fname ) );
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
