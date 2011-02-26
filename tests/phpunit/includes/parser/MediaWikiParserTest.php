<?php

require_once( dirname( __FILE__ ) . '/ParserHelpers.php' );
require_once( dirname( __FILE__ ) . '/NewParserTest.php' );
require_once( dirname(dirname(dirname( __FILE__ ))) . '/bootstrap.php' );

/**
 * The UnitTest must be either a class that inherits from PHPUnit_Framework_TestCase 
 * or a class that provides a public static suite() method which returns 
 * an PHPUnit_Framework_Test object
 * 
 * @group Parser
 * @group Database
 */
class MediaWikiParserTest {

	public static function suite() {
		global $IP, $wgParserTestFiles;

		$suite = new PHPUnit_Framework_TestSuite;

		foreach ( $wgParserTestFiles as $filename ) {
			$testsName = basename( $filename, '.txt' );
			$className = /*ucfirst( basename( dirname( $filename ) ) ) .*/ ucfirst( basename( $filename, '.txt' ) );
			
			eval( "/** @group Database\n@group Parser\n*/ class $className extends NewParserTest { protected \$file = \"" . addslashes( $filename ) . "\"; } " );

			$parserTester = new $className( $testsName );
			$suite->addTestSuite( new ReflectionClass ( $parserTester ) );
		}
		

		return $suite;
	}
}
