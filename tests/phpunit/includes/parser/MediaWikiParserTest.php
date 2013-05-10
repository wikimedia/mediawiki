<?php
require_once( __DIR__ . '/NewParserTest.php' );

/**
 * The UnitTest must be either a class that inherits from MediaWikiTestCase
 * or a class that provides a public static suite() method which returns
 * an PHPUnit_Framework_Test object
 *
 * @group Parser
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
	const WITH_CORE = 1;
	/** Include non core files as set in $wgParserTestFiles */
	const WITH_REST = 2;
	/** Include anything set via $wgParserTestFiles */
	const WITH_ALL  = 3;  # WITH_CORE | WITH_REST

	/** @} */

	/**
	 * Get a PHPUnit test suite of parser tests. Optionally filtered with
	 * $flags.
	 *
	 * @par Examples:
	 * Get a suite of parser tests shipped by MediaWiki core:
	 * @code
	 * MediaWikiParserTest::suite( MediaWikiParserTest::WITH_CORE );
	 * @endcode
	 * Get a suite of various parser tests, like extensions:
	 * @code
	 * MediaWikiParserTest::suite( MediaWikiParserTest::WITH_REST );
	 * @endcode
	 * Get any test defined via $wgParserTestFiles:
	 * @code
	 * MediaWikiParserTest::suite( MediaWikiParserTest::WITH_ALL );
	 * @endcode
	 *
	 * @param $flags bitwise flag to filter out the $wgParserTestFiles that
	 * will be included.  Default: MediaWikiParserTest::WITH_CORE
	 *
	 * @return PHPUnit_Framework_TestSuite
	 */
	public static function suite( $flags = self::WITH_CORE ) {
		var_dump("callled...");
		if( is_string( $flags ) ) {
			$flags = self::WITH_CORE;
		}
		global $wgParserTestFiles, $IP;

		$mwTestDir = $IP.'/tests/';

		$wantsCore = ($flags & self::WITH_CORE);
		$wantsRest = ($flags & self::WITH_REST);
		$filesToTest = array();
		foreach( $wgParserTestFiles as $parserTestFile ) {
			$isCore = ( 0 === strpos( $parserTestFile, $mwTestDir ) );

			if( $isCore && $wantsCore ) {
				var_dump( "IS CORE AND WE WANT CORE: $parserTestFile\n" );
				$filesToTest[] = $parserTestFile;
			} elseif( !$isCore && $wantsRest ) {
				var_dump( "IS NOT CORE BUT WANT REST: $parserTestFile\n" );
				$filesToTest[] = $parserTestFile;
			} else {
				var_dump( "ISCORE: $isCore  WANTSCORE: $wantsCore  FLAGS: $flags");
			}
		}

		$suite = new PHPUnit_Framework_TestSuite;

		var_dump( "TESTDIR: $mwTestDir" );
		var_dump( "Globals: " .var_export( $wgParserTestFiles, 1 ) );
		var_dump( "TO TEST: " .var_export( $filesToTest, 1 ) );
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

			$parserTester = new $parserTestClassName( $testsName );
			$suite->addTestSuite( new ReflectionClass ( $parserTester ) );
		}

		return $suite;
	}
}
