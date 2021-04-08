<?php
namespace MediaWiki\Tests\Page;

use LoadBalancer;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Page\PageStore;
use MediaWiki\Page\PageStoreFactory;
use MediaWikiUnitTestCase;
use NamespaceInfo;
use Wikimedia\Rdbms\LBFactory;

/**
 * @covers \MediaWiki\Page\PageStoreFactory
 */
class PageStoreFactoryTest extends MediaWikiUnitTestCase {

	public function testGetPageStore() {
		$options = new ServiceOptions( PageStoreFactory::CONSTRUCTOR_OPTIONS, [
			'LanguageCode' => 'fi',
			'PageLanguageUseDB' => true,
		] );

		$lb = $this->createNoOpMock( LoadBalancer::class );

		$lbFactory = $this->createNoOpMock( LBFactory::class, [ 'getMainLB' ] );
		$lbFactory->method( 'getMainLB' )->willReturn( $lb );

		$nsInfo = $this->createNoOpMock( NamespaceInfo::class );

		$factory = new PageStoreFactory(
			$options,
			$lbFactory,
			$nsInfo
		);

		// Just check that nothing explodes.
		$this->assertInstanceOf( PageStore::class, $factory->getPageStore() );
	}

}
