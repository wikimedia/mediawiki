<?php
/**
 * This test suite runs unit tests registered by extensions.
 * See http://www.mediawiki.org/wiki/Manual:Hooks/UnitTestsList for details of how to register your tests.
 */

class ExtensionsTestSuite extends PHPUnit_Framework_TestSuite {
	public function __construct() {
		parent::__construct();
		$files = array();
		wfRunHooks( 'UnitTestsList', array( &$files ) );
		foreach ( $files as $file ) {
			$this->addTestFile( $file );
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
