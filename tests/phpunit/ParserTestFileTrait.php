<?php

use MediaWiki\Tests\Common\Parser\ParserTestRunner;
use MediaWiki\Tests\Common\Parser\PhpunitTestRecorder;
use Wikimedia\Parsoid\ParserTests\Test as ParserTest;
use Wikimedia\Parsoid\ParserTests\TestFileReader;
use Wikimedia\Parsoid\ParserTests\TestMode as ParserTestMode;
use Wikimedia\ScopedCallback;

/**
 * This is a trait for running parser tests under phpunit.  It
 * is expected that the parent class defines the filename of the
 * test to run as `static::$filename`; see `ParserTest.php.template`.
 */
trait ParserTestFileTrait {
	public static ?TestFileReader $fileInfo = null;
	public static ?ParserTestRunner $runner = null;
	private static ?ScopedCallback $teardown = null;

	public static function getFileInfo(): TestFileReader {
		if ( static::$fileInfo === null ) {
			try {
				static::$fileInfo = TestFileReader::read(
					static::$filename,
					static function ( $msg ) {
						wfDeprecatedMsg( $msg, '1.35', false, false );
					}
				);
			} catch ( Exception $e ) {
				// Friendlier wrapping for any syntax errors that might occur.
				throw new RuntimeException(
					static::$filename . ': ' . $e->getMessage()
				);
			}
		}
		return static::$fileInfo;
	}

	/** Provide test cases to `testParse`. */
	public static function provideParse(): Generator {
		$fileInfo = static::getFileInfo();
		$mkName = static fn ( $test, $mode ) =>
			basename( static::$filename ) . ': ' .
			$test->testName . " [" . $mode . "]";

		// Legacy tests
		$mode = new ParserTestMode( 'legacy' );
		foreach ( $fileInfo->testCases as $test ) {
			$name = $mkName( $test, $mode );
			yield $name => [ static::$filename, $test, $mode ];
		}
		if ( static::$isCore ) {
			// Only run Parsoid tests on extensions for now, since Parsoid
			// has its own copy of core's parser tests which it runs in its
			// own test suite.
			return;
		}
		if ( !isset( $fileInfo->fileOptions['parsoid-compatible'] ) ) {
			// Not compatible with Parsoid integrated mode, skip it.
			return;
		}

		// Parsoid tests
		$runner = static::getTestRunner();
		$runnerOpts = $runner->getOptions();
		$validTestModes = $runner->computeValidTestModes(
			$runner->getRequestedTestModes(), $fileInfo->fileOptions
		);
		foreach ( $fileInfo->testCases as $test ) {
			$testModes = $test->computeTestModes( $validTestModes );
			$subtests = [];
			$test->testAllModes(
				$testModes,
				$runnerOpts,
				static function ( ParserTest $t, string $modeStr, array $options )
					use ( &$subtests, $test, $mkName ): void {
					if ( $modeStr !== 'selser' || $t->changetree !== null ) {
						// $t could be a clone of $test
						// Ensure that updates to knownFailures in $t are reflected in $test
						$t->knownFailures = &$test->knownFailures;
						$mode = new ParserTestMode( $modeStr, $t->changetree );
						$name = $mkName( $t, $mode );
						$subtests[$name] = [
							static::$filename, $t, $mode
						];
					}
				}
			);
			yield from $subtests;

			// Add a "selser-auto-composite" composite test
			if (
				in_array( 'selser', $testModes, true ) &&
				( $runnerOpts['selser'] ?? null ) !== 'noauto' &&
				( $test->options['parsoid']['selser'] ?? null ) !== 'noauto'
			) {
				$mode = new ParserTestMode( 'selser-auto-composite', $runnerOpts['changetree'] ?? null );
				$name = $mkName( $test, $mode );
				yield $name => [ static::$filename, $test, $mode ];
			}
		}
	}

	public static function setUpBeforeClass(): void {
		// MediaWikiIntegrationTestCase leaves its test DB hanging around.
		// we want to make sure we have a clean instance, so tear down any
		// existing test DB.  This has no effect if no test DB exists.
		MediaWikiIntegrationTestCase::teardownTestDB();
		// Similarly, make sure we don't reuse Test users from other tests
		TestUserRegistry::clear();

		$runner = static::getTestRunner();
		$teardown = static::$teardown;
		$teardown = $runner->setupDatabase( $teardown );
		$teardown = $runner->staticSetup( $teardown );
		$teardown = $runner->setupUploads( $teardown );
		$teardown = $runner->addArticles(
			static::getFileInfo()->articles, $teardown
		);
		static::$teardown = $teardown;
	}

	public static function tearDownAfterClass(): void {
		if ( static::$runner?->getOptions()['updateKnownFailures'] ?? false ) {
			static::$runner->updateKnownFailures( static::$fileInfo );
		}

		if ( static::$teardown !== null ) {
			ScopedCallback::consume( static::$teardown );
			static::$teardown = null;
		}
	}

	public static function getTestRunner(): ParserTestRunner {
		if ( static::$runner === null ) {
			$ptRecorder = new PhpunitTestRecorder;
			$runnerOpts = json_decode( getenv( "PARSERTEST_FLAGS" ) ?: "[]", true );
			// PHPUnit test runners requires all tests to be pregenerated.
			// But, generating Parsoid selser edit trees requires the DOM.
			// So, we cannot pregenerate Parsoid selser auto-edit tests.
			// They have to be generated dynamically. So, set this to 0.
			// We will handle auto-edit selser tests as a composite test.
			$runnerOpts['numchanges'] = 0;

			static::$runner = new ParserTestRunner(
				$ptRecorder, $runnerOpts
			);
		}
		return static::$runner;
	}

	/**
	 * @dataProvider provideParse
	 * @covers \MediaWiki\OutputTransform\Stages\ParsoidLanguageConverter
	 * @covers \MediaWiki\Parser\BlockLevelPass
	 * @covers \MediaWiki\Parser\CacheTime
	 * @covers \MediaWiki\Parser\CoreMagicVariables
	 * @covers \MediaWiki\Parser\CoreParserFunctions
	 * @covers \MediaWiki\Parser\CoreTagHooks
	 * @covers \MediaWiki\Parser\PPFrame
	 * @covers \MediaWiki\Parser\PPNode
	 * @covers \MediaWiki\Parser\Parser
	 * @covers \MediaWiki\Parser\ParserOptions
	 * @covers \MediaWiki\Parser\ParserOutput
	 * @covers \MediaWiki\Parser\ParserOutputFlags
	 * @covers \MediaWiki\Parser\ParserOutputStringSets
	 * @covers \MediaWiki\Parser\Preprocessor
	 * @covers \MediaWiki\Parser\Sanitizer
	 * @covers \MediaWiki\Parser\StripState
	 */
	public function testParse( string $filename, ParserTest $test, ParserTestMode $mode ) {
		$runner = static::getTestRunner();
		$skipMessage = $runner->getFileSkipMessage(
			$mode->isLegacy(), static::$fileInfo->fileOptions, $filename
		) ?? $runner->getTestSkipMessage(
			$test, $mode->isLegacy()
		);
		if ( $skipMessage !== null ) {
			$this->markTestSkipped( $skipMessage );
		}
		$runner->getRecorder()->setTestCase( $this );
		$result = $runner->runTest( $test, $mode );
		$this->assertEquals( $result->expected, $result->actual );
	}
}
