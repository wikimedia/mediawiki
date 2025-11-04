<?php

use MediaWiki\Cache\HTMLCacheUpdater;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group Cache
 * @covers \MediaWiki\Cache\HTMLCacheUpdater
 */
class HTMLCacheUpdaterTest extends MediaWikiUnitTestCase {

	public function testGetCdnUrls() {
		$htmlCache = new HTMLCacheUpdater(
			$this->createHookContainer(),
			$this->createTitleFactory(),
			0, false, 86400 );
		$title = $this->createMock( Title::class );
		$title->method( 'canExist' )->willReturn( true );
		$title->method( 'getInternalURL' )->willReturnCallback( static function ( $query = '' ) {
			return 'https://test/?title=Example' . ( $query !== '' ? "&$query" : '' );
		} );

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
		$title->method( 'canExist' )->willReturn( true );
		$title->method( 'getInternalURL' )->willReturnCallback( static function ( $query = '' ) {
			return 'https://test/?title=User:Example/foo.js' . ( $query !== '' ? "&$query" : '' );
		} );
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

		$title = $this->createMock( Title::class );
		$title->method( 'canExist' )->willReturn( true );
		$title->method( 'getInternalURL' )->willReturnCallback( static function ( $query = '' ) {
			return 'https://test/?title=MediaWiki:Example.js' . ( $query !== '' ? "&$query" : '' );
		} );
		$title->method( 'isSiteJsConfigPage' )->willReturn( true );
		$this->assertEquals(
			[
				'https://test/?title=MediaWiki:Example.js',
				'https://test/?title=MediaWiki:Example.js&action=history',
				'https://test/?title=MediaWiki:Example.js&action=raw&ctype=text/javascript',
			],
			$htmlCache->getUrls( $title ),
			'all urls for a site js page'
		);
	}

	/**
	 * @return MockObject|TitleFactory
	 */
	private function createTitleFactory() {
		$factory = $this->createNoOpMock( TitleFactory::class, [ 'newFromPageReference' ] );

		$factory->method( 'newFromPageReference' )->willReturnArgument( 0 );

		return $factory;
	}
}
