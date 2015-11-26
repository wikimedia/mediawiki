<?php
require_once __DIR__ . '/NewParserTest.php';

/**
 * The UnitTest must be either a class that inherits from MediaWikiTestCase
 * or a class that provides a public static suite() method which returns
 * an PHPUnit_Framework_Test object
 *
 * @group Parser
 * @group ParserTests
 * @group Database
 */
class MediaWikiParserTest {

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
	const WITH_ALL = 3; # CORE_ONLY | NO_CORE

	/** @} */

	/**
	 * Get a PHPUnit test suite of parser tests. Optionally filtered with
	 * $flags.
	 *
	 * @par Examples:
	 * Get a suite of parser tests shipped by MediaWiki core:
	 * @code
	 * MediaWikiParserTest::suite( MediaWikiParserTest::CORE_ONLY );
	 * @endcode
	 * Get a suite of various parser tests, like extensions:
	 * @code
	 * MediaWikiParserTest::suite( MediaWikiParserTest::NO_CORE );
	 * @endcode
	 * Get any test defined via $wgParserTestFiles:
	 * @code
	 * MediaWikiParserTest::suite( MediaWikiParserTest::WITH_ALL );
	 * @endcode
	 *
	 * @param $flags bitwise flag to filter out the $wgParserTestFiles that
	 * will be included.  Default: MediaWikiParserTest::CORE_ONLY
	 *
	 * @return PHPUnit_Framework_TestSuite
	 */
	public static function suite( $flags = self::CORE_ONLY ) {
		if ( is_string( $flags ) ) {
			$flags = self::CORE_ONLY;
		}
		global $wgParserTestFiles, $IP;

		$mwTestDir = $IP . '/tests/';

		# Human friendly helpers
		$wantsCore = ( $flags & self::CORE_ONLY );
		$wantsRest = ( $flags & self::NO_CORE );

		# Will hold the .txt parser test files we will include
		$filesToTest = array();

		# Filter out .txt files
		foreach ( $wgParserTestFiles as $parserTestFile ) {
			$isCore = ( 0 === strpos( $parserTestFile, $mwTestDir ) );

			if ( $isCore && $wantsCore ) {
				self::debug( "included core parser tests: $parserTestFile" );
				$filesToTest[] = $parserTestFile;
			} elseif ( !$isCore && $wantsRest ) {
				self::debug( "included non core parser tests: $parserTestFile" );
				$filesToTest[] = $parserTestFile;
			} else {
				self::debug( "skipped parser tests: $parserTestFile" );
			}
		}
		self::debug( 'parser tests files: '
			. implode( ' ', $filesToTest ) );

		$suite = new PHPUnit_Framework_TestSuite;
		foreach ( $filesToTest as $fileName ) {
			$testsName = basename( $fileName, '.txt' );
			$escapedFileName = strtr( $fileName, array( "'" => "\\'", '\\' => '\\\\' ) );
			/* This used to be ucfirst( basename( dirname( $filename ) ) )
			 * and then was ucfirst( basename( $filename, '.txt' )
			 * but that didn't work with names like foo.tests.txt
			 */
			$parserTestClassName = str_replace( '.', '_', ucfirst( $testsName ) );
			$parserTestClassDefinition = <<<EOT
/**
 * @group Database
 * @group Parser
 * @group ParserTests
 * @group ParserTests_$parserTestClassName
 */
class $parserTestClassName extends NewParserTest {
	protected \$file = '$escapedFileName';
}
EOT;

			eval( $parserTestClassDefinition );
			self::debug( "Adding test class $parserTestClassName" );
			$suite->addTestSuite( $parserTestClassName );
		}
		return $suite;
	}

	/**
	 * Write $msg under log group 'tests-parser'
	 * @param string $msg Message to log
	 */
	protected static function debug( $msg ) {
		return wfDebugLog( 'tests-parser', wfGetCaller() . ' ' . $msg );
	}
}
