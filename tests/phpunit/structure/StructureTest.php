<?php

use SebastianBergmann\FileIterator\Facade;

/**
 * The tests here verify the structure of the code.  This is for outright bugs,
 * not just style issues.
 * @coversNothing
 */
class StructureTest extends \PHPUnit\Framework\TestCase {
	private const FOLDER_TO_CHECK = [
		'tests/phpunit/includes',
		'tests/phpunit/integration/includes',
		'tests/phpunit/unit/includes',
		'tests/phpunit/mocks',
		'tests/common/',
	];

	/**
	 * Verify all files that appear to be tests have file names ending in
	 * Test.  If the file names do not end in Test, they will not be run.
	 * @group medium
	 */
	public function testUnitTestFileNamesEndWithTest() {
		// realpath() also normalizes directory separator on windows for prefix compares
		$rootPath = realpath( __DIR__ . '/..' );
		$suitesPath = realpath( __DIR__ . '/../suites/' );
		$testClassRegex = '/^(final )?class .* extends [\S]*(TestCase|TestBase)\\b/m';

		$results = $this->recurseFiles( $rootPath );

		$results = array_filter(
			$results,
			static function ( $filename ) use ( $testClassRegex, $suitesPath ) {
				// Remove testUnitTestFileNamesEndWithTest false positives
				if ( str_starts_with( $filename, $suitesPath ) ||
					str_ends_with( $filename, 'Test.php' )
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

		// Normalize indexes to make failure output less confusing
		$results = array_values( $results );

		$this->assertEquals(
			[],
			$results,
			"Unit test file in $rootPath must end with Test."
		);
	}

	/**
	 * See T398513
	 * See AutoloaderTest::testCapitaliseFolder for the real classes.
	 */
	public function testCapitaliseFolder() {
		global $IP;

		$error = [];
		$rootLen = strlen( $IP ) + 1;
		foreach ( self::FOLDER_TO_CHECK as $checkFolder ) {
			$checkPath = $IP . '/' . $checkFolder;
			$this->assertDirectoryExists( $checkPath );
			$testFiles = $this->recurseFiles( $checkPath );

			$checkFolderLen = strlen( $checkFolder );
			foreach ( $testFiles as $testFile ) {
				$testFile = strtr( $testFile, [ '\\' => '/' ] );
				if ( preg_match( '#/(data|fixtures|bin)/#', $testFile ) ) {
					continue;
				}

				$slash = strrpos( $testFile, '/' );
				$filename = substr( $testFile, $slash + 1 );
				$testPath = substr( $testFile, $rootLen, $slash - $rootLen );
				if ( preg_match( '#/(?!libs)[^A-Z]#', substr( $testPath, $checkFolderLen ) ) ) {
					$error[$filename] = $testPath;
				}
			}
		}
		$this->assertSame( [], $error, 'All folder in /includes/ with php classes must start with upper case' );
	}

	private function recurseFiles( $dir ) {
		return ( new Facade() )->getFilesAsArray( $dir, [ '.php' ] );
	}
}
