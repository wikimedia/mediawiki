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
class SpecialPageFatalTest extends MediaWikiTestCase {
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
			$executor->executeSpecialPage( $page, '', null, null, $user );
		} catch ( Exception $e ) {
			// Exceptions are allowed
		}

		// If the page fataled phpunit will have already died
		$this->addToAssertionCount( 1 );
	}

}
