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

	public static function suite() {
		global $wgParserTestFiles;

		$suite = new PHPUnit_Framework_TestSuite;

		foreach ( $wgParserTestFiles as $filename ) {
			$testsName = basename( $filename, '.txt' );
			/* This used to be ucfirst( basename( dirname( $filename ) ) )
			 * and then was ucfirst( basename( $filename, '.txt' )
			 * but that didn't work with names like foo.tests.txt
			 */
			$className = str_replace( '.', '_',  ucfirst( basename( $filename, '.txt' ) ) );
			
			eval( "/** @group Database\n@group Parser\n*/ class $className extends NewParserTest { protected \$file = '" . strtr( $filename, array( "'" => "\\'", '\\' => '\\\\' ) ) . "'; } " );

			$parserTester = new $className( $testsName );
			$suite->addTestSuite( new ReflectionClass ( $parserTester ) );
		}
		

		return $suite;
	}
}
