<?php
/**
 * The tests here verify the structure of the code.  This is for outright bugs,
 * not just style issues.
 */

class StructureTest extends MediaWikiTestCase {
	/**
	 * Verify all files that appear to be tests have file names ending in
	 * Test.  If the file names do not end in Test, they will not be run.
	 */
	public function testUnitTestFileNamesEndWithTest() {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test does not work on Windows' );
		}
		$rootPath = escapeshellarg( __DIR__ );
		$testClassRegex = implode( '|', array(
			'ApiFormatTestBase',
			'ApiTestCase',
			'MediaWikiLangTestCase',
			'MediaWikiTestCase',
			'PHPUnit_Framework_TestCase',
		) );
		$testClassRegex = "^class .* extends ($testClassRegex)";
		$finder = "find $rootPath -name '*.php' '!' -name '*Test.php'" .
			" | xargs grep -El '$testClassRegex|function suite\('";

		$results = null;
		$exitCode = null;
		exec($finder, $results, $exitCode);

		$this->assertEquals(
			0,
			$exitCode,
			'Verify find/grep command succeeds.'
		);

		$results = array_filter(
			$results,
			array( $this, 'filterSuites' )
		);

		$this->assertEquals(
			array(),
			$results,
			'Unit test file names must end with Test.'
		);
	}

	/**
	 * Filter to remove testUnitTestFileNamesEndWithTest false positives.
	 */
	public function filterSuites( $filename ) {
		return strpos( $filename, __DIR__ . '/suites/' ) !== 0;
	}
}
