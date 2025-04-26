<?php

namespace MediaWiki\Tests\Unit\Storage;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\WikiPage;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Storage\DerivedPageDataUpdater;
use MediaWiki\Storage\PageUpdater;
use MediaWiki\Storage\PageUpdaterFactory;
use MediaWiki\Tests\Unit\MockServiceDependenciesTrait;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use Wikimedia\Rdbms\LBFactory;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * @covers \MediaWiki\Storage\PageUpdaterFactory
 */
class PageUpdaterFactoryTest extends MediaWikiUnitTestCase {
	use MockServiceDependenciesTrait;

	private function getPageUpdaterFactory() {
		$config = [
			MainConfigNames::ArticleCountMethod => null,
			MainConfigNames::RCWatchCategoryMembership => null,
			MainConfigNames::PageCreationLog => null,
			MainConfigNames::UseAutomaticEditSummaries => null,
			MainConfigNames::ManualRevertSearchRadius => null,
			MainConfigNames::UseRCPatrol => null,
			MainConfigNames::ParsoidCacheConfig => [
				'WarmParsoidParserCache' => false
			],
		];

		$lb = $this->createNoOpMock( LoadBalancer::class );
		$lbFactory = $this->createNoOpMock( LBFactory::class, [ 'getMainLB' ] );
		$lbFactory->method( 'getMainLB' )->willReturn( $lb );

		$wikiPageFactory = $this->createNoOpMock( WikiPageFactory::class, [ 'newFromTitle' ] );
		$wikiPageFactory->method( 'newFromTitle' )->willReturnArgument( 0 );

		return $this->newServiceInstance(
			PageUpdaterFactory::class,
			[
				'loadbalancerFactory' => $lbFactory,
				'wikiPageFactory' => $wikiPageFactory,
				'options' => new ServiceOptions(
					PageUpdaterFactory::CONSTRUCTOR_OPTIONS,
					$config
				),
				'softwareTags' => [],
			]
		);
	}

	public function testNewDerivedPageDataUpdater() {
		$page = $this->createNoOpMock( WikiPage::class );

		$factory = $this->getPageUpdaterFactory();
		$derivedPageDataUpdater = $factory->newDerivedPageDataUpdater( $page );

		$this->assertInstanceOf( DerivedPageDataUpdater::class, $derivedPageDataUpdater );
	}

	public function testNewPageUpdater() {
		$page = $this->createNoOpMock( WikiPage::class, [ 'canExist' ] );
		$page->method( 'canExist' )->willReturn( true );

		$user = new UserIdentityValue( 0, 'Dummy' );

		$factory = $this->getPageUpdaterFactory();
		$pageUpdater = $factory->newPageUpdater( $page, $user );

		$this->assertInstanceOf( PageUpdater::class, $pageUpdater );
	}

	public function testNewPageUpdaterForDerivedPageDataUpdater() {
		$page = $this->createNoOpMock( WikiPage::class, [ 'canExist' ] );
		$page->method( 'canExist' )->willReturn( true );

		$user = new UserIdentityValue( 0, 'Dummy' );

		$factory = $this->getPageUpdaterFactory();
		$derivedPageDataUpdater = $factory->newDerivedPageDataUpdater( $page );
		$pageUpdater = $factory->newPageUpdaterForDerivedPageDataUpdater(
			$page,
			$user,
			$derivedPageDataUpdater
		);

		$this->assertInstanceOf( PageUpdater::class, $pageUpdater );
	}
}
