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
		$counter = 0;

		foreach ( $wgParserTestFiles as $fileName ) {
			$testsName = basename( dirname( $fileName ) ) . '⁄' . basename( $fileName, '.txt' );
			$escapedFileName = strtr( $fileName, array( "'" => "\\'", '\\' => '\\\\' ) );
			/* This used to be ucfirst( basename( dirname( $filename ) ) )
			 * and then was ucfirst( basename( $filename, '.txt' )
			 * but that didn't work with names like foo.tests.txt
			 * and then was basename( $fileName, '.txt' )
			 */
			$parserTestClassName = ucfirst( $testsName );
			// Official spec for class names: http://php.net/manual/en/language.oop5.basic.php
			// Prepend 'PT_' to be paranoid about it not starting with a number
			$parserTestClassName = 'PT_' . preg_replace( '/[^a-zA-Z0-9_\x7f-\xff]/', '_', $parserTestClassName );
			if ( isset( $testList[$parserTestClassName] ) ) {
				// If a conflict happens, gives a very unclear fatal.
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

			$parserTester = new $parserTestClassName( $testsName );
			$suite->addTestSuite( new ReflectionClass ( $parserTester ) );
		}

		return $suite;
	}
}
