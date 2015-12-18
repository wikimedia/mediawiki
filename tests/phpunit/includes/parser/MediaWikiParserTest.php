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
	 * @param int $flags Bitwise flag to filter out the $wgParserTestFiles that
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
		$testList = array();
		$counter = 0;
		foreach ( $filesToTest as $fileName ) {
			// Call the highest level directory the extension name.
			// It may or may not actually be, but it should be close
			// enough to cause there to be separate names for different
			// things, which is good enough for our purposes.
			$extensionName = basename( dirname( $fileName ) );
			$testsName = $extensionName . '__' . basename( $fileName, '.txt' );
			$escapedFileName = strtr( $fileName, array( "'" => "\\'", '\\' => '\\\\' ) );
			$parserTestClassName = ucfirst( $testsName );
			// Official spec for class names: http://php.net/manual/en/language.oop5.basic.php
			// Prepend 'ParserTest_' to be paranoid about it not starting with a number
			$parserTestClassName = 'ParserTest_' . preg_replace( '/[^a-zA-Z0-9_\x7f-\xff]/', '_', $parserTestClassName );
			if ( isset( $testList[$parserTestClassName] ) ) {
				// If a conflict happens, gives a very unclear fatal.
				// So as a last ditch effort to prevent that eventuality, if there
				// is a conflict, append a number.
				$counter++;
				$parserTestClassName .= $counter;
			}
			$testList[$parserTestClassName] = true;
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
