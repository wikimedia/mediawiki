<?php

/**
 * Test class for SpecialShortPages class
 *
 * @since 1.30
 *
 * @license GPL-2.0-or-later
 */
class SpecialShortPagesTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideGetQueryInfoRespectsContentNs
	 * @covers SpecialShortPages::getQueryInfo()
	 */
	public function testGetQueryInfoRespectsContentNS( $contentNS, $blacklistNS, $expectedNS ) {
		$this->setMwGlobals( [
			'wgShortPagesNamespaceBlacklist' => $blacklistNS,
			'wgContentNamespaces' => $contentNS
		] );
		$this->setTemporaryHook( 'ShortPagesQuery', function () {
			// empty hook handler
		} );

		$page = new SpecialShortPages();
		$queryInfo = $page->getQueryInfo();

		$this->assertArrayHasKey( 'conds', $queryInfo );
		$this->assertArrayHasKey( 'page_namespace', $queryInfo[ 'conds' ] );
		$this->assertEquals( $expectedNS, $queryInfo[ 'conds' ][ 'page_namespace' ] );
	}

	public function provideGetQueryInfoRespectsContentNs() {
		return [
			[ [ NS_MAIN, NS_FILE ], [], [ NS_MAIN, NS_FILE ] ],
			[ [ NS_MAIN, NS_TALK ], [ NS_FILE ], [ NS_MAIN, NS_TALK ] ],
			[ [ NS_MAIN, NS_FILE ], [ NS_FILE ], [ NS_MAIN ] ],
			// NS_MAIN namespace is always forced
			[ [], [ NS_FILE ], [ NS_MAIN ] ]
		];
	}

}
