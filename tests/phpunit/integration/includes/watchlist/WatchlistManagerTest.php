<?php

use MediaWiki\MainConfigNames;
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
	 * While this *could* be moved to a unit test, it is specifically kept
	 * here as an integration test to double check that the actual integration
	 * between this service and others, and getting this service from the
	 * service container, works as expected. Please don't move it to
	 * the unit tests.
	 *
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

		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
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
			$watchlistManager->isTempWatched( $authority, $title ), 'The article has been temporarily watched'
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
	 * Can't move to unit tests because if the user is missing rights
	 * the status is from User::newFatalPermissionDeniedStatus which uses
	 * MediaWikiServices
	 *
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

		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
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
	 * Can't move to unit tests because if the user is missing rights
	 * the status is from User::newFatalPermissionDeniedStatus which uses
	 * MediaWikiServices
	 *
	 * @covers \MediaWiki\Watchlist\WatchlistManager::addWatch()
	 */
	public function testAddWatchUserNotPermittedStatusNotGood() {
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$performer = $this->mockUserAuthorityWithPermissions( $userIdentity, [] );
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$services = $this->getServiceContainer();
		$watchedItemStore = $services->getWatchedItemStore();
		$watchlistManager = $services->getWatchlistManager();

		$watchedItemStore->clearUserWatchedItems( $userIdentity );

		$actual = $watchlistManager->addWatch( $performer, $title );

		$this->assertStatusNotGood( $actual );
		$this->assertFalse( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
	}

	/**
	 * Can't move to unit tests because if the user is missing rights
	 * the status is from User::newFatalPermissionDeniedStatus which uses
	 * MediaWikiServices
	 *
	 * @covers \MediaWiki\Watchlist\WatchlistManager::removeWatch()
	 */
	public function testRemoveWatchWithoutRights() {
		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		$performer = $this->mockUserAuthorityWithPermissions( $userIdentity, [] );
		$title = new PageIdentityValue( 100, NS_MAIN, 'Page_db_Key_goesHere', PageIdentityValue::LOCAL );

		$services = $this->getServiceContainer();
		$watchlistManager = $services->getWatchlistManager();

		$watchlistManager->addWatchIgnoringRights( $userIdentity, $title );

		$actual = $watchlistManager->removeWatch( $performer, $title );

		$this->assertStatusNotGood( $actual );
		$this->assertTrue( $watchlistManager->isWatchedIgnoringRights( $userIdentity, $title ) );
	}

}
