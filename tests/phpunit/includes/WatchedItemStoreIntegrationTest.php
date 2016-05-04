<?php

use MediaWiki\MediaWikiServices;

/**
 * @author Addshore
 *
 * @group Database
 *
 * @covers WatchedItemStore
 */
class WatchedItemStoreIntegrationTest extends MediaWikiTestCase {

	public function setUp() {
		parent::setUp();
		self::$users['WatchedItemStoreIntegrationTestUser']
			= new TestUser( 'WatchedItemStoreIntegrationTestUser' );
	}

	private function getUser() {
		return self::$users['WatchedItemStoreIntegrationTestUser']->getUser();
	}

	public function testWatchAndUnWatchItem() {
		$user = $this->getUser();
		$title = Title::newFromText( 'WatchedItemStoreIntegrationTestPage' );
		$store = MediaWikiServices::getInstance()->getWatchedItemStore();
		// Cleanup after previous tests
		$store->removeWatch( $user, $title );
		$initialWatchers = $store->countWatchers( $title );
		$initialUserWatchedItems = $store->countWatchedItems( $user );

		$this->assertFalse(
			$store->isWatched( $user, $title ),
			'Page should not initially be watched'
		);

		$store->addWatch( $user, $title );
		$this->assertTrue(
			$store->isWatched( $user, $title ),
			'Page should be watched'
		);
		$this->assertEquals( $initialUserWatchedItems + 1, $store->countWatchedItems( $user ) );
		$watchedItemsForUser = $store->getWatchedItemsForUser( $user );
		$this->assertCount( $initialUserWatchedItems + 1, $watchedItemsForUser );
		$watchedItemsForUserHasExpectedItem = false;
		foreach ( $watchedItemsForUser as $watchedItem ) {
			if (
				$watchedItem->getUser()->equals( $user ) &&
				$watchedItem->getLinkTarget() == $title->getTitleValue()
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
				$watchedItem->getUser()->equals( $user ) &&
				$watchedItem->getLinkTarget() == $title->getTitleValue()
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

	public function testUpdateResetAndSetNotificationTimestamp() {
		$user = $this->getUser();
		$otherUser = ( new TestUser( 'WatchedItemStoreIntegrationTestUser_otherUser' ) )->getUser();
		$title = Title::newFromText( 'WatchedItemStoreIntegrationTestPage' );
		$store = MediaWikiServices::getInstance()->getWatchedItemStore();
		$store->addWatch( $user, $title );
		$this->assertNull( $store->loadWatchedItem( $user, $title )->getNotificationTimestamp() );
		$initialVisitingWatchers = $store->countVisitingWatchers( $title, '20150202020202' );
		$initialUnreadNotifications = $store->countUnreadNotifications( $user );

		$store->updateNotificationTimestamp( $otherUser, $title, '20150202010101' );
		$this->assertEquals(
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
			$store->setNotificationTimestampsForUser( $user, '20200202020202', [ $title ] )
		);
		$this->assertEquals(
			'20200202020202',
			$store->getWatchedItem( $user, $title )->getNotificationTimestamp()
		);

		// setNotificationTimestampsForUser not specifying a title
		$this->assertTrue(
			$store->setNotificationTimestampsForUser( $user, '20210202020202' )
		);
		$this->assertEquals(
			'20210202020202',
			$store->getWatchedItem( $user, $title )->getNotificationTimestamp()
		);
	}

	public function testDuplicateAllAssociatedEntries() {
		$user = $this->getUser();
		$titleOld = Title::newFromText( 'WatchedItemStoreIntegrationTestPageOld' );
		$titleNew = Title::newFromText( 'WatchedItemStoreIntegrationTestPageNew' );
		$store = MediaWikiServices::getInstance()->getWatchedItemStore();
		$store->addWatch( $user, $titleOld->getSubjectPage() );
		$store->addWatch( $user, $titleOld->getTalkPage() );
		// Cleanup after previous tests
		$store->removeWatch( $user, $titleNew->getSubjectPage() );
		$store->removeWatch( $user, $titleNew->getTalkPage() );

		$store->duplicateAllAssociatedEntries( $titleOld, $titleNew );

		$this->assertTrue( $store->isWatched( $user, $titleOld->getSubjectPage() ) );
		$this->assertTrue( $store->isWatched( $user, $titleOld->getTalkPage() ) );
		$this->assertTrue( $store->isWatched( $user, $titleNew->getSubjectPage() ) );
		$this->assertTrue( $store->isWatched( $user, $titleNew->getTalkPage() ) );
	}

}
