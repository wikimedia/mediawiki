<?php

use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\StaticHookRegistry;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;

/**
 * @group Cache
 */
class HtmlCacheUpdaterIntegrationTest extends MediaWikiIntegrationTestCase {

	/**
	 * @return HtmlCacheUpdater
	 * @throws Exception
	 */
	private function newHtmlCacheUpdater(): HtmlCacheUpdater {
		$updater = new HtmlCacheUpdater(
			new HookContainer(
				new StaticHookRegistry(),
				$this->getServiceContainer()->getObjectFactory()
			),
			$this->getServiceContainer()->getTitleFactory(),
			1,
			false,
			3
		);
		return $updater;
	}

	private function getEventRelayGroup( array $expected ) {
		if ( !$expected ) {
			$relayer = $this->createNoOpMock( EventRelayer::class );
		} else {
			$relayer = $this->getMockBuilder( EventRelayer::class )
				->disableOriginalConstructor()
				->onlyMethods( [ 'doNotify' ] )
				->getMock();

			$relayer->method( 'doNotify' )->willReturnCallback(
				function ( $channel, array $events ) use ( $expected ) {
					$this->assertSame( 'cdn-url-purges', $channel );

					$this->assertSameSize( $expected, $events );
					foreach ( $expected as $i => $url ) {
						$event = $events[$i];
						$this->assertStringContainsString( $url, $event['url'] );
					}
				}
			);
		}

		$group = $this->createNoOpMock( EventRelayerGroup::class, [ 'getRelayer' ] );
		$group->method( 'getRelayer' )->willReturn( $relayer );

		return $group;
	}

	public function providePurgeTitleUrls() {
		yield [ [], [] ];

		yield [
			new PageReferenceValue( NS_MAIN, 'Test', PageReference::LOCAL ),
			[ 'Test', '?title=Test&action=history' ]
		];

		yield [
			[
				new PageReferenceValue( NS_MAIN, 'Test1', PageReference::LOCAL ),
				new PageReferenceValue( NS_MAIN, 'Test2', PageReference::LOCAL ),
				new PageReferenceValue( NS_SPECIAL, 'Nope', PageReference::LOCAL ),
				Title::makeTitle( NS_MAIN, '', 'Nope' ),
				Title::makeTitle( NS_MAIN, 'Foo', '', 'nope' ),
			],
			[
				'Test1', '?title=Test1&action=history',
				'Test2', '?title=Test2&action=history'
			]
		];

		yield [
			new TitleArrayFromResult( new FakeResultWrapper( [
				(object)[
					'page_id' => 1,
					'page_namespace' => NS_MAIN,
					'page_title' => 'Test',
				]
			] ) ),
			[ 'Test', '?title=Test&action=history' ]
		];
	}

	/**
	 * @dataProvider providePurgeTitleUrls
	 * @covers HtmlCacheUpdater::purgeTitleUrls
	 */
	public function testPurgeTitleUrls( $pages, $expected ) {
		$this->setService( 'EventRelayerGroup', $this->getEventRelayGroup( $expected ) );

		$updater = $this->newHtmlCacheUpdater();
		$updater->purgeTitleUrls( $pages );
	}

	/**
	 * @covers HtmlCacheUpdater::purgeUrls
	 */
	public function testPurgeUrls() {
		$urls = [ 'https://acme.test/wiki/Foo', 'https://acme.test/wiki/Bar', ];
		$this->setService( 'EventRelayerGroup', $this->getEventRelayGroup( $urls ) );

		$updater = $this->newHtmlCacheUpdater();
		$updater->purgeUrls( $urls );
	}

}
