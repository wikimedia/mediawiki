<?php

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
		$store = WatchedItemStore::getDefaultInstance();
		// Cleanup after previous tests
		$store->removeWatch( $user, $title );

		$this->assertFalse(
			$store->isWatched( $user, $title ),
			'Page should not initially be watched'
		);
		$store->addWatch( $user, $title );
		$this->assertTrue(
			$store->isWatched( $user, $title ),
			'Page should be watched'
		);
		$store->removeWatch( $user, $title );
		$this->assertFalse(
			$store->isWatched( $user, $title ),
			'Page should be unwatched'
		);
	}

	public function testUpdateAndResetNotificationTimestamp() {
		$user = $this->getUser();
		$otherUser = ( new TestUser( 'WatchedItemStoreIntegrationTestUser_otherUser' ) )->getUser();
		$title = Title::newFromText( 'WatchedItemStoreIntegrationTestPage' );
		$store = WatchedItemStore::getDefaultInstance();
		$store->addWatch( $user, $title );
		$this->assertNull( $store->loadWatchedItem( $user, $title )->getNotificationTimestamp() );

		$store->updateNotificationTimestamp( $otherUser, $title, '20150202010101' );
		$this->assertEquals(
			'20150202010101',
			$store->loadWatchedItem( $user, $title )->getNotificationTimestamp()
		);

		$this->assertTrue( $store->resetNotificationTimestamp( $user, $title ) );
		$this->assertNull( $store->loadWatchedItem( $user, $title )->getNotificationTimestamp() );
	}

	public function testDuplicateAllAssociatedEntries() {
		$user = $this->getUser();
		$titleOld = Title::newFromText( 'WatchedItemStoreIntegrationTestPageOld' );
		$titleNew = Title::newFromText( 'WatchedItemStoreIntegrationTestPageNew' );
		$store = WatchedItemStore::getDefaultInstance();
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
