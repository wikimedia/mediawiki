<?php

use MediaWiki\MediaWikiServices;

/**
 * Test that runs against all registered special pages to make sure that regular
 * execution of the special page does not cause a fatal error.
 *
 * UTSysop is used to run as much of the special page code as possible without
 * actually knowing the details of the special page.
 *
 * @since 1.32
 * @author Addshore
 */
class SpecialPageFatalTest extends MediaWikiIntegrationTestCase {

	protected function setUp() : void {
		parent::setUp();
		// FIXME: Acknowledge known non-fatal query (T248191)
		$this->setMwGlobals( 'wgDBerrorLog', false );
		// Deprecations don't matter for what this test cares about. This made browser tests fail
		// on many occasions already. (T236809)
		$this->filterDeprecated( '//' );
	}

	public function provideSpecialPages() {
		$specialPages = [];
		$spf = MediaWikiServices::getInstance()->getSpecialPageFactory();
		foreach ( $spf->getNames() as $name ) {
			$specialPages[$name] = [ $spf->getPage( $name ) ];
		}
		return $specialPages;
	}

	/**
	 * @dataProvider provideSpecialPages
	 */
	public function testSpecialPageDoesNotFatal( SpecialPage $page ) {
		$executor = new SpecialPageExecutor();
		$user = User::newFromName( 'UTSysop' );

		try {
			$executor->executeSpecialPage( $page, '', null, 'qqx', $user );
		} catch ( \PHPUnit\Framework\Error\Deprecated $deprecated ) {
			// Allow deprecation,
			// this test want to check fatals or other things breaking the extension
		} catch ( \PHPUnit\Framework\Error\Error $error ) {
			// Let phpunit settings working:
			// - convertErrorsToExceptions="true"
			// - convertNoticesToExceptions="true"
			// - convertWarningsToExceptions="true"
			throw $error;
		} catch ( Exception $e ) {
			// Other exceptions are allowed
		}

		// If the page fataled phpunit will have already died
		$this->addToAssertionCount( 1 );
	}

}
