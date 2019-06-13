<?php
/**
 * The tests here verify the structure of the code.  This is for outright bugs,
 * not just style issues.
 */

class StructureTest extends MediaWikiTestCase {
	/**
	 * Verify all files that appear to be tests have file names ending in
	 * Test.  If the file names do not end in Test, they will not be run.
	 * @group medium
	 */
	public function testUnitTestFileNamesEndWithTest() {
		// realpath() also normalizes directory separator on windows for prefix compares
		$rootPath = realpath( __DIR__ . '/..' );
		$suitesPath = realpath( __DIR__ . '/../suites/' );
		$testClassRegex = implode( '|', [
			'ApiFormatTestBase',
			'ApiTestCase',
			'ApiQueryTestBase',
			'ApiQueryContinueTestBase',
			'MediaWikiLangTestCase',
			'MediaWikiMediaTestCase',
			'MediaWikiTestCase',
			'ResourceLoaderTestCase',
			'PHPUnit_Framework_TestCase',
			'\\?PHPUnit\\Framework\\TestCase',
			'TestCase', // \PHPUnit\Framework\TestCase with appropriate use statement
			'DumpTestCase',
			'SpecialPageTestBase',
		] );
		$testClassRegex = "/^class .* extends ($testClassRegex)/m";

		$results = $this->recurseFiles( $rootPath );

		$results = array_filter(
			$results,
			function ( $filename ) use ( $testClassRegex, $suitesPath ) {
				// Remove testUnitTestFileNamesEndWithTest false positives
				if ( strpos( $filename, $suitesPath ) === 0
					|| substr( $filename, -8 ) === 'Test.php'
				) {
					return false;
				}
				$contents = file_get_contents( $filename );
				return preg_match( $testClassRegex, $contents );
			}
		);
		$strip = strlen( $rootPath ) + 1;
		foreach ( $results as $k => $v ) {
			$results[$k] = substr( $v, $strip );
		}
		$this->assertEquals(
			[],
			$results,
			"Unit test file in $rootPath must end with Test."
		);
	}

	private function recurseFiles( $dir ) {
		return ( new File_Iterator_Facade() )->getFilesAsArray( $dir, [ '.php' ] );
	}
}
