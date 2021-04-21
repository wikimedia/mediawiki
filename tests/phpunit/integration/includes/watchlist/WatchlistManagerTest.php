<?php

use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \MediaWiki\Watchlist\WatchlistManager
 *
 * @author DannyS712
 * @group Database
 */
class WatchlistManagerTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;

	/**
	 * @var bool
	 */
	private $watched;

	/**
	 * @var ?string
	 */
	private $expiry;

	private function setMockWatchedItemStore() {
		$this->watched = false;
		$this->expiry = null;
		$mock = $this->createMock( WatchedItemStore::class );
		$mock->method( 'getWatchedItem' )->willReturnCallback( function ( $user, $target ) {
			if ( $this->watched ) {
				return new WatchedItem(
					$user,
					$target,
					null,
					$this->expiry
				);
			}
			return null;
		} );
		$mock->method( 'addWatch' )->willReturnCallback( function ( $user, $title, $expiry ) {
			$this->watched = true;
			$this->expiry = $expiry;
			return true;
		} );
		$mock->method( 'removeWatch' )->willReturnCallback( function ( $user, $title ) {
			$this->watched = false;
			return false;
		} );
		$mock->method( 'isWatched' )->willReturnCallback( function () {
			return $this->watched;
		} );
		$this->setService( 'WatchedItemStore', $mock );
		return $mock;
	}

	public function testClearTitleUserNotifications() {
		$username = 'User Name';
		$userIdentity = new UserIdentityValue( 100, $username );
		$performer = $this->mockUserAuthorityWithPermissions( $userIdentity, [ 'editmywatchlist' ] );
		$title = new PageIdentityValue( 100, NS_USER_TALK, $username, PageIdentityValue::LOCAL );

		$watchlistManager = $this->getServiceContainer()->getWatchlistManager();

		$this->db->startAtomic( __METHOD__ ); // let deferred updates queue up

		$updateCountBefore = DeferredUpdates::pendingUpdatesCount();
		$watchlistManager->clearTitleUserNotifications( $performer, $title );
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
		$watchedItemStore = $services->getWatchedItemStore();
		$watchlistManager = $services->getWatchlistManager();

		$watchedItemStore->clearUserWatchedItems( $userIdentity );

		$this->assertFalse(
			$watchlistManager->isWatched( $authority, $title ),
			'The article has not been watched yet'
		);
		$this->assertFalse(
			$watchlistManager->isTempWatched( $authority, $title ),
			"The article hasn't been temporarily watched"
		);

		$watchlistManager->addWatch( $authority, $title );
		$this->assertTrue( $watchlistManager->isWatched( $authority, $title ), 'The article has been watched' );
		$this->assertFalse(
			$watchlistManager->isTempWatched( $authority, $title ),
			"The article hasn't been temporarily watched"
		);

		$watchlistManager->removeWatch( $authority, $title );
		$this->assertFalse( $watchlistManager->isWatched( $authority, $title ), 'The article has been unwatched' );
		$this->assertFalse(
			$watchlistManager->isTempWatched( $authority, $title ),
			"The article hasn't been temporarily watched"
		);

		$watchlistManager->addWatch( $authority, $title, '2 weeks' );
		$this->assertTrue( $watchlistManager->isWatched( $authority, $title ), 'The article has been watched' );
		$this->assertTrue(
			$watchlistManager->isTempWatched( $authority, $title ), 'The article has been tempoarily watched'
		);

		$watchlistManager->removeWatch( $authority, $title );
		$this->assertFalse( $watchlistManager->isWatched( $authority, $title ), 'The article has been unwatched' );
		$this->assertFalse(
			$watchlistManager->isTempWatched( $authority, $title ),
			"The article hasn't been temporarily watched"
		);

		$watchlistManager->addWatchIgnoringRights( $userIdentity, $title );
		$this->assertTrue( $watchlistManager->isWatched( $authority, $title ), 'The article has been watched' );
		$this->assertFalse(
			$watchlistManager->isTempWatched( $authority, $title ),
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
		$watchedItemStore = $services->getWatchedItemStore();
		$watchlistManager = $services->getWatchlistManager();

		$watchedItemStore->clearUserWatchedItems( $userIdentity );

		$this->assertFalse( $watchlistManager->isWatched( $authority, $title ) );
		$this->assertFalse( $watchlistManager->isTempWatched( $authority, $title ) );
		$this->assertFalse( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
		$this->assertFalse( $watchlistManager->isTempWatchedIgnoringRights( $userIdentity, $title ) );

		$watchlistManager->addWatch( $authority, $title );
		$this->assertFalse( $watchlistManager->isWatched( $authority, $title ) );
		$this->assertFalse( $watchlistManager->isTempWatched( $authority, $title ) );
		$this->assertFalse( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
		$this->assertFalse( $watchlistManager->isTempWatchedIgnoringRights( $userIdentity, $title ) );

		$watchlistManager->addWatchIgnoringRights( $userIdentity, $title );
		$this->assertFalse( $watchlistManager->isWatched( $authority, $title ) );
		$this->assertFalse( $watchlistManager->isTempWatched( $authority, $title ) );
		$this->assertTrue( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
		$this->assertFalse( $watchlistManager->isTempWatchedIgnoringRights( $userIdentity, $title ) );

		$watchlistManager->addWatchIgnoringRights( $userIdentity, $title, '1 week' );
		$this->assertFalse( $watchlistManager->isWatched( $authority, $title ) );
		$this->assertFalse( $watchlistManager->isTempWatched( $authority, $title ) );
		$this->assertTrue( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
		$this->assertTrue( $watchlistManager->isTempWatchedIgnoringRights( $userIdentity, $title ) );

		$watchlistManager->removeWatch( $authority, $title );
		$this->assertFalse( $watchlistManager->isWatched( $authority, $title ) );
		$this->assertFalse( $watchlistManager->isTempWatched( $authority, $title ) );
		$this->assertTrue( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
		$this->assertTrue( $watchlistManager->isTempWatchedIgnoringRights( $userIdentity, $title ) );

		$watchlistManager->removeWatchIgnoringRights( $userIdentity, $title );
		$this->assertFalse( $watchlistManager->isWatched( $authority, $title ) );
		$this->assertFalse( $watchlistManager->isTempWatched( $authority, $title ) );
		$this->assertFalse( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
		$this->assertFalse( $watchlistManager->isTempWatchedIgnoringRights( $userIdentity, $title ) );
	}

	/**
	 * @covers \MediaWiki\Watchlist\WatchlistManager::setWatch()
	 */
	public function testSetWatchWithExpiry() {
		// Already watched, but we're adding an expiry so 'addWatch' should be called.
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$performer = $this->mockUserAuthorityWithPermissions( $userIdentity, [ 'editmywatchlist' ] );
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$mock = $this->setMockWatchedItemStore();
		$mock->expects( $this->exactly( 4 ) )->method( 'addWatch' ); // watch page and its talk page twice
		$mock->expects( $this->never() )->method( 'removeWatch' );

		$services = $this->getServiceContainer();
		$watchlistManager = $services->getWatchlistManager();

		$watchlistManager->addWatchIgnoringRights( $userIdentity, $title );

		$status = $watchlistManager->setWatch( true, $performer, $title, '1 week' );

		$this->assertTrue( $status->isGood() );
		$this->assertTrue( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
	}

	/**
	 * @covers \MediaWiki\Watchlist\WatchlistManager::setWatch()
	 * @throws Exception
	 */
	public function testSetWatchUserNotLoggedIn() {
		$userIdentity = new UserIdentityValue( 0, 'User Name' );
		$performer = $this->mockUserAuthorityWithPermissions( $userIdentity, [ 'editmywatchlist' ] );
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$mock = $this->setMockWatchedItemStore();
		$mock->expects( $this->never() )->method( 'addWatch' );
		$mock->expects( $this->never() )->method( 'removeWatch' );

		$services = $this->getServiceContainer();
		$watchlistManager = $services->getWatchlistManager();

		$status = $watchlistManager->setWatch( true, $performer, $title );

		// returns immediately with no error if not logged in
		$this->assertTrue( $status->isGood() );
	}

	/**
	 * @covers \MediaWiki\Watchlist\WatchlistManager::setWatch()
	 * @throws Exception
	 */
	public function testSetWatchSkipsIfAlreadyWatched() {
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$performer = $this->mockUserAuthorityWithPermissions( $userIdentity, [ 'editmywatchlist' ] );
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$mock = $this->setMockWatchedItemStore();
		$mock->expects( $this->exactly( 2 ) )->method( 'addWatch' ); // watch page and its talk page
		$mock->expects( $this->never() )->method( 'removeWatch' );

		$services = $this->getServiceContainer();
		$watchlistManager = $services->getWatchlistManager();

		$expiry = '99990123000000';
		$watchlistManager->addWatchIgnoringRights( $userIdentity, $title, $expiry );

		// Same expiry
		$status = $watchlistManager->setWatch( true, $performer, $title, $expiry );

		$this->assertTrue( $status->isGood() );
	}

	/**
	 * @covers \MediaWiki\Watchlist\WatchlistManager::setWatch()
	 * @throws Exception
	 */
	public function testSetWatchSkipsIfAlreadyUnWatched() {
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$performer = $this->mockUserAuthorityWithPermissions( $userIdentity, [ 'editmywatchlist' ] );
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$mock = $this->setMockWatchedItemStore();
		$mock->expects( $this->never() )->method( 'addWatch' );
		$mock->expects( $this->never() )->method( 'removeWatch' );

		$services = $this->getServiceContainer();
		$watchlistManager = $services->getWatchlistManager();

		$status = $watchlistManager->setWatch( false, $performer, $title );

		$this->assertTrue( $status->isGood() );
	}

	/**
	 * @covers \MediaWiki\Watchlist\WatchlistManager::setWatch()
	 * @throws Exception
	 */
	public function testSetWatchWatchesIfWatch() {
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$performer = $this->mockUserAuthorityWithPermissions( $userIdentity, [ 'editmywatchlist' ] );
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$services = $this->getServiceContainer();
		$watchlistManager = $services->getWatchlistManager();

		$watchlistManager->addWatchIgnoringRights( $userIdentity, $title );

		$status = $watchlistManager->setWatch( true, $performer, $title );

		$this->assertTrue( $status->isGood() );
		$this->assertTrue( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
	}

	/**
	 * @covers \MediaWiki\Watchlist\WatchlistManager::setWatch()
	 * @throws Exception
	 */
	public function testSetWatchUnwatchesIfUnwatch() {
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$performer = $this->mockUserAuthorityWithPermissions( $userIdentity, [ 'editmywatchlist' ] );
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$services = $this->getServiceContainer();
		$watchedItemStore = $services->getWatchedItemStore();
		$watchlistManager = $services->getWatchlistManager();

		$watchedItemStore->clearUserWatchedItems( $userIdentity );

		$status = $watchlistManager->setWatch( false, $performer, $title );

		$this->assertTrue( $status->isGood() );
		$this->assertFalse( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
	}

	/**
	 * @covers \MediaWiki\Watchlist\WatchlistManager::addWatchIgnoringRights()
	 * @throws Exception
	 */
	public function testDoWatchNoCheckRights() {
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$performer = $this->mockUserAuthorityWithPermissions( $userIdentity, [] );
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$services = $this->getServiceContainer();
		$watchedItemStore = $services->getWatchedItemStore();
		$watchlistManager = $services->getWatchlistManager();

		$watchedItemStore->clearUserWatchedItems( $userIdentity );

		$actual = $watchlistManager->addWatchIgnoringRights( $userIdentity, $title );

		$this->assertTrue( $actual->isGood() );
		$this->assertTrue( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
	}

	/**
	 * @covers \MediaWiki\Watchlist\WatchlistManager::addWatch()
	 * @throws Exception
	 */
	public function testDoWatchUserNotPermittedStatusNotGood() {
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$performer = $this->mockUserAuthorityWithPermissions( $userIdentity, [] );
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$services = $this->getServiceContainer();
		$watchedItemStore = $services->getWatchedItemStore();
		$watchlistManager = $services->getWatchlistManager();

		$watchedItemStore->clearUserWatchedItems( $userIdentity );

		$actual = $watchlistManager->addWatch( $performer, $title );

		$this->assertFalse( $actual->isGood() );
		$this->assertFalse( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
	}

	/**
	 * @covers \MediaWiki\Watchlist\WatchlistManager::addWatch()
	 * @throws Exception
	 */
	public function testDoWatchSuccess() {
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$performer = $this->mockUserAuthorityWithPermissions( $userIdentity, [ 'editmywatchlist' ] );
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$services = $this->getServiceContainer();
		$watchedItemStore = $services->getWatchedItemStore();
		$watchlistManager = $services->getWatchlistManager();

		$watchedItemStore->clearUserWatchedItems( $userIdentity );

		$actual = $watchlistManager->addWatch( $performer, $title );

		$this->assertTrue( $actual->isGood() );
		$this->assertTrue( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
	}

	/**
	 * @covers \MediaWiki\Watchlist\WatchlistManager::removeWatch()
	 * @throws Exception
	 */
	public function testDoUnwatchWithoutRights() {
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$performer = $this->mockUserAuthorityWithPermissions( $userIdentity, [] );
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$services = $this->getServiceContainer();
		$watchlistManager = $services->getWatchlistManager();

		$watchlistManager->addWatchIgnoringRights( $userIdentity, $title );

		$actual = $watchlistManager->removeWatch( $performer, $title );

		$this->assertFalse( $actual->isGood() );
		$this->assertTrue( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
	}

	/**
	 * @covers \MediaWiki\Watchlist\WatchlistManager::removeWatch()
	 */
	public function testDoUnwatchUserHookAborted() {
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$performer = $this->mockUserAuthorityWithPermissions( $userIdentity, [ 'editmywatchlist' ] );
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$services = $this->getServiceContainer();
		$watchlistManager = $services->getWatchlistManager();

		$watchlistManager->addWatchIgnoringRights( $userIdentity, $title );
		$this->setTemporaryHook( 'UnwatchArticle', static function () {
			return false;
		} );

		$status = $watchlistManager->removeWatch( $performer, $title );

		$this->assertFalse( $status->isGood() );
		$errors = $status->getErrors();
		$this->assertCount( 1, $errors );
		$this->assertEquals( 'hookaborted', $errors[0]['message'] );
		$this->assertTrue( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
	}

	/**
	 * @covers \MediaWiki\Watchlist\WatchlistManager::removeWatch()
	 * @throws Exception
	 */
	public function testDoUnwatchSuccess() {
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$performer = $this->mockUserAuthorityWithPermissions( $userIdentity, [ 'editmywatchlist' ] );
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$services = $this->getServiceContainer();
		$watchlistManager = $services->getWatchlistManager();

		$watchlistManager->addWatchIgnoringRights( $userIdentity, $title );

		$status = $watchlistManager->removeWatch( $performer, $title );

		$this->assertTrue( $status->isGood() );
		$this->assertFalse( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
	}
}
