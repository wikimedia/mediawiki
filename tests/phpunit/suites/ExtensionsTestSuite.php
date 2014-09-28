<?php
/**
 * This test suite runs unit tests registered by extensions.
 * See https://www.mediawiki.org/wiki/Manual:Hooks/UnitTestsList for details of
 * how to register your tests.
 */

class ExtensionsTestSuite extends PHPUnit_Framework_TestSuite {
	public function __construct() {
		parent::__construct();
		$files = array();
		// Extensions can return a list of files or directories
		wfRunHooks( 'UnitTestsList', array( &$files ) );
		foreach ( $files as $path ) {
			if ( is_dir( $path ) ) {
				// If the path is a directory, search for test cases.
				// @since 1.24
				$suffixes = array(
					'Test.php',
					'TestCase.php',
				);
				$fileIterator = new File_Iterator_Facade();
				$files = $fileIterator->getFilesAsArray( $path, $suffixes );
				$this->addTestFiles( $files );
			} else {
				// Add a single test case or suite class
				$this->addTestFile( $path );
			}
		}
		if ( !count( $files ) ) {
			$this->addTest( new DummyExtensionsTest( 'testNothing' ) );
		}
	}

	public static function suite() {
		return new self;
	}
}

/**
 * Needed to avoid warnings like 'No tests found in class "ExtensionsTestSuite".'
 * when no extensions with tests are used.
 */
class DummyExtensionsTest extends MediaWikiTestCase {
	public function testNothing() {
		$this->assertTrue( true );
	}
}
