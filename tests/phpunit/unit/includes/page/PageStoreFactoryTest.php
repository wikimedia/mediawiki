<?php
namespace MediaWiki\Tests\Page;

use MediaWiki\Cache\LinkCache;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageStore;
use MediaWiki\Page\PageStoreFactory;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\TitleParser;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Stats\StatsFactory;

/**
 * @covers \MediaWiki\Page\PageStoreFactory
 */
class PageStoreFactoryTest extends MediaWikiUnitTestCase {

	public function testGetPageStore() {
		$options = new ServiceOptions( PageStoreFactory::CONSTRUCTOR_OPTIONS, [
			MainConfigNames::LanguageCode => 'fi',
			MainConfigNames::PageLanguageUseDB => true,
		] );

		$lb = $this->createNoOpMock( LoadBalancer::class );

		$lbFactory = $this->createNoOpMock( LBFactory::class, [ 'getMainLB' ] );
		$lbFactory->method( 'getMainLB' )->willReturn( $lb );

		$factory = new PageStoreFactory(
			$options,
			$lbFactory,
			$this->createNoOpMock( NamespaceInfo::class ),
			$this->createNoOpMock( TitleParser::class ),
			$this->createNoOpMock( LinkCache::class ),
			$this->createNoOpMock( StatsFactory::class )
		);

		// Just check that nothing explodes.
		$this->assertInstanceOf( PageStore::class, $factory->getPageStore() );
	}

}
