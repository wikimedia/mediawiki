<?php

/**
 * @author Addshore
 *
 * @group Database
 *
 * @covers WatchedItem
 */
class WatchedItemIntegrationTest extends MediaWikiTestCase {

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
		// Cleanup after previous tests
		WatchedItem::fromUserTitle( $user, $title )->removeWatch();

		$this->assertFalse(
			WatchedItem::fromUserTitle( $user, $title )->isWatched(),
			'Page should not initially be watched'
		);
		WatchedItem::fromUserTitle( $user, $title )->addWatch();
		$this->assertTrue(
			WatchedItem::fromUserTitle( $user, $title )->isWatched(),
			'Page should be watched'
		);
		WatchedItem::fromUserTitle( $user, $title )->removeWatch();
		$this->assertFalse(
			WatchedItem::fromUserTitle( $user, $title )->isWatched(),
			'Page should be unwatched'
		);
	}

	public function testUpdateAndResetNotificationTimestamp() {
		$user = $this->getUser();
		$otherUser = ( new TestUser( 'WatchedItemStoreIntegrationTestUser_otherUser' ) )->getUser();
		$title = Title::newFromText( 'WatchedItemStoreIntegrationTestPage' );
		WatchedItem::fromUserTitle( $user, $title )->addWatch();
		$this->assertNull( WatchedItem::fromUserTitle( $user, $title )->getNotificationTimestamp() );

		EmailNotification::updateWatchlistTimestamp( $otherUser, $title, '20150202010101' );
		$this->assertEquals(
			'20150202010101',
			WatchedItem::fromUserTitle( $user, $title )->getNotificationTimestamp()
		);

		WatchedItem::fromUserTitle( $user, $title )->resetNotificationTimestamp();
		$this->assertNull( WatchedItem::fromUserTitle( $user, $title )->getNotificationTimestamp() );
	}

	public function testDuplicateAllAssociatedEntries() {
		$user = $this->getUser();
		$titleOld = Title::newFromText( 'WatchedItemStoreIntegrationTestPageOld' );
		$titleNew = Title::newFromText( 'WatchedItemStoreIntegrationTestPageNew' );
		WatchedItem::fromUserTitle( $user, $titleOld->getSubjectPage() )->addWatch();
		WatchedItem::fromUserTitle( $user, $titleOld->getTalkPage() )->addWatch();
		// Cleanup after previous tests
		WatchedItem::fromUserTitle( $user, $titleNew->getSubjectPage() )->removeWatch();
		WatchedItem::fromUserTitle( $user, $titleNew->getTalkPage() )->removeWatch();

		WatchedItem::duplicateEntries( $titleOld, $titleNew );

		$this->assertTrue(
			WatchedItem::fromUserTitle( $user, $titleOld->getSubjectPage() )->isWatched()
		);
		$this->assertTrue(
			WatchedItem::fromUserTitle( $user, $titleOld->getTalkPage() )->isWatched()
		);
		$this->assertTrue(
			WatchedItem::fromUserTitle( $user, $titleNew->getSubjectPage() )->isWatched()
		);
		$this->assertTrue(
			WatchedItem::fromUserTitle( $user, $titleNew->getTalkPage() )->isWatched()
		);
	}

}
