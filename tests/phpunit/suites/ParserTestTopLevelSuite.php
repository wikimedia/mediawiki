<?php

use PHPUnit\Framework\TestSuite;
use Wikimedia\ScopedCallback;

/**
 * The UnitTest must be either a class that inherits from MediaWikiIntegrationTestCase
 * or a class that provides a public static suite() method which returns
 * an PHPUnit\Framework\Test object
 *
 * @group Parser
 * @group ParserTests
 * @group Database
 */
class ParserTestTopLevelSuite extends TestSuite {
	use SuiteEventsTrait;

	/** @var PhpunitTestRecorder */
	private $ptRecorder;

	/** @var ParserTestRunner */
	private $ptRunner;

	/** @var ScopedCallback */
	private $ptTeardownScope;

	/**
	 * @defgroup filtering_constants Filtering constants
	 *
	 * Limit inclusion of parser tests files coming from MediaWiki core
	 * @{
	 */

	/** Include files shipped with MediaWiki core */
	public const CORE_ONLY = 1;
	/** Include non core files as set in $wgParserTestFiles */
	public const NO_CORE = 2;
	/** Include anything set via $wgParserTestFiles */
	public const WITH_ALL = self::CORE_ONLY | self::NO_CORE;

	/** @} */

	/**
	 * Get a PHPUnit test suite of parser tests. Optionally filtered with
	 * $flags.
	 *
	 * @par Examples:
	 * Get a suite of parser tests shipped by MediaWiki core:
	 * @code
	 * ParserTestTopLevelSuite::suite( ParserTestTopLevelSuite::CORE_ONLY );
	 * @endcode
	 * Get a suite of various parser tests, like extensions:
	 * @code
	 * ParserTestTopLevelSuite::suite( ParserTestTopLevelSuite::NO_CORE );
	 * @endcode
	 * Get any test defined via $wgParserTestFiles:
	 * @code
	 * ParserTestTopLevelSuite::suite( ParserTestTopLevelSuite::WITH_ALL );
	 * @endcode
	 *
	 * @param int $flags Bitwise flag to filter out the $wgParserTestFiles that
	 * will be included.  Default: ParserTestTopLevelSuite::CORE_ONLY
	 *
	 * @return TestSuite
	 */
	public static function suite( $flags = self::CORE_ONLY ) {
		return new self( $flags );
	}

	public function __construct( $flags, array $parserTestFlags = null ) {
		parent::__construct();

		$this->ptRecorder = new PhpunitTestRecorder;
		$runnerOpts = $parserTestFlags ?? json_decode( getenv( "PARSERTEST_FLAGS" ) ?: "[]", true );
		// PHPUnit test runners requires all tests to be pregenerated.
		// But, generating Parsoid selser edit trees requires the DOM.
		// So, we cannot pregenerate Parsoid selser auto-edit tests.
		// They have to be generated dynamically. So, set this to 0.
		// We will handle auto-edit selser tests as a composite test.
		$runnerOpts['numchanges'] = 0;
		$this->ptRunner = new ParserTestRunner(
			$this->ptRecorder, $runnerOpts
		);

		if ( is_string( $flags ) ) {
			$flags = self::CORE_ONLY;
		}
		global $IP;

		$mwTestDir = $IP . '/tests/';

		# Human friendly helpers
		$wantsCore = ( $flags & self::CORE_ONLY );
		$wantsRest = ( $flags & self::NO_CORE );

		# Will hold the .txt parser test files we will include
		$filesToTest = [];

		# Filter out .txt files
		$files = ParserTestRunner::getParserTestFiles();
		foreach ( $files as $extName => $parserTestFile ) {
			$isCore = ( strpos( $parserTestFile, $mwTestDir ) === 0 );

			if ( $isCore && $wantsCore ) {
				self::debug( "included core parser tests: $parserTestFile" );
				$filesToTest[$extName] = $parserTestFile;
			} elseif ( !$isCore && $wantsRest ) {
				self::debug( "included non core parser tests: $parserTestFile" );
				$filesToTest[$extName] = $parserTestFile;
			} else {
				self::debug( "skipped parser tests: $parserTestFile" );
			}
		}
		self::debug( 'parser tests files: '
			. implode( ' ', $filesToTest ) );

		$testList = [];
		$counter = 0;
		foreach ( $filesToTest as $extensionName => $fileName ) {
			$isCore = ( strpos( $fileName, $mwTestDir ) === 0 );
			if ( is_int( $extensionName ) ) {
				// If there's no extension name because this is coming
				// from the legacy global, then assume the next level directory
				// is the extension name (e.g. extensions/FooBar/parserTests.txt).
				$extensionName = basename( dirname( $fileName ) );
			}
			$testsName = $extensionName . '__' . basename( $fileName, '.txt' );
			$parserTestClassName = ucfirst( $testsName );

			// Official spec for class names: https://www.php.net/manual/en/language.oop5.basic.php
			// Prepend 'ParserTest_' to be paranoid about it not starting with a number
			$parserTestClassName = 'ParserTest_' .
				preg_replace( '/[^a-zA-Z0-9_\x7f-\xff]/', '_', $parserTestClassName );

			$originalClassName = $parserTestClassName;
			while ( isset( $testList[$parserTestClassName] ) ) {
				// If there is a conflict, append a number.
				$counter++;
				$parserTestClassName = $originalClassName . '_' . $counter;
			}
			$testList[$parserTestClassName] = true;

			// Previously we actually created a class here, with eval(). We now
			// just override the name.

			self::debug( "Adding test class $parserTestClassName" );
			// Legacy parser
			$this->addTest( new ParserTestFileSuite(
				$this->ptRunner, "Legacy$parserTestClassName", $fileName ) );
			// Parsoid (only run this on extensions for now, since Parsoid
			// has its own copy of core's parser tests which it runs in its
			// own test suite)
			if ( !$isCore ) {
				$this->addTest( new ParsoidTestFileSuite(
					$this->ptRunner, "Parsoid$parserTestClassName", $fileName
				) );
			}
		}
	}

	protected function setUp(): void {
		// MediaWikiIntegrationTestCase leaves its test DB hanging around.
		// we want to make sure we have a clean instance, so tear down any
		// existing test DB.  This has no effect if no test DB exists.
		MediaWikiIntegrationTestCase::teardownTestDB();
		// Similarly, make sure we don't reuse Test users from other tests
		TestUserRegistry::clear();

		$teardown = $this->ptRunner->setupDatabase( null );
		$teardown = $this->ptRunner->staticSetup( $teardown );
		$teardown = $this->ptRunner->setupUploads( $teardown );
		$this->ptTeardownScope = $teardown;
	}

	protected function tearDown(): void {
		if ( $this->ptTeardownScope ) {
			ScopedCallback::consume( $this->ptTeardownScope );
		}
		TestUserRegistry::clear();
	}

	/**
	 * Write $msg under log group 'tests-parser'
	 * @param string $msg Message to log
	 */
	protected static function debug( $msg ) {
		wfDebugLog( 'tests-parser', wfGetCaller() . ' ' . $msg );
	}
}
