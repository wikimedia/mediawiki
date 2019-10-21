<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\ScopedCallback;

/**
 * The UnitTest must be either a class that inherits from MediaWikiTestCase
 * or a class that provides a public static suite() method which returns
 * an PHPUnit_Framework_Test object
 *
 * @group Parser
 * @group ParserTests
 * @group Database
 */
class ParserTestTopLevelSuite extends PHPUnit_Framework_TestSuite {
	/** @var ParserTestRunner */
	private $ptRunner;

	/** @var ScopedCallback */
	private $ptTeardownScope;

	private $oldTablePrefix = '';

	/**
	 * @defgroup filtering_constants Filtering constants
	 *
	 * Limit inclusion of parser tests files coming from MediaWiki core
	 * @{
	 */

	/** Include files shipped with MediaWiki core */
	const CORE_ONLY = 1;
	/** Include non core files as set in $wgParserTestFiles */
	const NO_CORE = 2;
	/** Include anything set via $wgParserTestFiles */
	const WITH_ALL = self::CORE_ONLY | self::NO_CORE;

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
	 * @return PHPUnit_Framework_TestSuite
	 */
	public static function suite( $flags = self::CORE_ONLY ) {
		return new self( $flags );
	}

	function __construct( $flags ) {
		parent::__construct();

		$this->ptRecorder = new PhpunitTestRecorder;
		$this->ptRunner = new ParserTestRunner( $this->ptRecorder );

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

			if ( isset( $testList[$parserTestClassName] ) ) {
				// If there is a conflict, append a number.
				$counter++;
				$parserTestClassName .= $counter;
			}
			$testList[$parserTestClassName] = true;

			// Previously we actually created a class here, with eval(). We now
			// just override the name.

			self::debug( "Adding test class $parserTestClassName" );
			$this->addTest( new ParserTestFileSuite(
				$this->ptRunner, $parserTestClassName, $fileName ) );
		}
	}

	public function setUp() {
		wfDebug( __METHOD__ );

		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$db = $lb->getConnection( DB_MASTER );
		$type = $db->getType();
		$prefix = MediaWikiTestCase::DB_PREFIX;
		$this->oldTablePrefix = $db->tablePrefix();
		MediaWikiTestCase::setupTestDB( $db, $prefix );
		CloneDatabase::changePrefix( $prefix );

		$this->ptRunner->setDatabase( $db );

		MediaWikiTestCase::resetNonServiceCaches();

		MediaWikiTestCase::installMockMwServices();
		$teardown = new ScopedCallback( function () {
			MediaWikiTestCase::restoreMwServices();
		} );

		$teardown = $this->ptRunner->setupUploads( $teardown );
		$this->ptTeardownScope = $teardown;
	}

	public function tearDown() {
		wfDebug( __METHOD__ );
		if ( $this->ptTeardownScope ) {
			ScopedCallback::consume( $this->ptTeardownScope );
		}
		CloneDatabase::changePrefix( $this->oldTablePrefix );
	}

	/**
	 * Write $msg under log group 'tests-parser'
	 * @param string $msg Message to log
	 */
	protected static function debug( $msg ) {
		wfDebugLog( 'tests-parser', wfGetCaller() . ' ' . $msg );
	}
}
