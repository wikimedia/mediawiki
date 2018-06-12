<?php
/**
 * This test suite runs unit tests registered by extensions.
 * See https://www.mediawiki.org/wiki/Manual:Hooks/UnitTestsList for details of
 * how to register your tests.
 */

class ExtensionsTestSuite extends PHPUnit_Framework_TestSuite {
	public function __construct() {
		parent::__construct();

		$paths = [];
		// Autodiscover extension unit tests
		$registry = ExtensionRegistry::getInstance();
		foreach ( $registry->getAllThings() as $info ) {
			$paths[] = dirname( $info['path'] ) . '/tests/phpunit';
		}
		// Extensions can return a list of files or directories
		Hooks::run( 'UnitTestsList', [ &$paths ] );
		foreach ( array_unique( $paths ) as $path ) {
			if ( is_dir( $path ) ) {
				// If the path is a directory, search for test cases.
				// @since 1.24
				$suffixes = [ 'Test.php' ];
				$fileIterator = new File_Iterator_Facade();
				$matchingFiles = $fileIterator->getFilesAsArray( $path, $suffixes );
				$this->addTestFiles( $matchingFiles );
			} elseif ( file_exists( $path ) ) {
				// Add a single test case or suite class
				$this->addTestFile( $path );
			}
		}
		if ( !$paths ) {
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
	/**
	 * @coversNothing
	 */
	public function testNothing() {
		$this->assertTrue( true );
	}
}
