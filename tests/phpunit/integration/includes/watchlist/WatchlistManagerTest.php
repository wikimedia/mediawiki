<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Watchlist\WatchlistManager;

/**
 * @covers \MediaWiki\Watchlist\WatchlistManager
 *
 * @author DannyS712
 * @group Database
 */
class WatchlistManagerTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	public function testClearTitleUserNotifications() {
		$options = new ServiceOptions(
			WatchlistManager::CONSTRUCTOR_OPTIONS,
			[
				'UseEnotif' => false,
				'ShowUpdatedMarker' => false
			]
		);

		$services = $this->getServiceContainer();
		$nsInfo = $this->createNoOpMock( NamespaceInfo::class, [ 'isWatchable' ] );
		$nsInfo->method( 'isWatchable' )->willReturnCallback(
			function ( $ns ) {
				return $ns >= 0;
			}
		);

		$manager = new WatchlistManager(
			$options,
			$this->createHookContainer(),
			$services->getReadOnlyMode(),
			$services->getRevisionLookup(),
			$services->getTalkPageNotificationManager(),
			$services->getWatchedItemStore(),
			$services->getUserFactory(),
			$nsInfo
		);

		$username = 'User Name';
		$user = $this->mockUserAuthorityWithPermissions(
			new UserIdentityValue( 100, $username ),
			[ 'editmywatchlist' ]
		);
		$title = new PageIdentityValue( 100, NS_USER_TALK, $username, PageIdentityValue::LOCAL );

		$this->db->startAtomic( __METHOD__ ); // let deferred updates queue up

		$updateCountBefore = DeferredUpdates::pendingUpdatesCount();
		$manager->clearTitleUserNotifications( $user, $title );
		$updateCountAfter = DeferredUpdates::pendingUpdatesCount();
		$this->assertGreaterThan( $updateCountBefore, $updateCountAfter, 'An update should have been queued' );

		$this->db->endAtomic( __METHOD__ ); // run deferred updates

		$this->assertSame( 0, DeferredUpdates::pendingUpdatesCount(), 'No pending updates' );
	}

	/**
	 * @covers \MediaWiki\Watchlist\WatchlistManager::isWatched
	 * @covers \MediaWiki\Watchlist\WatchlistManager::isTempWatched
	 * @covers \MediaWiki\Watchlist\WatchlistManager::addWatch
	 * @covers \MediaWiki\Watchlist\WatchlistManager::addWatchIgnoringRights()
	 * @covers \MediaWiki\Watchlist\WatchlistManager::removeWatch()
	 */
	public function testWatchlist() {
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$authority = $this->mockUserAuthorityWithPermissions(
			$userIdentity,
			[ 'editmywatchlist', 'viewmywatchlist' ]
		);
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$services = $this->getServiceContainer();
		$services->getWatchedItemStore()->clearUserWatchedItems( $userIdentity );

		$manager = $services->getWatchlistManager();

		$this->assertFalse(
			$manager->isWatched( $authority, $title ),
			'The article has not been watched yet'
		);
		$this->assertFalse(
			$manager->isTempWatched( $authority, $title ),
			"The article hasn't been temporarily watched"
		);

		$manager->addWatch( $authority, $title );
		$this->assertTrue( $manager->isWatched( $authority, $title ), 'The article has been watched' );
		$this->assertFalse(
			$manager->isTempWatched( $authority, $title ),
			"The article hasn't been temporarily watched"
		);

		$manager->removeWatch( $authority, $title );
		$this->assertFalse( $manager->isWatched( $authority, $title ), 'The article has been unwatched' );
		$this->assertFalse(
			$manager->isTempWatched( $authority, $title ),
			"The article hasn't been temporarily watched"
		);

		$manager->addWatch( $authority, $title, '2 weeks' );
		$this->assertTrue( $manager->isWatched( $authority, $title ), 'The article has been watched' );
		$this->assertTrue(
			$manager->isTempWatched( $authority, $title ), 'The article has been tempoarily watched'
		);

		$manager->removeWatch( $authority, $title );
		$this->assertFalse( $manager->isWatched( $authority, $title ), 'The article has been unwatched' );
		$this->assertFalse(
			$manager->isTempWatched( $authority, $title ),
			"The article hasn't been temporarily watched"
		);

		$manager->addWatchIgnoringRights( $userIdentity, $title );
		$this->assertTrue( $manager->isWatched( $authority, $title ), 'The article has been watched' );
		$this->assertFalse(
			$manager->isTempWatched( $authority, $title ),
			"The article hasn't been temporarily watched"
		);
	}

	/**
	 * @covers \MediaWiki\Watchlist\WatchlistManager::isWatched
	 * @covers \MediaWiki\Watchlist\WatchlistManager::isWatchedIgnoringRights
	 * @covers \MediaWiki\Watchlist\WatchlistManager::isTempWatched
	 * @covers \MediaWiki\Watchlist\WatchlistManager::isTempWatchedIgnoringRights
	 * @covers \MediaWiki\Watchlist\WatchlistManager::addWatch
	 * @covers \MediaWiki\Watchlist\WatchlistManager::addWatchIgnoringRights()
	 * @covers \MediaWiki\Watchlist\WatchlistManager::removeWatch()
	 */
	public function testWatchlistNoRights() {
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$authority = $this->mockUserAuthorityWithPermissions( $userIdentity, [] );
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$services = $this->getServiceContainer();
		$services->getWatchedItemStore()->clearUserWatchedItems( $userIdentity );

		$manager = $services->getWatchlistManager();

		$this->assertFalse( $manager->isWatched( $authority, $title ) );
		$this->assertFalse( $manager->isTempWatched( $authority, $title ) );
		$this->assertFalse( $manager->isWatchedIgnoringRights( $userIdentity, $title ) );
		$this->assertFalse( $manager->isTempWatchedIgnoringRights( $userIdentity, $title ) );

		$manager->addWatch( $authority, $title );
		$this->assertFalse( $manager->isWatched( $authority, $title ) );
		$this->assertFalse( $manager->isTempWatched( $authority, $title ) );
		$this->assertFalse( $manager->isWatchedIgnoringRights( $userIdentity, $title ) );
		$this->assertFalse( $manager->isTempWatchedIgnoringRights( $userIdentity, $title ) );

		$manager->addWatchIgnoringRights( $userIdentity, $title );
		$this->assertFalse( $manager->isWatched( $authority, $title ) );
		$this->assertFalse( $manager->isTempWatched( $authority, $title ) );
		$this->assertTrue( $manager->isWatchedIgnoringRights( $userIdentity, $title ) );
		$this->assertFalse( $manager->isTempWatchedIgnoringRights( $userIdentity, $title ) );

		$manager->addWatchIgnoringRights( $userIdentity, $title, '1 week' );
		$this->assertFalse( $manager->isWatched( $authority, $title ) );
		$this->assertFalse( $manager->isTempWatched( $authority, $title ) );
		$this->assertTrue( $manager->isWatchedIgnoringRights( $userIdentity, $title ) );
		$this->assertTrue( $manager->isTempWatchedIgnoringRights( $userIdentity, $title ) );

		$manager->removeWatch( $authority, $title );
		$this->assertFalse( $manager->isWatched( $authority, $title ) );
		$this->assertFalse( $manager->isTempWatched( $authority, $title ) );
		$this->assertTrue( $manager->isWatchedIgnoringRights( $userIdentity, $title ) );
		$this->assertTrue( $manager->isTempWatchedIgnoringRights( $userIdentity, $title ) );

		$manager->removeWatchIgnoringRights( $userIdentity, $title );
		$this->assertFalse( $manager->isWatched( $authority, $title ) );
		$this->assertFalse( $manager->isTempWatched( $authority, $title ) );
		$this->assertFalse( $manager->isWatchedIgnoringRights( $userIdentity, $title ) );
		$this->assertFalse( $manager->isTempWatchedIgnoringRights( $userIdentity, $title ) );
	}
}
