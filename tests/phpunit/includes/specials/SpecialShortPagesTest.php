<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Specials\SpecialShortPages;

/**
 * Test class for SpecialShortPages class
 *
 * @since 1.30
 *
 * @license GPL-2.0-or-later
 */
class SpecialShortPagesTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideGetQueryInfoRespectsContentNs
	 * @covers \MediaWiki\Specials\SpecialShortPages::getQueryInfo
	 */
	public function testGetQueryInfoRespectsContentNS( $contentNS, $blacklistNS, $expectedNS ) {
		$this->overrideConfigValues( [
			MainConfigNames::ShortPagesNamespaceExclusions => $blacklistNS,
			MainConfigNames::ContentNamespaces => $contentNS
		] );
		$this->setTemporaryHook( 'ShortPagesQuery', static function () {
			// empty hook handler
		} );

		$services = $this->getServiceContainer();
		$page = new SpecialShortPages(
			$services->getNamespaceInfo(),
			$services->getDBLoadBalancerFactory(),
			$services->getLinkBatchFactory()
		);
		$queryInfo = $page->getQueryInfo();

		$this->assertArrayHasKey( 'conds', $queryInfo );
		$this->assertArrayHasKey( 'page_namespace', $queryInfo[ 'conds' ] );
		$this->assertEquals( $expectedNS, $queryInfo[ 'conds' ][ 'page_namespace' ] );
	}

	public static function provideGetQueryInfoRespectsContentNs() {
		return [
			[ [ NS_MAIN, NS_FILE ], [], [ NS_MAIN, NS_FILE ] ],
			[ [ NS_MAIN, NS_TALK ], [ NS_FILE ], [ NS_MAIN, NS_TALK ] ],
			[ [ NS_MAIN, NS_FILE ], [ NS_FILE ], [ NS_MAIN ] ],
			// NS_MAIN namespace is always forced
			[ [], [ NS_FILE ], [ NS_MAIN ] ]
		];
	}

}
