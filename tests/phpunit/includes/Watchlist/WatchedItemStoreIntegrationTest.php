<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @author Addshore
 *
 * @group Database
 *
 * @covers \MediaWiki\Watchlist\WatchedItemStore
 */
class WatchedItemStoreIntegrationTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValues( [
			MainConfigNames::WatchlistExpiry => true,
			MainConfigNames::WatchlistExpiryMaxDuration => '6 months',
		] );
	}

	private function getUser(): UserIdentity {
		return new UserIdentityValue( 42, 'WatchedItemStoreIntegrationTestUser' );
	}

	public function testWatchAndUnWatchItem() {
		$user = $this->getUser();
		$title = Title::makeTitle( NS_MAIN, 'WatchedItemStoreIntegrationTestPage' );
		$store = $this->getServiceContainer()->getWatchedItemStore();
		// Cleanup after previous tests
		$store->removeWatch( $user, $title );
		$initialWatchers = $store->countWatchers( $title );
		$initialUserWatchedItems = $store->countWatchedItems( $user );

		$this->assertFalse(
			$store->isWatched( $user, $title ),
			'Page should not initially be watched'
		);
		$this->assertFalse( $store->isTempWatched( $user, $title ) );

		$store->addWatch( $user, $title );
		$this->assertTrue(
			$store->isWatched( $user, $title ),
			'Page should be watched'
		);
		$this->assertFalse(
			$store->isTempWatched( $user, $title ),
			'Page should not be temporarily watched'
		);
		$this->assertEquals( $initialUserWatchedItems + 1, $store->countWatchedItems( $user ) );
		$watchedItemsForUser = $store->getWatchedItemsForUser( $user );
		$this->assertCount( $initialUserWatchedItems + 1, $watchedItemsForUser );
		$watchedItemsForUserHasExpectedItem = false;
		foreach ( $watchedItemsForUser as $watchedItem ) {
			if (
				$watchedItem->getUserIdentity()->equals( $user ) &&
				$watchedItem->getTarget()->isSamePageAs( $title )
			) {
				$watchedItemsForUserHasExpectedItem = true;
			}
		}
		$this->assertTrue(
			$watchedItemsForUserHasExpectedItem,
			'getWatchedItemsForUser should contain the page'
		);
		$this->assertEquals( $initialWatchers + 1, $store->countWatchers( $title ) );
		$this->assertEquals(
			$initialWatchers + 1,
			$store->countWatchersMultiple( [ $title ] )[$title->getNamespace()][$title->getDBkey()]
		);
		$this->assertEquals(
			[ 0 => [ 'WatchedItemStoreIntegrationTestPage' => $initialWatchers + 1 ] ],
			$store->countWatchersMultiple( [ $title ], [ 'minimumWatchers' => $initialWatchers + 1 ] )
		);
		$this->assertEquals(
			[ 0 => [ 'WatchedItemStoreIntegrationTestPage' => 0 ] ],
			$store->countWatchersMultiple( [ $title ], [ 'minimumWatchers' => $initialWatchers + 2 ] )
		);
		$this->assertEquals(
			[ $title->getNamespace() => [ $title->getDBkey() => null ] ],
			$store->getNotificationTimestampsBatch( $user, [ $title ] )
		);

		$store->removeWatch( $user, $title );
		$this->assertFalse(
			$store->isWatched( $user, $title ),
			'Page should be unwatched'
		);
		$this->assertEquals( $initialUserWatchedItems, $store->countWatchedItems( $user ) );
		$watchedItemsForUser = $store->getWatchedItemsForUser( $user );
		$this->assertCount( $initialUserWatchedItems, $watchedItemsForUser );
		$watchedItemsForUserHasExpectedItem = false;
		foreach ( $watchedItemsForUser as $watchedItem ) {
			if (
				$watchedItem->getUserIdentity()->equals( $user ) &&
				$watchedItem->getTarget()->isSamePageAs( $title )
			) {
				$watchedItemsForUserHasExpectedItem = true;
			}
		}
		$this->assertFalse(
			$watchedItemsForUserHasExpectedItem,
			'getWatchedItemsForUser should not contain the page'
		);
		$this->assertEquals( $initialWatchers, $store->countWatchers( $title ) );
		$this->assertEquals(
			$initialWatchers,
			$store->countWatchersMultiple( [ $title ] )[$title->getNamespace()][$title->getDBkey()]
		);
		$this->assertEquals(
			[ $title->getNamespace() => [ $title->getDBkey() => false ] ],
			$store->getNotificationTimestampsBatch( $user, [ $title ] )
		);
	}

	public function testWatchItemWithExpiryWhenDisabled(): void {
		// Disable watchlist expiry.
		$this->overrideConfigValues( [ MainConfigNames::WatchlistExpiry => false ] );

		$user = $this->getUser();
		$title = Title::makeTitle( NS_MAIN, 'WatchedItemStoreIntegrationTestPage' );
		$store = $this->getServiceContainer()->getWatchedItemStore();

		// Attempt to add a watch with an expiry.
		$store->addWatch( $user, $title, '1 week' );
		// Fetch from the process cache.
		$watchedItem = $store->getWatchedItem( $user, $title );
		// The expiry should be null since the feature is disabled.
		$this->assertNull( $watchedItem->getExpiry() );
		// The item should still be watched.
		$this->assertTrue( $store->isWatched( $user, $title ) );
		$this->assertFalse( $store->isTempWatched( $user, $title ) );
	}

	public function testWatchAndUnWatchItemWithExpiry(): void {
		$user = $this->getUser();
		$title = Title::makeTitle( NS_MAIN, 'WatchedItemStoreIntegrationTestPage' );
		$store = $this->getServiceContainer()->getWatchedItemStore();
		$initialUserWatchedItems = $store->countWatchedItems( $user );

		// Watch for a duration greater than the max ($wgWatchlistExpiryMaxDuration),
		// which should get changed to the max.
		$expiry = wfTimestamp( TS_MW, strtotime( '10 years' ) );
		$store->addWatch( $user, $title, $expiry );
		$this->assertLessThanOrEqual(
			wfTimestamp( TS_MW, strtotime( '6 months' ) ),
			$store->loadWatchedItem( $user, $title )->getExpiry()
		);

		// Valid expiry that's less than the max.
		$expiry = wfTimestamp( TS_MW, strtotime( '1 week' ) );

		$store->addWatch( $user, $title, $expiry );
		$this->assertSame(
			$expiry,
			$store->loadWatchedItem( $user, $title )->getExpiry()
		);
		$this->assertEquals( $initialUserWatchedItems + 1, $store->countWatchedItems( $user ) );
		$this->assertTrue( $store->isTempWatched( $user, $title ) );

		// Invalid expiry, nothing should change.
		$exceptionThrown = false;
		try {
			$store->addWatch( $user, $title, 'invalid expiry' );
		} catch ( InvalidArgumentException ) {
			$exceptionThrown = true;
			// Asserting watchedItem getExpiry stays unchanged
			$this->assertSame(
				$expiry,
				$store->loadWatchedItem( $user, $title )->getExpiry()
			);
			$this->assertSame(
				$initialUserWatchedItems + 1,
				$store->countWatchedItems( $user )
			);
		}
		$this->assertTrue( $exceptionThrown );

		// Changed to infinity, so expiry row should be removed.
		$store->addWatch( $user, $title, 'infinity' );
		$this->assertNull(
			$store->loadWatchedItem( $user, $title )->getExpiry()
		);
		$this->assertEquals( $initialUserWatchedItems + 1, $store->countWatchedItems( $user ) );
		$this->assertFalse( $store->isTempWatched( $user, $title ) );

		// Updating to a valid expiry.
		$store->addWatch( $user, $title, '1 month' );
		$this->assertLessThanOrEqual(
			strtotime( '1 month' ),
			wfTimestamp(
				TS_UNIX,
				$store->loadWatchedItem( $user, $title )->getExpiry()
			)
		);
		$this->assertEquals( $initialUserWatchedItems + 1, $store->countWatchedItems( $user ) );

		// Expiry in the past, should not be considered watched.
		$store->addWatch( $user, $title, '20090101000000' );
		$this->assertEquals( $initialUserWatchedItems, $store->countWatchedItems( $user ) );

		// Test isWatch(), which would normally pull from the cache. In this case
		// the cache should bust and return false since the item has expired.
		$this->assertFalse( $store->isWatched( $user, $title ) );
		$this->assertFalse( $store->isTempWatched( $user, $title ) );
	}

	public function testWatchAndUnwatchMultipleWithExpiry(): void {
		$user = $this->getUser();
		$title1 = Title::makeTitle( NS_MAIN, 'WatchedItemStoreIntegrationTestPage1' );
		$title2 = Title::makeTitle( NS_MAIN, 'WatchedItemStoreIntegrationTestPage1' );
		$store = $this->getServiceContainer()->getWatchedItemStore();

		// Use a relative timestamp in the near future to ensure we don't exceed the max.
		// See testWatchAndUnWatchItemWithExpiry() for tests regarding the max duration.
		$timestamp = wfTimestamp( TS_MW, strtotime( '1 week' ) );
		$store->addWatchBatchForUser( $user, [ $title1, $title2 ], $timestamp );

		$this->assertSame(
			$timestamp,
			$store->loadWatchedItem( $user, $title1 )->getExpiry()
		);
		$this->assertSame(
			$timestamp,
			$store->loadWatchedItem( $user, $title2 )->getExpiry()
		);

		// Clear expiries.
		$store->addWatchBatchForUser( $user, [ $title1, $title2 ], 'infinity' );

		$this->assertNull(
			$store->loadWatchedItem( $user, $title1 )->getExpiry()
		);
		$this->assertNull(
			$store->loadWatchedItem( $user, $title2 )->getExpiry()
		);
	}

	public function testWatchBatchAndClearItems() {
		$user = $this->getUser();
		$title1 = Title::makeTitle( NS_MAIN, 'WatchedItemStoreIntegrationTestPage1' );
		$title2 = Title::makeTitle( NS_MAIN, 'WatchedItemStoreIntegrationTestPage2' );
		$store = $this->getServiceContainer()->getWatchedItemStore();

		$store->addWatchBatchForUser( $user, [ $title1, $title2 ] );

		$this->assertTrue( $store->isWatched( $user, $title1 ) );
		$this->assertTrue( $store->isWatched( $user, $title2 ) );

		$store->clearUserWatchedItems( $user );

		$this->assertFalse( $store->isWatched( $user, $title1 ) );
		$this->assertFalse( $store->isWatched( $user, $title2 ) );
	}

	public function testUpdateResetAndSetNotificationTimestamp() {
		$user = $this->getUser();
		$otherUser = new UserIdentityValue(
			$user->getId() + 1,
			$user->getName() . '_other'
		);
		$title = Title::makeTitle( NS_MAIN, 'WatchedItemStoreIntegrationTestPage' );
		$store = $this->getServiceContainer()->getWatchedItemStore();
		$store->addWatch( $user, $title );
		$this->assertNull( $store->loadWatchedItem( $user, $title )->getNotificationTimestamp() );
		$initialVisitingWatchers = $store->countVisitingWatchers( $title, '20150202020202' );
		$initialUnreadNotifications = $store->countUnreadNotifications( $user );

		$store->updateNotificationTimestamp( $otherUser, $title, '20150202010101' );
		$this->assertSame(
			'20150202010101',
			$store->loadWatchedItem( $user, $title )->getNotificationTimestamp()
		);
		$this->assertEquals(
			[ $title->getNamespace() => [ $title->getDBkey() => '20150202010101' ] ],
			$store->getNotificationTimestampsBatch( $user, [ $title ] )
		);
		$this->assertEquals(
			$initialVisitingWatchers - 1,
			$store->countVisitingWatchers( $title, '20150202020202' )
		);
		$this->assertEquals(
			$initialVisitingWatchers - 1,
			$store->countVisitingWatchersMultiple(
				[ [ $title, '20150202020202' ] ]
			)[$title->getNamespace()][$title->getDBkey()]
		);
		$this->assertEquals(
			$initialUnreadNotifications + 1,
			$store->countUnreadNotifications( $user )
		);
		$this->assertSame(
			true,
			$store->countUnreadNotifications( $user, $initialUnreadNotifications + 1 )
		);

		$this->assertTrue( $store->resetNotificationTimestamp( $user, $title ) );
		$this->assertNull( $store->getWatchedItem( $user, $title )->getNotificationTimestamp() );
		$this->assertEquals(
			[ $title->getNamespace() => [ $title->getDBkey() => null ] ],
			$store->getNotificationTimestampsBatch( $user, [ $title ] )
		);

		// Run the job queue
		$this->runJobs();

		$this->assertEquals(
			$initialVisitingWatchers,
			$store->countVisitingWatchers( $title, '20150202020202' )
		);
		$this->assertEquals(
			$initialVisitingWatchers,
			$store->countVisitingWatchersMultiple(
				[ [ $title, '20150202020202' ] ]
			)[$title->getNamespace()][$title->getDBkey()]
		);
		$this->assertEquals(
			[ 0 => [ 'WatchedItemStoreIntegrationTestPage' => $initialVisitingWatchers ] ],
			$store->countVisitingWatchersMultiple(
				[ [ $title, '20150202020202' ] ], $initialVisitingWatchers
			)
		);
		$this->assertEquals(
			[ 0 => [ 'WatchedItemStoreIntegrationTestPage' => 0 ] ],
			$store->countVisitingWatchersMultiple(
				[ [ $title, '20150202020202' ] ], $initialVisitingWatchers + 1
			)
		);

		// setNotificationTimestampsForUser specifying a title
		$this->assertTrue(
			$store->setNotificationTimestampsForUser( $user, '20100202020202', [ $title ] )
		);
		$this->assertSame(
			'20100202020202',
			$store->getWatchedItem( $user, $title )->getNotificationTimestamp()
		);

		// setNotificationTimestampsForUser not specifying a title
		// This will try to use a DeferredUpdate; disable that
		$mockCallback = static function ( $callback ) {
			$callback();
		};
		$scopedOverride = $store->overrideDeferredUpdatesAddCallableUpdateCallback( $mockCallback );
		$this->assertTrue(
			$store->setNotificationTimestampsForUser( $user, '20110202020202' )
		);
		// Because the operation above is normally deferred, it doesn't clear the cache
		// Clear the cache manually
		$wrappedStore = TestingAccessWrapper::newFromObject( $store );
		$wrappedStore->uncacheUser( $user );
		$this->assertSame(
			'20110202020202',
			$store->getWatchedItem( $user, $title )->getNotificationTimestamp()
		);
	}

	public function testDuplicateAllAssociatedEntries() {
		// Fake current time to be 2020-05-27T00:00:00Z
		ConvertibleTimestamp::setFakeTime( '20200527000000' );

		$user = $this->getUser();
		$titleOld = Title::makeTitle( NS_MAIN, 'WatchedItemStoreIntegrationTestPageOld' );
		$titleNew = Title::makeTitle( NS_MAIN, 'WatchedItemStoreIntegrationTestPageNew' );
		$store = $this->getServiceContainer()->getWatchedItemStore();
		$store->addWatch( $user, $titleOld->getSubjectPage(), '99990123000000' );
		$store->addWatch( $user, $titleOld->getTalkPage(), '99990123000000' );

		// Fetch stored expiry (may have changed due to wgWatchlistExpiryMaxDuration).
		// Note we use loadWatchedItem() instead of getWatchedItem() to bypass the process cache.
		$expectedExpiry = $store->loadWatchedItem( $user, $titleOld )->getExpiry();

		// Watch the new title with a different expiry, so that we can confirm
		// it gets replaced with the old title's expiry.
		$store->addWatch( $user, $titleNew->getSubjectPage(), '1 day' );
		$store->addWatch( $user, $titleNew->getTalkPage(), '1 day' );

		// Use the sysop test user as well on the old title, so we can test that
		// each user's respective expiry is correctly copied.
		$user2 = $this->getTestSysop()->getUser();
		$store->addWatch( $user2, $titleOld->getSubjectPage(), '1 week' );
		$store->addWatch( $user2, $titleOld->getTalkPage(), '1 week' );
		$expectedExpiry2 = $store->loadWatchedItem( $user2, $titleOld )->getExpiry();

		// Duplicate associated entries. This will try to use a DeferredUpdate; disable that.
		$mockCallback = static function ( $callback ) {
			$callback();
		};
		$store->overrideDeferredUpdatesAddCallableUpdateCallback( $mockCallback );
		$store->duplicateAllAssociatedEntries( $titleOld, $titleNew );

		$this->assertTrue( $store->isWatched( $user, $titleOld->getSubjectPage() ) );
		$this->assertTrue( $store->isWatched( $user, $titleOld->getTalkPage() ) );
		$this->assertTrue( $store->isWatched( $user, $titleNew->getSubjectPage() ) );
		$this->assertTrue( $store->isWatched( $user, $titleNew->getTalkPage() ) );

		$oldExpiry = $store->loadWatchedItem( $user, $titleOld )->getExpiry();
		$newExpiry = $store->loadWatchedItem( $user, $titleNew )->getExpiry();
		$this->assertSame( $expectedExpiry, $oldExpiry );
		$this->assertSame( $expectedExpiry, $newExpiry );

		// Same for $user2 and $expectedExpiry2
		$oldExpiry = $store->loadWatchedItem( $user2, $titleOld )->getExpiry();
		$newExpiry = $store->loadWatchedItem( $user2, $titleNew )->getExpiry();
		$this->assertSame( $expectedExpiry2, $oldExpiry );
		$this->assertSame( $expectedExpiry2, $newExpiry );
	}

	public function testRemoveExpired() {
		$store = $this->getServiceContainer()->getWatchedItemStore();

		// Clear out any expired rows, to start from a known point.
		$store->removeExpired( 10 );
		$this->assertSame( 0, $store->countExpired() );

		// Add three pages, two of which have already expired.
		$user = $this->getUser();
		$store->addWatch( $user, Title::makeTitle( NS_MAIN, 'P1' ), '2020-01-25' );
		$store->addWatch( $user, Title::makeTitle( NS_MAIN, 'P2' ), '20200101000000' );
		$store->addWatch( $user, Title::makeTitle( NS_MAIN, 'P3' ), '1 month' );

		// Test that they can be counted and removed correctly.
		$this->assertSame( 2, $store->countExpired() );
		$store->removeExpired( 1 );
		$this->assertSame( 1, $store->countExpired() );
	}

	public function testRemoveOrphanedExpired() {
		$store = $this->getServiceContainer()->getWatchedItemStore();
		// Clear out any expired rows, to start from a known point.
		$store->removeExpired( 10 );

		// Manually insert some orphaned non-expired rows.
		$orphanRows = [
			[ 'we_item' => '100000', 'we_expiry' => $this->getDb()->timestamp( '30300101000000' ) ],
			[ 'we_item' => '100001', 'we_expiry' => $this->getDb()->timestamp( '30300101000000' ) ],
		];
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'watchlist_expiry' )
			->rows( $orphanRows )
			->caller( __METHOD__ )
			->execute();
		$initialRowCount = $this->getDb()->newSelectQueryBuilder()
			->select( '*' )
			->from( 'watchlist_expiry' )
			->caller( __METHOD__ )->fetchRowCount();

		// Make sure the orphans aren't removed if it's not requested.
		$store->removeExpired( 10, false );
		$this->assertSame(
			$initialRowCount,
			$this->getDb()->newSelectQueryBuilder()
				->select( '*' )
				->from( 'watchlist_expiry' )
				->caller( __METHOD__ )->fetchRowCount()
		);

		// Make sure they are removed when requested.
		$store->removeExpired( 10, true );
		$this->assertSame(
			$initialRowCount - 2,
			$this->getDb()->newSelectQueryBuilder()
				->select( '*' )
				->from( 'watchlist_expiry' )
				->caller( __METHOD__ )->fetchRowCount()
		);
	}
}
