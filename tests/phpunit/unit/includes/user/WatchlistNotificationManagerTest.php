<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\WatchlistNotificationManager;

/**
 * @covers \MediaWiki\User\WatchlistNotificationManager
 *
 * Cannot use the name `WatchlistNotificationManagerTest`, already used by the integration test
 * @phpcs:disable MediaWiki.Files.ClassMatchesFilename
 *
 * @author DannyS712
 */
class WatchlistNotificationManagerUnitTest extends MediaWikiUnitTestCase {

	private function getManager( array $params ) {
		$config = $params['config'] ?? [
			'UseEnotif' => false,
			'ShowUpdatedMarker' => false
		];
		$options = new ServiceOptions(
			WatchlistNotificationManager::CONSTRUCTOR_OPTIONS,
			$config
		);

		$hookContainer = $params['hookContainer'] ??
			$this->createNoOpMock( HookContainer::class );

		$permissionManager = $params['permissionManager'] ??
			$this->createNoOpMock( PermissionManager::class );

		$readOnly = $params['readOnly'] ?? false;
		$readOnlyMode = $this->createMock( ReadOnlyMode::class );
		if ( $readOnly !== 'never' ) {
			// Set 'never' for testing getTitleNotificationTimestamp, which doesn't call
			$readOnlyMode->expects( $this->once() )
				->method( 'isReadOnly' )
				->willReturn( $readOnly );
		}

		$revisionLookup = $params['revisionLookup'] ??
			$this->createNoOpAbstractMock( RevisionLookup::class );

		$talkPageNotificationManager = $params['talkPageNotificationManager'] ??
			$this->createNoOpMock( TalkPageNotificationManager::class );

		$watchedItemStore = $params['watchedItemStore'] ??
			$this->createNoOpAbstractMock( WatchedItemStoreInterface::class );

		return new WatchlistNotificationManager(
			$options,
			$hookContainer,
			$permissionManager,
			$readOnlyMode,
			$revisionLookup,
			$talkPageNotificationManager,
			$watchedItemStore
		);
	}

	public function testClearAllUserNotifications_readOnly() {
		$user = $this->createNoOpAbstractMock( UserIdentity::class );

		// ********** Code path #1 **********
		// Early return: read only mode
		$manager = $this->getManager( [
			'readOnly' => true
		] );
		$manager->clearAllUserNotifications( $user );
	}

	public function testClearAllUserNotifications_noPerms() {
		$user = $this->createNoOpAbstractMock( UserIdentity::class );

		// ********** Code path #2 **********
		// Early return: User lacks `editmywatchlist`
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->expects( $this->once() )
			->method( 'userHasRight' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( 'editmywatchlist' )
			)
			->willReturn( false );

		$manager = $this->getManager( [
			'permissionManager' => $permissionManager
		] );
		$manager->clearAllUserNotifications( $user );
	}

	public function testClearAllUserNotifications_configDisabled() {
		$user = $this->createNoOpAbstractMock( UserIdentity::class );

		// ********** Code path #3 **********
		// Early return: config with `UseEnotif` and `ShowUpdatedMarker` both false
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->expects( $this->once() )
			->method( 'userHasRight' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( 'editmywatchlist' )
			)
			->willReturn( true );
		$talkPageNotificationManager = $this->createMock( TalkPageNotificationManager::class );
		$talkPageNotificationManager->expects( $this->once() )
			->method( 'removeUserHasNewMessages' )
			->with( $this->equalTo( $user ) );

		$manager = $this->getManager( [
			'permissionManager' => $permissionManager,
			'talkPageNotificationManager' => $talkPageNotificationManager
		] );
		$manager->clearAllUserNotifications( $user );
	}

	public function testClearAllUserNotifications_falseyId() {
		// ********** Code path #4 **********
		// Early return: user's id is falsey
		$config = [
			'UseEnotif' => true,
			'ShowUpdatedMarker' => true
		];
		$user = $this->getMockBuilder( UserIdentity::class )
			->setMethods( [ 'getId' ] )
			->getMockForAbstractClass();
		$user->expects( $this->once() )
			->method( 'getId' )
			->willReturn( 0 );
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->expects( $this->once() )
			->method( 'userHasRight' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( 'editmywatchlist' )
			)
			->willReturn( true );

		$manager = $this->getManager( [
			'config' => $config,
			'permissionManager' => $permissionManager
		] );
		$manager->clearAllUserNotifications( $user );
	}

	public function testClearAllUserNotifications() {
		// ********** Code path #5 **********
		// No early returns
		$config = [
			'UseEnotif' => true,
			'ShowUpdatedMarker' => true
		];
		$user = $this->getMockBuilder( UserIdentity::class )
			->setMethods( [ 'getId' ] )
			->getMockForAbstractClass();
		$user->expects( $this->once() )
			->method( 'getId' )
			->willReturn( 1 );
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->expects( $this->once() )
			->method( 'userHasRight' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( 'editmywatchlist' )
			)
			->willReturn( true );
		$watchedItemStore = $this->getMockBuilder( WatchedItemStoreInterface::class )
			->setMethods( [ 'resetAllNotificationTimestampsForUser' ] )
			->getMockForAbstractClass();
		$watchedItemStore->expects( $this->once() )
			->method( 'resetAllNotificationTimestampsForUser' )
			->with( $this->equalTo( $user ) );

		$manager = $this->getManager( [
			'config' => $config,
			'permissionManager' => $permissionManager,
			'watchedItemStore' => $watchedItemStore
		] );
		$manager->clearAllUserNotifications( $user );
	}

	public function testClearTitleUserNotifications_readOnly() {
		$user = $this->createNoOpAbstractMock( UserIdentity::class );
		$title = $this->createNoOpAbstractMock( LinkTarget::class );

		// ********** Code path #1 **********
		// Early return: read only mode
		$manager = $this->getManager( [
			'readOnly' => true
		] );
		$manager->clearTitleUserNotifications( $user, $title );
	}

	public function testClearTitleUserNotifications_noPerms() {
		$user = $this->createNoOpAbstractMock( UserIdentity::class );
		$title = $this->createNoOpAbstractMock( LinkTarget::class );

		// ********** Code path #2 **********
		// Early return: User lacks `editmywatchlist`
		// readOnlyMode returned false, and since we'll use the same mock again don't only
		// expect it once
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->expects( $this->once() )
			->method( 'userHasRight' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( 'editmywatchlist' )
			)
			->willReturn( false );

		$manager = $this->getManager( [
			'permissionManager' => $permissionManager
		] );

		$manager->clearTitleUserNotifications( $user, $title );
	}

	public function testClearTitleUserNotifications_configDisabled() {
		// ********** Code path #3 **********
		// Early return: config with `UseEnotif` and `ShowUpdatedMarker` both false
		// $user and $title are now checked for if the page is the user's talk page
		// Currently only tests for when the title isn't the user's talk page, since
		// DeferredUpdates::addCallableUpdate doesn't work in unit tests
		$user = $this->getMockBuilder( UserIdentity::class )
			->setMethods( [ 'getName' ] )
			->getMockForAbstractClass();
		$user->expects( $this->once() )
			->method( 'getName' )
			->willReturn( 'UserNameGoesHere' );
		$title = $this->getMockBuilder( LinkTarget::class )
			->setMethods( [ 'getNamespace', 'getText' ] )
			->getMockForAbstractClass();
		$title->expects( $this->once() )
			->method( 'getNamespace' )
			->willReturn( NS_USER_TALK );
		$title->expects( $this->once() )
			->method( 'getText' )
			->willReturn( 'PageTitleGoesHere' );
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->expects( $this->once() )
			->method( 'userHasRight' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( 'editmywatchlist' )
			)
			->willReturn( true );

		$manager = $this->getManager( [
			'permissionManager' => $permissionManager
		] );
		$manager->clearTitleUserNotifications( $user, $title );
	}

	public function testClearTitleUserNotifications_notRegistered() {
		// ********** Code path #4 **********
		// Early return: user is not registered
		$config = [
			'UseEnotif' => true,
			'ShowUpdatedMarker' => true
		];
		$user = $this->getMockBuilder( UserIdentity::class )
			->setMethods( [ 'getName', 'isRegistered' ] )
			->getMockForAbstractClass();
		$user->expects( $this->once() )
			->method( 'getName' )
			->willReturn( 'UserNameGoesHere' );
		$user->expects( $this->once() )
			->method( 'isRegistered' )
			->willReturn( false );
		$title = $this->getMockBuilder( LinkTarget::class )
			->setMethods( [ 'getNamespace', 'getText' ] )
			->getMockForAbstractClass();
		$title->expects( $this->once() )
			->method( 'getNamespace' )
			->willReturn( NS_USER_TALK );
		$title->expects( $this->once() )
			->method( 'getText' )
			->willReturn( 'PageTitleGoesHere' );
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->expects( $this->once() )
			->method( 'userHasRight' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( 'editmywatchlist' )
			)
			->willReturn( true );

		$manager = $this->getManager( [
			'config' => $config,
			'permissionManager' => $permissionManager
		] );
		$manager->clearTitleUserNotifications( $user, $title );
	}

	public function testClearTitleUserNotifications() {
		// ********** Code path #5 **********
		// No early returns; resetNotificationTimestamp is called
		// PermissionManager now expects a different user object
		$config = [
			'UseEnotif' => true,
			'ShowUpdatedMarker' => true
		];
		$user = $this->getMockBuilder( UserIdentity::class )
			->setMethods( [ 'getName', 'isRegistered' ] )
			->getMockForAbstractClass();
		$user->expects( $this->once() )
			->method( 'getName' )
			->willReturn( 'UserNameGoesHere' );
		$user->expects( $this->once() )
			->method( 'isRegistered' )
			->willReturn( true );
		$title = $this->getMockBuilder( LinkTarget::class )
			->setMethods( [ 'getNamespace', 'getText' ] )
			->getMockForAbstractClass();
		$title->expects( $this->once() )
			->method( 'getNamespace' )
			->willReturn( NS_USER_TALK );
		$title->expects( $this->once() )
			->method( 'getText' )
			->willReturn( 'PageTitleGoesHere' );
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->expects( $this->once() )
			->method( 'userHasRight' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( 'editmywatchlist' )
			)
			->willReturn( true );
		$watchedItemStore = $this->getMockBuilder( WatchedItemStoreInterface::class )
			->setMethods( [ 'resetNotificationTimestamp' ] )
			->getMockForAbstractClass();
		$watchedItemStore->expects( $this->once() )
			->method( 'resetNotificationTimestamp' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( $title ),
				$this->equalTo( '' ),
				$this->equalTo( 0 )
			);

		$manager = $this->getManager( [
			'config' => $config,
			'permissionManager' => $permissionManager,
			'watchedItemStore' => $watchedItemStore
		] );
		$manager->clearTitleUserNotifications( $user, $title );
	}

	public function testGetTitleNotificationTimestamp_falseyId() {
		// ********** Code path #1 **********
		// Early return: user id is falsey
		$user = $this->getMockBuilder( UserIdentity::class )
			->setMethods( [ 'getId' ] )
			->getMockForAbstractClass();
		$user->expects( $this->once() )
			->method( 'getId' )
			->willReturn( 0 );
		$title = $this->createNoOpAbstractMock( LinkTarget::class );

		$manager = $this->getManager( [
			'readOnly' => 'never'
		] );
		$res = $manager->getTitleNotificationTimestamp( $user, $title );
		$this->assertFalse( $res, 'Early return for anonymous users is false' );
	}

	public function testGetTitleNotificationTimestamp_timestamp() {
		// ********** Code path #2 **********
		// Early return: value is already cached - will be tested after #3 because
		// an entry in the cache is needed (duh)

		// ********** Code path #3 **********
		// Actually check watchedItemStore, v.1-a - returns a WatchedItem with a timestamp
		// From here on a cache key will be generated each time
		$user = $this->getMockBuilder( UserIdentity::class )
			->setMethods( [ 'getId' ] )
			->getMockForAbstractClass();
		$user->expects( $this->exactly( 2 ) )
			->method( 'getId' )
			->willReturn( 1 );
		$title = $this->getMockBuilder( LinkTarget::class )
			->setMethods( [ 'getNamespace', 'getDBkey' ] )
			->getMockForAbstractClass();
		$title->expects( $this->exactly( 2 ) )
			->method( 'getNamespace' )
			->willReturn( NS_MAIN );
		$title->expects( $this->exactly( 2 ) )
			->method( 'getDBkey' )
			->willReturn( 'Page_db_Key_goesHere' );
		$watchedItem = $this->createMock( WatchedItem::class );
		$watchedItem->expects( $this->once() )
			->method( 'getNotificationTimestamp' )
			->willReturn( 'stringTimestamp' );
		$watchedItemStore = $this->getMockBuilder( WatchedItemStoreInterface::class )
			->setMethods( [ 'getWatchedItem' ] )
			->getMockForAbstractClass();
		$watchedItemStore->expects( $this->once() )
			->method( 'getWatchedItem' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( $title )
			)
			->willReturn( $watchedItem );

		$manager = $this->getManager( [
			'readOnly' => 'never',
			'watchedItemStore' => $watchedItemStore
		] );
		$res = $manager->getTitleNotificationTimestamp( $user, $title );
		$this->assertSame(
			'stringTimestamp',
			$res,
			'if getWatchedItem returns a WatchedItem, that object\'s timestamp is returned'
		);

		// ********** Code path #2 **********
		// Actually test code path #2 now that there is something in the cache
		// use the same $manager instance (so the value is in the cache, duh)
		// all of the same expectations apply - getWatchedItem shouldn't be called again, and
		// so was only expecting to be called ->once() above
		$res = $manager->getTitleNotificationTimestamp( $user, $title );
		$this->assertSame(
			'stringTimestamp',
			$res,
			'if the timestamp is cached getWatchedItem is not called again'
		);
	}

	public function testGetTitleNotificationTimestamp_null() {
		$user = $this->getMockBuilder( UserIdentity::class )
			->setMethods( [ 'getId' ] )
			->getMockForAbstractClass();
		$user->expects( $this->once() )
			->method( 'getId' )
			->willReturn( 1 );
		$title = $this->getMockBuilder( LinkTarget::class )
			->setMethods( [ 'getNamespace', 'getDBkey' ] )
			->getMockForAbstractClass();
		$title->expects( $this->once() )
			->method( 'getNamespace' )
			->willReturn( NS_MAIN );
		$title->expects( $this->once() )
			->method( 'getDBkey' )
			->willReturn( 'Page_db_Key_goesHere' );

		// ********** Code path #4 **********
		// Actually check watchedItemStore, v.1-b - returns a WatchedItem with null
		$watchedItem = $this->createMock( WatchedItem::class );
		$watchedItem->expects( $this->once() )
			->method( 'getNotificationTimestamp' )
			->willReturn( null );
		$watchedItemStore = $this->getMockBuilder( WatchedItemStoreInterface::class )
			->setMethods( [ 'getWatchedItem' ] )
			->getMockForAbstractClass();
		$watchedItemStore->expects( $this->once() )
			->method( 'getWatchedItem' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( $title )
			)
			->willReturn( $watchedItem );

		$manager = $this->getManager( [
			'readOnly' => 'never',
			'watchedItemStore' => $watchedItemStore
		] );
		$res = $manager->getTitleNotificationTimestamp( $user, $title );
		$this->assertNull( $res, 'WatchedItem can return null instead of a timestamp' );
	}

	public function testGetTitleNotificationTimestamp_false() {
		$user = $this->getMockBuilder( UserIdentity::class )
			->setMethods( [ 'getId' ] )
			->getMockForAbstractClass();
		$user->expects( $this->once() )
			->method( 'getId' )
			->willReturn( 1 );
		$title = $this->getMockBuilder( LinkTarget::class )
			->setMethods( [ 'getNamespace', 'getDBkey' ] )
			->getMockForAbstractClass();
		$title->expects( $this->once() )
			->method( 'getNamespace' )
			->willReturn( NS_MAIN );
		$title->expects( $this->once() )
			->method( 'getDBkey' )
			->willReturn( 'Page_db_Key_goesHere' );

		// ********** Code path #5 **********
		// Actually check watchedItemStore, v.2 - returns false
		$watchedItemStore = $this->getMockBuilder( WatchedItemStoreInterface::class )
			->setMethods( [ 'getWatchedItem' ] )
			->getMockForAbstractClass();
		$watchedItemStore->expects( $this->once() )
			->method( 'getWatchedItem' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( $title )
			)
			->willReturn( false );

		$manager = $this->getManager( [
			'readOnly' => 'never',
			'watchedItemStore' => $watchedItemStore
		] );
		$res = $manager->getTitleNotificationTimestamp( $user, $title );
		$this->assertFalse(
			$res,
			'getWatchedItem can return false if the item is not watched'
		);
	}

}
