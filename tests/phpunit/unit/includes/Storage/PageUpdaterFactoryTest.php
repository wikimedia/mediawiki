<?php

namespace MediaWiki\Tests\Storage;

use JobQueueGroup;
use Language;
use LoadBalancer;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\ContentHandlerFactory;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRoleRegistry;
use MediaWiki\Storage\DerivedPageDataUpdater;
use MediaWiki\Storage\EditResultCache;
use MediaWiki\Storage\PageUpdater;
use MediaWiki\Storage\PageUpdaterFactory;
use MediaWiki\User\UserEditTracker;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserNameUtils;
use MediaWikiUnitTestCase;
use MessageCache;
use ParserCache;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\LBFactory;
use WikiPage;

/**
 * @covers MediaWiki\Storage\PageUpdaterFactory
 * @group Database
 */
class PageUpdaterFactoryTest extends MediaWikiUnitTestCase {
	private function getPageUpdaterFactory() {
		$config = [
			'ArticleCountMethod' => null,
			'RCWatchCategoryMembership' => null,
			'PageCreationLog' => null,
			'AjaxEditStash' => null,
			'UseAutomaticEditSummaries' => null,
			'ManualRevertSearchRadius' => null,
			'UseRCPatrol' => null,
		];

		$lb = $this->createNoOpMock( LoadBalancer::class );
		$lbFactory = $this->createNoOpMock( LBFactory::class, [ 'getMainLB' ] );
		$lbFactory->method( 'getMainLB' )->willReturn( $lb );

		return new PageUpdaterFactory(
			$this->createNoOpMock( RevisionStore::class ),
			$this->createNoOpMock( RevisionRenderer::class ),
			$this->createNoOpMock( SlotRoleRegistry::class ),
			$this->createNoOpMock( ParserCache::class ),
			$this->createNoOpMock( JobQueueGroup::class ),
			$this->createNoOpMock( MessageCache::class ),
			$this->createNoOpMock( Language::class ),
			$lbFactory,
			$this->createNoOpMock( ContentHandlerFactory::class ),
			$this->createNoOpMock( HookContainer::class ),
			$this->createNoOpMock( EditResultCache::class ),
			$this->createNoOpMock( UserNameUtils::class ),
			$this->createNoOpMock( LoggerInterface::class ),
			new ServiceOptions(
				PageUpdaterFactory::CONSTRUCTOR_OPTIONS,
				$config
			),
			$this->createNoOpMock( UserEditTracker::class ),
			$this->createNoOpMock( UserGroupManager::class ),
			[]
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
