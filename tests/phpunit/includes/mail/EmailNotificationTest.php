<?php

use MediaWiki\MainConfigNames;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Title\Title;

/**
 * @group Database
 * @group Mail
 * @covers \EmailNotification
 */
class EmailNotificationTest extends MediaWikiIntegrationTestCase {

	/** @var EmailNotification */
	protected $emailNotification;

	protected function setUp(): void {
		parent::setUp();

		$this->emailNotification = new EmailNotification();

		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
	}

	public function testNotifyOnPageChange(): void {
		$store = $this->getServiceContainer()->getWatchedItemStore();

		// both Alice and Bob watch 'Foobar'
		$title = Title::makeTitle( NS_MAIN, 'Foobar' );
		$alice = $this->getTestSysop()->getUser();
		$store->addWatch( $alice, $title );
		$bob = $this->getTestUser()->getUser();
		$store->addWatch( $bob, $title );

		// Alice edits the page (doesn't actually have to edit in this test).
		// Bob (as in, not Alice) should have received an email notification.
		$row = (object)[
			'rc_timestamp' => '20200624000000',
			'rc_comment' => '',
			'rc_minor' => false,
			'rc_title' => $title->getDBkey(),
			'rc_namespace' => $title->getNamespace(),
			'rc_deleted' => false,
			'rc_last_oldid' => false,
			'rc_user' => $alice->getId(),
		];
		$rc = @RecentChange::newFromRow( $row );

		$sent = $this->emailNotification->notifyOnPageChange( $rc );
		static::assertTrue( $sent );

		// Alice edits again, but Bob shouldn't be notified again
		// (only one email until Bob visits the page again).
		$sent = $this->emailNotification->notifyOnPageChange( $rc );
		static::assertFalse( $sent );

		// Reset notification timestamp, simulating that Bob visited the page.
		$store->resetAllNotificationTimestampsForUser( $bob );

		// Bob re-watches temporarily. For testing purposes we use a past expiry,
		// so an email shouldn't be sent after Alice edits the page.
		$store->addWatch( $bob, $title, '20060123000000' );

		// Alice edits again, email should not be sent.
		$sent = $this->emailNotification->notifyOnPageChange( $rc );
		static::assertFalse( $sent );
	}
}
