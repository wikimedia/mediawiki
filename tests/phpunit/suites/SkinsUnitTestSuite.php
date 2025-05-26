<?php

use PHPUnit\Framework\TestSuite;
use SebastianBergmann\FileIterator\Facade;

/**
 * Test suite that runs skins unit tests (the `skins:unit` suite).
 */
class SkinsUnitTestSuite extends TestSuite {
	public function __construct() {
		parent::__construct();

		if ( !defined( 'MW_PHPUNIT_EXTENSIONS_PATHS' ) ) {
			throw new RuntimeException( 'The PHPUnit bootstrap was not loaded' );
		}
		$paths = [];
		foreach ( MW_PHPUNIT_EXTENSIONS_PATHS as $path ) {
			// Note that we don't load settings, so we expect to find skins in their
			// default location
			// Standardize directory separators for Windows compatibility.
			if ( str_contains( strtr( $path, '\\', '/' ), '/skins/' ) ) {
				$paths["$path/tests/phpunit/unit"] = true;
			}
		}
		$suffixes = [ 'Test.php' ];
		$fileIterator = new Facade();
		foreach ( $paths as $path => $_ ) {
			$this->addTestFiles( $fileIterator->getFilesAsArray( $path, $suffixes ) );
		}
	}

	public static function suite() {
		return new self;
	}
}
