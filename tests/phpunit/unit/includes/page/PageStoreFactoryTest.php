<?php
namespace MediaWiki\Tests\Page;

use LinkCache;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageStore;
use MediaWiki\Page\PageStoreFactory;
use MediaWikiUnitTestCase;
use NamespaceInfo;
use TitleParser;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\LoadBalancer;

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
			$this->createNoOpMock( StatsdDataFactoryInterface::class )
		);

		// Just check that nothing explodes.
		$this->assertInstanceOf( PageStore::class, $factory->getPageStore() );
	}

}
