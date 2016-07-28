<?php
use MediaWiki\MediaWikiServices;

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
		self::$users['WatchedItemIntegrationTestUser']
			= new TestUser( 'WatchedItemIntegrationTestUser' );

		$this->hideDeprecated( 'WatchedItem::fromUserTitle' );
		$this->hideDeprecated( 'WatchedItem::addWatch' );
		$this->hideDeprecated( 'WatchedItem::removeWatch' );
		$this->hideDeprecated( 'WatchedItem::isWatched' );
		$this->hideDeprecated( 'WatchedItem::duplicateEntries' );
		$this->hideDeprecated( 'WatchedItem::batchAddWatch' );
	}

	private function getUser() {
		return self::$users['WatchedItemIntegrationTestUser']->getUser();
	}

	public function testWatchAndUnWatchItem() {

		$user = $this->getUser();
		$title = Title::newFromText( 'WatchedItemIntegrationTestPage' );
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
		$otherUser = ( new TestUser( 'WatchedItemIntegrationTestUser_otherUser' ) )->getUser();
		$title = Title::newFromText( 'WatchedItemIntegrationTestPage' );
		WatchedItem::fromUserTitle( $user, $title )->addWatch();
		$this->assertNull( WatchedItem::fromUserTitle( $user, $title )->getNotificationTimestamp() );

		EmailNotification::updateWatchlistTimestamp( $otherUser, $title, '20150202010101' );
		$this->assertEquals(
			'20150202010101',
			WatchedItem::fromUserTitle( $user, $title )->getNotificationTimestamp()
		);

		MediaWikiServices::getInstance()->getWatchedItemStore()->resetNotificationTimestamp(
			$user, $title
		);
		$this->assertNull( WatchedItem::fromUserTitle( $user, $title )->getNotificationTimestamp() );
	}

	public function testDuplicateAllAssociatedEntries() {
		$user = $this->getUser();
		$titleOld = Title::newFromText( 'WatchedItemIntegrationTestPageOld' );
		$titleNew = Title::newFromText( 'WatchedItemIntegrationTestPageNew' );
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

	public function testIsWatched_falseOnNotAllowed() {
		$user = $this->getUser();
		$title = Title::newFromText( 'WatchedItemIntegrationTestPage' );
		WatchedItem::fromUserTitle( $user, $title )->addWatch();

		$this->assertTrue( WatchedItem::fromUserTitle( $user, $title )->isWatched() );
		$user->mRights = [];
		$this->assertFalse( WatchedItem::fromUserTitle( $user, $title )->isWatched() );
	}

	public function testGetNotificationTimestamp_falseOnNotAllowed() {
		$user = $this->getUser();
		$title = Title::newFromText( 'WatchedItemIntegrationTestPage' );
		WatchedItem::fromUserTitle( $user, $title )->addWatch();
		MediaWikiServices::getInstance()->getWatchedItemStore()->resetNotificationTimestamp(
			$user, $title
		);

		$this->assertEquals(
			null,
			WatchedItem::fromUserTitle( $user, $title )->getNotificationTimestamp()
		);
		$user->mRights = [];
		$this->assertFalse( WatchedItem::fromUserTitle( $user, $title )->getNotificationTimestamp() );
	}

	public function testRemoveWatch_falseOnNotAllowed() {
		$user = $this->getUser();
		$title = Title::newFromText( 'WatchedItemIntegrationTestPage' );
		WatchedItem::fromUserTitle( $user, $title )->addWatch();

		$previousRights = $user->mRights;
		$user->mRights = [];
		$this->assertFalse( WatchedItem::fromUserTitle( $user, $title )->removeWatch() );
		$user->mRights = $previousRights;
		$this->assertTrue( WatchedItem::fromUserTitle( $user, $title )->removeWatch() );
	}

	public function testGetNotificationTimestamp_falseOnNotWatched() {
		$user = $this->getUser();
		$title = Title::newFromText( 'WatchedItemIntegrationTestPage' );

		WatchedItem::fromUserTitle( $user, $title )->removeWatch();
		$this->assertFalse( WatchedItem::fromUserTitle( $user, $title )->isWatched() );

		$this->assertFalse( WatchedItem::fromUserTitle( $user, $title )->getNotificationTimestamp() );
	}

}
