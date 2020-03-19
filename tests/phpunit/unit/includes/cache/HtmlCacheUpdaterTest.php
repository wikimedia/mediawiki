<?php

/**
 * @group Cache
 * @covers HtmlCacheUpdater
 */
class HtmlCacheUpdaterTest extends MediaWikiUnitTestCase {

	public function testGetCdnUrls() {
		$htmlCache = new HtmlCacheUpdater(
			$this->createHookContainer(),
			0, false, 86400 );
		$title = $this->createMock( Title::class );
		$title->method( 'getInternalURL' )->will( $this->returnCallback( function ( $query = '' ) {
			return 'https://test/?title=Example' . ( $query !== '' ? "&$query" : '' );
		} ) );

		$this->assertEquals(
			[
				'https://test/?title=Example',
				'https://test/?title=Example&action=history',
			],
			$htmlCache->getUrls( $title ),
			'all urls for an article'
		);
		$this->assertEquals(
			[
				'https://test/?title=Example',
			],
			$htmlCache->getUrls( $title, $htmlCache::PURGE_URLS_LINKSUPDATE_ONLY ),
			'linkupdate urls for an article'
		);

		$title = $this->createMock( Title::class );
		$title->method( 'getInternalURL' )->will( $this->returnCallback( function ( $query = '' ) {
			return 'https://test/?title=User:Example/foo.js' . ( $query !== '' ? "&$query" : '' );
		} ) );
		$title->method( 'isUserJsConfigPage' )->willReturn( true );
		$this->assertEquals(
			[
				'https://test/?title=User:Example/foo.js',
				'https://test/?title=User:Example/foo.js&action=history',
				'https://test/?title=User:Example/foo.js&action=raw&ctype=text/javascript',
			],
			$htmlCache->getUrls( $title ),
			'all urls for a user js page'
		);
	}
}
