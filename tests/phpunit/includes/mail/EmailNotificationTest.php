<?php

use MediaWiki\MediaWikiServices;

class EmailNotificationTest extends MediaWikiIntegrationTestCase {

	protected $emailNotification;

	protected function setUp(): void {
		parent::setUp();

		$this->tablesUsed = [
			'watchlist',
			'watchlist_expiry',
		];

		$this->emailNotification = new EmailNotification();

		$this->setMwGlobals( [
			'wgWatchlistExpiry' => true,
		] );
	}

	/**
	 * @covers EmailNotification::notifyOnPageChange
	 */
	public function testNotifyOnPageChange(): void {
		$store = MediaWikiServices::getInstance()->getWatchedItemStore();

		// both Alice and Bob watch 'Foobar'
		$title = Title::newFromText( 'Foobar' );
		$alice = $this->getTestSysop()->getUser();
		$store->addWatch( $alice, $title );
		$bob = $this->getTestUser()->getUser();
		$store->addWatch( $bob, $title );

		// Alice edits the page (doesn't actually have to edit in this test).
		// Bob (as in, not Alice) should have received an email notification.
		$notifyArgs = [ $alice, $title, '20200624000000', '', false ];
		$sent = $this->emailNotification->notifyOnPageChange( ...$notifyArgs );
		static::assertTrue( $sent );

		// Alice edits again, but Bob shouldn't be notified again
		// (only one email until Bob visits the page again).
		$sent = $this->emailNotification->notifyOnPageChange( ...$notifyArgs );
		static::assertFalse( $sent );

		// Reset notification timestamp, simulating that Bob visited the page.
		$store->resetAllNotificationTimestampsForUser( $bob );

		// Bob re-watches temporarily. For testing purposes we use a past expiry,
		// so an email shouldn't be sent after Alice edits the page.
		$store->addWatch( $bob, $title, '20060123000000' );

		// Alice edits again, email should not be sent.
		$sent = $this->emailNotification->notifyOnPageChange( ...$notifyArgs );
		static::assertFalse( $sent );
	}
}
