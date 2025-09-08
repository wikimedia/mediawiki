<?php

use PHPUnit\Framework\TestSuite;
use SebastianBergmann\FileIterator\Facade;

/**
 * Test suite that runs extensions unit tests (the `extensions:unit` suite).
 */
class ExtensionsUnitTestSuite extends TestSuite {
	public function __construct() {
		parent::__construct();

		if ( !defined( 'MW_PHPUNIT_EXTENSIONS_PATHS' ) ) {
			throw new RuntimeException( 'The PHPUnit bootstrap was not loaded' );
		}

		$suffixes = [ 'Test.php' ];
		$fileIterator = new Facade();
		foreach ( MW_PHPUNIT_EXTENSIONS_PATHS as $path ) {
			$this->addTestFiles( $fileIterator->getFilesAsArray( "$path/tests/phpunit/unit", $suffixes ) );
		}
	}

	public static function suite() {
		return new self;
	}
}
