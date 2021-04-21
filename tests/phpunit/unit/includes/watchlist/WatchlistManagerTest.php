<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Watchlist\WatchlistManager;

/**
 * @covers \MediaWiki\Watchlist\WatchlistManager
 *
 * Cannot use the name `WatchlistManagerTest`, already used by the integration test
 * @phpcs:disable MediaWiki.Files.ClassMatchesFilename
 *
 * @author DannyS712
 */
class WatchlistManagerUnitTest extends MediaWikiUnitTestCase {
	use MockTitleTrait;
	use MockAuthorityTrait;

	private function getManager( array $params = [] ) {
		$config = $params['config'] ?? [
			'UseEnotif' => false,
			'ShowUpdatedMarker' => false,
		];
		$options = new ServiceOptions(
			WatchlistManager::CONSTRUCTOR_OPTIONS,
			$config
		);

		$readOnly = $params['readOnly'] ?? false;
		$readOnlyMode = $this->createMock( ReadOnlyMode::class );
		if ( $readOnly !== 'never' ) {
			// Set 'never' for testing getTitleNotificationTimestamp, which doesn't call
			$readOnlyMode->expects( $this->atLeast( 1 ) )
				->method( 'isReadOnly' )
				->willReturn( $readOnly );
		}

		$talkPageNotificationManager = $params['talkPageNotificationManager'] ??
			$this->createNoOpMock( TalkPageNotificationManager::class );

		$watchedItemStore = $params['watchedItemStore'] ??
			$this->createNoOpAbstractMock( WatchedItemStoreInterface::class );

		$userFactory = $params['userFactory'] ??
			$this->createNoOpAbstractMock( UserFactory::class );

		$nsInfo = $this->createNoOpMock( NamespaceInfo::class, [ 'isWatchable' ] );
		$nsInfo->method( 'isWatchable' )->willReturnCallback(
			function ( $ns ) {
				return $ns >= 0;
			}
		);

		return new WatchlistManager(
			$options,
			$this->createHookContainer(),
			$readOnlyMode,
			$this->createMock( RevisionLookup::class ),
			$talkPageNotificationManager,
			$watchedItemStore,
			$userFactory,
			$nsInfo,
			$this->createMock( WikiPageFactory::class )
		);
	}

	private function getAuthorityAndUserFactory( UserIdentity $userIdentity, array $permissions = [] ) {
		$authority = $this->mockUserAuthorityWithPermissions(
			$userIdentity,
			$permissions
		);

		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newFromUserIdentity' )
			->willReturnCallback( function ( $userIdentity ) use ( $permissions ) {
				$user = $this->createMock( User::class );
				$user->method( 'isAllowed' )
					->willReturnCallback( function ( $permission ) use ( $permissions ) {
						return in_array( $permission, $permissions );
					} );
				$user->method( 'getUser' )
					->willReturn( $userIdentity );
				return $user;
			} );

		return [ $authority, $userFactory ];
	}

	public function testClearAllUserNotifications_readOnly() {
		// ********** Code path #1 **********
		// Early return: read only mode

		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		list( $authority, ) = $this->getAuthorityAndUserFactory( $userIdentity );

		$watchedItemStore = $this->createMock( WatchedItemStoreInterface::class );
		$watchedItemStore->expects( $this->never() )
			->method( 'resetAllNotificationTimestampsForUser' );

		$manager = $this->getManager( [
			'readOnly' => true,
			'watchedItemStore' => $watchedItemStore
		] );

		$manager->clearAllUserNotifications( $userIdentity );
		$manager->clearAllUserNotifications( $authority );
	}

	public function testClearAllUserNotifications_noPerms() {
		// ********** Code path #2 **********
		// Early return: User lacks `editmywatchlist`

		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		list( $authority, $userFactory ) = $this->getAuthorityAndUserFactory( $userIdentity );

		$watchedItemStore = $this->createMock( WatchedItemStoreInterface::class );
		$watchedItemStore->expects( $this->never() )
			->method( 'resetAllNotificationTimestampsForUser' );

		$manager = $this->getManager( [
			'watchedItemStore' => $watchedItemStore,
			'userFactory' => $userFactory
		] );

		$manager->clearAllUserNotifications( $userIdentity );
		$manager->clearAllUserNotifications( $authority );
	}

	public function testClearAllUserNotifications_configDisabled() {
		// ********** Code path #3 **********
		// Early return: config with `UseEnotif` and `ShowUpdatedMarker` both false

		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		list( $authority, $userFactory ) = $this->getAuthorityAndUserFactory(
			$userIdentity,
			[ 'editmywatchlist' ]
		);

		$talkPageNotificationManager = $this->createMock( TalkPageNotificationManager::class );
		$talkPageNotificationManager->expects( $this->exactly( 2 ) )
			->method( 'removeUserHasNewMessages' );

		$watchedItemStore = $this->createMock( WatchedItemStoreInterface::class );
		$watchedItemStore->expects( $this->never() )
			->method( 'resetAllNotificationTimestampsForUser' );

		$manager = $this->getManager( [
			'watchedItemStore' => $watchedItemStore,
			'talkPageNotificationManager' => $talkPageNotificationManager,
			'userFactory' => $userFactory
		] );

		$manager->clearAllUserNotifications( $userIdentity );
		$manager->clearAllUserNotifications( $authority );
	}

	public function testClearAllUserNotifications_falseyId() {
		// ********** Code path #4 **********
		// Early return: user's id is falsey

		$config = [
			'UseEnotif' => true,
			'ShowUpdatedMarker' => true
		];

		$userIdentity = new UserIdentityValue( 0, 'User Name' );
		list( $authority, $userFactory ) = $this->getAuthorityAndUserFactory(
			$userIdentity,
			[ 'editmywatchlist' ]
		);

		$watchedItemStore = $this->createMock( WatchedItemStoreInterface::class );
		$watchedItemStore->expects( $this->never() )
			->method( 'resetAllNotificationTimestampsForUser' );

		$manager = $this->getManager( [
			'config' => $config,
			'watchedItemStore' => $watchedItemStore,
			'userFactory' => $userFactory
		] );

		$manager->clearAllUserNotifications( $userIdentity );
		$manager->clearAllUserNotifications( $authority );
	}

	public function testClearAllUserNotifications() {
		// ********** Code path #5 **********
		// No early returns

		$config = [
			'UseEnotif' => true,
			'ShowUpdatedMarker' => true
		];

		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		list( $authority, $userFactory ) = $this->getAuthorityAndUserFactory(
			$userIdentity,
			[ 'editmywatchlist' ]
		);

		$watchedItemStore = $this->createMock( WatchedItemStoreInterface::class );
		$watchedItemStore->expects( $this->exactly( 2 ) )
			->method( 'resetAllNotificationTimestampsForUser' );

		$manager = $this->getManager( [
			'config' => $config,
			'watchedItemStore' => $watchedItemStore,
			'userFactory' => $userFactory
		] );

		$manager->clearAllUserNotifications( $userIdentity );
		$manager->clearAllUserNotifications( $authority );
	}

	public function provideTestPageFactory() {
		yield [ function ( $pageId, $namespace, $dbKey ) {
			return new TitleValue( $namespace, $dbKey );
		} ];
		yield [ function ( $pageId, $namespace, $dbKey ) {
			return new PageIdentityValue( $pageId, $namespace, $dbKey, PageIdentityValue::LOCAL );
		} ];
		yield [ function ( $pageId, $namespace, $dbKey ) {
			return $this->makeMockTitle( $dbKey, [
				'id' => $pageId,
				'namespace' => $namespace
			] );
		} ];
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testClearTitleUserNotifications_readOnly( $testPageFactory ) {
		// ********** Code path #1 **********
		// Early return: read only mode

		$title = $testPageFactory( 100, 0, 'SomeDbKey' );

		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		list( $authority, ) = $this->getAuthorityAndUserFactory( $userIdentity );

		$watchedItemStore = $this->createMock( WatchedItemStoreInterface::class );
		$watchedItemStore->expects( $this->never() )
			->method( 'resetNotificationTimestamp' );

		$manager = $this->getManager( [
			'readOnly' => true,
			'watchedItemStore' => $watchedItemStore
		] );

		$manager->clearTitleUserNotifications( $userIdentity, $title );
		$manager->clearTitleUserNotifications( $authority, $title );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testClearTitleUserNotifications_noPerms( $testPageFactory ) {
		// ********** Code path #2 **********
		// Early return: User lacks `editmywatchlist`

		$title = $testPageFactory( 100, 0, 'SomeDbKey' );

		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		list( $authority, $userFactory ) = $this->getAuthorityAndUserFactory( $userIdentity );

		$watchedItemStore = $this->createMock( WatchedItemStoreInterface::class );
		$watchedItemStore->expects( $this->never() )
			->method( 'resetNotificationTimestamp' );

		$manager = $this->getManager( [
			'watchedItemStore' => $watchedItemStore,
			'userFactory' => $userFactory
		] );

		$manager->clearTitleUserNotifications( $userIdentity, $title );
		$manager->clearTitleUserNotifications( $authority, $title );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testClearTitleUserNotifications_configDisabled( $testPageFactory ) {
		// ********** Code path #3 **********
		// Early return: config with `UseEnotif` and `ShowUpdatedMarker` both false

		$title = $testPageFactory( 100, NS_USER_TALK, 'PageTitleGoesHere' );

		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		list( $authority, $userFactory ) = $this->getAuthorityAndUserFactory(
			$userIdentity,
			[ 'editmywatchlist' ]
		);

		$watchedItemStore = $this->createMock( WatchedItemStoreInterface::class );
		$watchedItemStore->expects( $this->never() )
			->method( 'resetNotificationTimestamp' );

		$manager = $this->getManager( [
			'watchedItemStore' => $watchedItemStore,
			'userFactory' => $userFactory
		] );

		$manager->clearTitleUserNotifications( $userIdentity, $title );
		$manager->clearTitleUserNotifications( $authority, $title );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testClearTitleUserNotifications_notRegistered( $testPageFactory ) {
		// ********** Code path #4 **********
		// Early return: user is not registered

		$title = $testPageFactory( 100, NS_USER_TALK, 'PageTitleGoesHere' );

		$config = [
			'UseEnotif' => true,
			'ShowUpdatedMarker' => true
		];

		$userIdentity = new UserIdentityValue( 0, 'User Name' );
		list( $authority, $userFactory ) = $this->getAuthorityAndUserFactory(
			$userIdentity,
			[ 'editmywatchlist' ]
		);

		$watchedItemStore = $this->createMock( WatchedItemStoreInterface::class );
		$watchedItemStore->expects( $this->never() )
			->method( 'resetNotificationTimestamp' );

		$manager = $this->getManager( [
			'config' => $config,
			'userFactory' => $userFactory,
			'watchedItemStore' => $watchedItemStore
		] );

		$manager->clearTitleUserNotifications( $userIdentity, $title );
		$manager->clearTitleUserNotifications( $authority, $title );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testClearTitleUserNotifications( $testPageFactory ) {
		// ********** Code path #5 **********
		// No early returns; resetNotificationTimestamp is called

		$title = $testPageFactory( 100, NS_USER_TALK, 'PageTitleGoesHere' );

		$config = [
			'UseEnotif' => true,
			'ShowUpdatedMarker' => true
		];

		$userIdentity = new UserIdentityValue( 100, 'User Name' );
		list( $authority, $userFactory ) = $this->getAuthorityAndUserFactory(
			$userIdentity,
			[ 'editmywatchlist' ]
		);

		$watchedItemStore = $this->createMock( WatchedItemStoreInterface::class );
		$watchedItemStore->expects( $this->exactly( 2 ) )
			->method( 'resetNotificationTimestamp' );

		$manager = $this->getManager( [
			'config' => $config,
			'userFactory' => $userFactory,
			'watchedItemStore' => $watchedItemStore
		] );

		$manager->clearTitleUserNotifications( $userIdentity, $title );
		$manager->clearTitleUserNotifications( $authority, $title );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetTitleNotificationTimestamp_falseyId( $testPageFactory ) {
		// ********** Code path #1 **********
		// Early return: user id is falsey

		$userIdentity = new UserIdentityValue( 0, 'User Name' );

		$title = $testPageFactory( 100, 0, 'SomeDbKey' );

		$manager = $this->getManager( [
			'readOnly' => 'never'
		] );

		$res = $manager->getTitleNotificationTimestamp( $userIdentity, $title );

		$this->assertFalse( $res, 'Early return for anonymous users is false' );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetTitleNotificationTimestamp_timestamp( $testPageFactory ) {
		// ********** Code path #2 **********
		// Early return: value is already cached - will be tested after #3 because
		// an entry in the cache is needed (duh)

		// ********** Code path #3 **********
		// Actually check watchedItemStore, v.1-a - returns a WatchedItem with a timestamp
		// From here on a cache key will be generated each time

		$userIdentity = new UserIdentityValue( 100, 'User Name' );

		$title = $testPageFactory( 100, NS_MAIN, 'Page_db_Key_goesHere' );

		$watchedItem = $this->createMock( WatchedItem::class );
		$watchedItem->expects( $this->once() )
			->method( 'getNotificationTimestamp' )
			->willReturn( 'stringTimestamp' );

		$watchedItemStore = $this->createMock( WatchedItemStoreInterface::class );
		$watchedItemStore->expects( $this->once() )
			->method( 'getWatchedItem' )
			->with( $userIdentity, $title )
			->willReturn( $watchedItem );

		$manager = $this->getManager( [
			'readOnly' => 'never',
			'watchedItemStore' => $watchedItemStore
		] );

		$res = $manager->getTitleNotificationTimestamp( $userIdentity, $title );

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
		$res = $manager->getTitleNotificationTimestamp( $userIdentity, $title );
		$this->assertSame(
			'stringTimestamp',
			$res,
			'if the timestamp is cached getWatchedItem is not called again'
		);
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetTitleNotificationTimestamp_null( $testPageFactory ) {
		// ********** Code path #4 **********
		// Actually check watchedItemStore, v.1-b - returns a WatchedItem with null

		$userIdentity = new UserIdentityValue( 100, 'User Name' );

		$title = $testPageFactory( 100, NS_MAIN, 'Page_db_Key_goesHere' );

		$watchedItem = $this->createMock( WatchedItem::class );
		$watchedItem->expects( $this->once() )
			->method( 'getNotificationTimestamp' )
			->willReturn( null );

		$watchedItemStore = $this->createMock( WatchedItemStoreInterface::class );
		$watchedItemStore->expects( $this->once() )
			->method( 'getWatchedItem' )
			->with( $userIdentity, $title )
			->willReturn( $watchedItem );

		$manager = $this->getManager( [
			'readOnly' => 'never',
			'watchedItemStore' => $watchedItemStore
		] );

		$res = $manager->getTitleNotificationTimestamp( $userIdentity, $title );

		$this->assertNull( $res, 'WatchedItem can return null instead of a timestamp' );
	}

	/**
	 * @dataProvider provideTestPageFactory
	 */
	public function testGetTitleNotificationTimestamp_false( $testPageFactory ) {
		// ********** Code path #5 **********
		// Actually check watchedItemStore, v.2 - returns false

		$userIdentity = new UserIdentityValue( 100, 'User Name' );

		$title = $testPageFactory( 100, NS_MAIN, 'Page_db_Key_goesHere' );

		$watchedItemStore = $this->createMock( WatchedItemStoreInterface::class );
		$watchedItemStore->expects( $this->once() )
			->method( 'getWatchedItem' )
			->with( $userIdentity, $title )
			->willReturn( false );

		$manager = $this->getManager( [
			'readOnly' => 'never',
			'watchedItemStore' => $watchedItemStore
		] );

		$res = $manager->getTitleNotificationTimestamp( $userIdentity, $title );

		$this->assertFalse(
			$res,
			'getWatchedItem can return false if the item is not watched'
		);
	}

	public function testIsWatchable() {
		$manager = $this->getManager( [ 'readOnly' => 'never' ] );

		$target = new PageReferenceValue( NS_USER, __METHOD__, PageReference::LOCAL );
		$this->assertTrue( $manager->isWatchable( $target ) );
	}

	public function provideNotIsWatchable() {
		yield [ new PageReferenceValue( NS_SPECIAL, 'Contributions', PageReference::LOCAL ) ];
		yield [ Title::makeTitle( NS_MAIN, '', 'References' ) ];
		yield [ Title::makeTitle( NS_MAIN, 'Foo', '', 'acme' ) ];
	}

	/**
	 * @dataProvider provideNotIsWatchable
	 */
	public function testNotIsWatchable( $target ) {
		$manager = $this->getManager( [ 'readOnly' => 'never' ] );

		$this->assertFalse( $manager->isWatchable( $target ) );
	}
}
