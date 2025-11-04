<?php

use MediaWiki\Cache\BacklinkCacheFactory;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Page\PageReferenceValue;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @group Cache
 */
class BacklinkCacheFactoryTest extends MediaWikiUnitTestCase {

	/**
	 * @covers \MediaWiki\Cache\BacklinkCacheFactory::getBacklinkCache
	 */
	public function testGetBacklinkCache() {
		$wanCache = new WANObjectCache( [ 'cache' => new EmptyBagOStuff() ] );
		$dbProvider = $this->createMock( IConnectionProvider::class );
		$page = PageReferenceValue::localReference( NS_CATEGORY, "kittens" );
		$factory = new BacklinkCacheFactory(
			$this->createMock( ServiceOptions::class ),
			$this->createMock( LinksMigration::class ),
			$wanCache,
			$this->createHookContainer(),
			$dbProvider,
			LoggerFactory::getInstance( 'BacklinkCache' )
		);
		$cache = $factory->getBacklinkCache( $page );
		$this->assertTrue( $cache->getPage()->isSamePageAs( $page ) );

		$cache2 = $factory->getBacklinkCache( $page );
		$this->assertSame( $cache, $cache2 );

		$page2 = PageReferenceValue::localReference( NS_CATEGORY, "doggos" );
		$cache2 = $factory->getBacklinkCache( $page2 );
		$this->assertNotSame( $cache, $cache2 );
		$this->assertTrue( $cache2->getPage()->isSamePageAs( $page2 ) );
	}

}
