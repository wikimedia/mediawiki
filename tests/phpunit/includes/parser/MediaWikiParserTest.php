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
			$regex = '/' .preg_quote( DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR, '/' ) . '(\w+)/';
			$m = array();
			if ( preg_match( $regex, $fileName, $m ) ) {
				$extensionName = $m[1];
			} else {
				// Either a "core" parser test, or the user doesn't keep their
				// extensions in the extension directory.
				// Fallback to highest level directory.
				$extensionName = basename( dirname( $fileName ) );
			}
			$testsName = $extensionName . '⁄' . basename( $fileName, '.txt' );
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

			$parserTester = new $parserTestClassName( $testsName );
			$suite->addTestSuite( new ReflectionClass ( $parserTester ) );
		}

		return $suite;
	}
}
