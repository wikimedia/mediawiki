<?php

/**
 * Test class for SpecialShortpages class
 *
 * @since 1.30
 *
 * @license GNU GPL v2+
 */
class SpecialShortpagesTest extends MediaWikiTestCase {

	/**
	 * @dataProvider provideGetQueryInfoRespectsContentNs
	 * @covers ShortPagesPage::getQueryInfo()
	 */
	public function testGetQueryInfoRespectsContentNS( $contentNS, $blacklistNS, $expectedNS ) {
		$this->setMwGlobals( [
			'wgShortPagesNamespaceBlacklist' => $blacklistNS,
			'wgContentNamespaces' => $contentNS
		] );
		$this->setTemporaryHook( 'ShortPagesQuery', function () {
			// empty hook handler
		} );

		$page = new ShortPagesPage();
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
