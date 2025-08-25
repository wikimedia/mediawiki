<?php

use MediaWiki\RecentChanges\CategoryMembershipChange;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\RecentChanges\RecentChangeStore;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;

/**
 * @covers \MediaWiki\RecentChanges\CategoryMembershipChange
 *
 * @group Database
 *
 * @author Addshore
 */
class CategoryMembershipChangeTest extends MediaWikiLangTestCase {

	/**
	 * @var RevisionRecord
	 */
	private static $pageRev = null;

	/**
	 * @var UserIdentity
	 */
	private static $revUser = null;

	/**
	 * @var string
	 */
	private static $pageName = 'CategoryMembershipChangeTestPage';

	protected function setUp(): void {
		parent::setUp();

		$this->setContentLang( 'qqx' );
	}

	public function addDBDataOnce() {
		$info = $this->insertPage( self::$pageName );
		$title = $info['title'];

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		self::$pageRev = $page->getRevisionRecord();
		self::$revUser = self::$pageRev->getUser( RevisionRecord::RAW );
	}

	private function newChange( RevisionRecord $revision, string $message ): CategoryMembershipChange {
		$title = Title::makeTitle( NS_MAIN, self::$pageName );
		$blcFactory = $this->getServiceContainer()->getBacklinkCacheFactory();

		$mockRecentChangeStore = $this->createMock( RecentChangeStore::class );
		$mockRecentChangeStore
			->expects( $this->once() )
			->method( 'createCategorizationRecentChange' )
			->with(
				$this->callback( static function ( $timestamp ) {
					return ctype_digit( $timestamp ) && strlen( $timestamp ) === 14;
				} ),
				'Category:CategoryName',
				self::$revUser->getName(),
				'(' . $message . ': ' . self::$pageName . ')',
				self::$pageName,
				self::$pageRev->getParentId(),
				$revision->getId(),
				0,
				'127.0.0.1',
				0
			)
			->willReturn( $this->createMock( RecentChange::class ) );
		$mockRecentChangeStore
			->expects( $this->once() )
			->method( 'insertRecentChange' )
			->willReturn( true );

		$change = new CategoryMembershipChange(
			$title,
			$blcFactory->getBacklinkCache( $title ),
			$revision,
			$mockRecentChangeStore,
			false
		);

		return $change;
	}

	public function testChangeAddedWithRev() {
		$revision = $this->getServiceContainer()
			->getRevisionLookup()
			->getRevisionByTitle( Title::makeTitle( NS_MAIN, self::$pageName ) );
		$change = $this->newChange( $revision, 'recentchanges-page-added-to-category' );
		$change->triggerCategoryAddedNotification( Title::makeTitle( NS_CATEGORY, 'CategoryName' ) );
	}

	public function testChangeRemovedWithRev() {
		$revision = $this->getServiceContainer()
			->getRevisionLookup()
			->getRevisionByTitle( Title::makeTitle( NS_MAIN, self::$pageName ) );
		$change = $this->newChange( $revision, 'recentchanges-page-removed-from-category' );
		$change->triggerCategoryRemovedNotification( Title::makeTitle( NS_CATEGORY, 'CategoryName' ) );
	}
}
