<?php

/**
 * The tests here verify that phpunit/suite.xml covers all of the tests under /tests/phpunit
 * @group medium
 * @coversNothing
 */
class PHPUnitConfigTest extends PHPUnit\Framework\TestCase {

	/**
	 * @dataProvider provideConfigFiles
	 */
	public function testConfigDirectories( string $configPath ) {
		// realpath() also normalizes directory separator on windows for prefix compares
		$testRootDir = realpath( __DIR__ . '/..' );

		$dom = new DOMDocument();
		$dom->load( $configPath );
		/** @var DOMElement $suites */
		$suites = $dom->documentElement->getElementsByTagName( 'testsuites' )[0];

		$suiteInfos = [];
		/** @var DOMElement $suite */
		foreach ( $suites->getElementsByTagName( 'testsuite' ) as $suite ) {
			$generalDirs = [];
			foreach ( $suite->getElementsByTagName( 'directory' ) as $dirNode ) {
				$generalDirs[] = str_replace( 'tests/phpunit/', '', $dirNode->textContent );
			}
			$excludedDirs = [];
			foreach ( $suite->getElementsByTagName( 'exclude' ) as $dirNode ) {
				$excludedDirs[] = str_replace( 'tests/phpunit/', '', $dirNode->textContent );
			}
			$suiteInfos[$suite->getAttribute( 'name' )] = [ $generalDirs, $excludedDirs ];
		}

		$directoriesFound = scandir( $testRootDir, SCANDIR_SORT_ASCENDING );
		if ( !$directoriesFound ) {
			$this->fail( "Could not scan '$testRootDir' directory" );
		}

		$directoriesNeeded = array_values( array_diff(
			array_filter(
				$directoriesFound,
				static function ( $name ) use ( $testRootDir ) {
					return (
						$name !== '.' &&
						$name !== '..' &&
						is_dir( "$testRootDir/$name" )
					);
				}
			),
			[
				'data',
				'docs',
				'documentation',
				'mocks',
				'suites' // custom suite entry points load their own files
			]
		) );

		$directoriesIncluded = [];
		foreach ( $directoriesNeeded as $directory ) {
			if ( $this->isDirectoryIncluded( $directory, $suiteInfos ) ) {
				$directoriesIncluded[] = $directory;
			}
		}

		$this->assertSame(
			$directoriesNeeded,
			$directoriesIncluded,
			"All suites included"
		);
	}

	public static function provideConfigFiles(): array {
		return [
			'suite.xml' => [ __DIR__ . '/../suite.xml' ],
			'phpunit.xml.dist' => [ __DIR__ . '/../../../phpunit.xml.dist' ],
		];
	}

	private function isDirectoryIncluded( $dir, array $suiteInfos ) {
		foreach ( $suiteInfos as [ $generalDirs, $excludedDirs ] ) {
			$found = false;
			foreach ( $generalDirs as $generalDir ) {
				if ( $this->isSameOrChildOfDirectory( $dir, $generalDir ) ) {
					$found = true;
					break;
				}
			}
			foreach ( $excludedDirs as $excludedDir ) {
				if ( $this->isSameOrChildOfDirectory( $dir, $excludedDir ) ) {
					$found = false;
					break;
				}
			}
			if ( $found ) {
				return true;
			}
		}

		return false;
	}

	private function isSameOrChildOfDirectory( $dirA, $dirB ) {
		if ( $dirA === $dirB ) {
			return true;
		}

		if ( strpos( "$dirB/", $dirA ) === 0 ) {
			return true;
		}

		return false;
	}
}
