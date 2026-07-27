<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\MainConfigNames;
use MediaWiki\Specials\SpecialShortPages;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Specials\SpecialShortPages
 * @license GPL-2.0-or-later
 */
class SpecialShortPagesTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideGetQueryInfoRespectsContentNs
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
			$services->getConnectionProvider(),
			$services->getLinkBatchFactory()
		);
		$queryInfo = $page->getQueryInfo();

		$this->assertArrayHasKey( 'conds', $queryInfo );
		$this->assertArrayHasKey( 'page_namespace', $queryInfo[ 'conds' ] );
		$this->assertEquals( $expectedNS, $queryInfo[ 'conds' ][ 'page_namespace' ] );
	}

	public function testGetQueryInfoExcludesExpectedShortPages() {
		$this->setTemporaryHook( 'ShortPagesQuery', static function () {
			// empty hook handler
		} );
		$services = $this->getServiceContainer();
		$page = new SpecialShortPages(
			$services->getNamespaceInfo(),
			$services->getConnectionProvider(),
			$services->getLinkBatchFactory()
		);
		$queryInfo = $page->getQueryInfo();

		$this->assertContains( 'page_props', $queryInfo[ 'tables' ] );
		$this->assertArrayHasKey( 'page_props.pp_page', $queryInfo[ 'conds' ] );
		$this->assertNull( $queryInfo[ 'conds' ][ 'page_props.pp_page' ] );
		$this->assertSame(
			[
				'LEFT JOIN',
				[
					'page.page_id = page_props.pp_page',
					'page_props.pp_propname' => 'expectshortpage',
				],
			],
			$queryInfo[ 'join_conds' ][ 'page_props' ]
		);
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
