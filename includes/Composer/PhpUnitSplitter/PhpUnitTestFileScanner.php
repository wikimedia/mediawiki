<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * @license GPL-2.0-or-later
 */
class PhpUnitTestFileScanner {

	private string $rootDir;

	public function __construct( string $rootDir ) {
		$this->rootDir = $rootDir;
	}

	/**
	 * @return array Returns an list of `.php` files found on the filesystem
	 * inside `$rootDir`. The array maps file basenames (i.e.
	 * `MyClassTest.php`) to lists of paths where that basename
	 * is found (e.g. `[ 'tests/phpunit/MyClassTest.php',
	 * 'extensions/MyExtension/tests/phpunit/MyClassTest.php' ]`)
	 */
	public function scanForFiles(): array {
		$phpFiles = [];
		$iterator = new RecursiveIteratorIterator(
			new PhpUnitTestFileScannerFilter(
				new RecursiveDirectoryIterator( $this->rootDir )
			)
		);
		foreach ( $iterator as $file ) {
			if ( $file->isFile() && $file->getExtension() === 'php' ) {
				$filename = $file->getFilename();
				if ( !array_key_exists( $filename, $phpFiles ) ) {
					$phpFiles[$filename] = [];
				}
				$phpFiles[$filename][] = $file->getPathname();
			}
		}
		return $phpFiles;
	}
}
