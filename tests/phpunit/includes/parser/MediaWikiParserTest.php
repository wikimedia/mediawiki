<?php
require_once __DIR__ . '/NewParserTest.php';

/**
 * The UnitTest must be either a class that inherits from MediaWikiTestCase
 * or a class that provides a public static suite() method which returns
 * an PHPUnit_Framework_Test object
 *
 * @group Parser
 * @group Database
 */
class MediaWikiParserTest {

	public static function suite() {
		global $wgParserTestFiles;

		$suite = new PHPUnit_Framework_TestSuite;
		$testList = array();

		foreach ( $wgParserTestFiles as $fileName ) {
			$testsName = basename( $fileName, '.txt' );
			$escapedFileName = strtr( $fileName, array( "'" => "\\'", '\\' => '\\\\' ) );
			/* This used to be ucfirst( basename( dirname( $filename ) ) )
			 * and then was ucfirst( basename( $filename, '.txt' )
			 * but that didn't work with names like foo.tests.txt
			 */
			$parserTestClassName = str_replace( '.', '_', ucfirst( $testsName ) );
			if ( isset( $testList[$parserTestClassName] ) ) {
				// CharSpanRange was causing a conflict, which gives
				// a very unclear error.
				$parserTestClassName .= mt_rand();
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

			$parserTester = new $parserTestClassName( $testsName );
			$suite->addTestSuite( new ReflectionClass ( $parserTester ) );
		}

		return $suite;
	}
}
