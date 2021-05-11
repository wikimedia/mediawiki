<?php

use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \MediaWiki\User\UserEditTracker
 * @group Database
 */
class UserEditTrackerTest extends MediaWikiIntegrationTestCase {
	/**
	 * Do an edit
	 *
	 * @param UserIdentity $user
	 * @param string $timestamp
	 * @param bool $create
	 */
	private function editTrackerDoEdit( $user, $timestamp, $create ) {
		$title = Title::newFromText( __FUNCTION__ );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		if ( $create ) {
			$page->insertOn( $this->db );
		}

		$rev = new MutableRevisionRecord( $title );
		$rev->setContent( SlotRecord::MAIN, new WikitextContent( $timestamp ) );
		$rev->setComment( CommentStoreComment::newUnsavedComment( '' ) );
		$rev->setTimestamp( $timestamp );
		$rev->setUser( $user );
		$rev->setPageId( $page->getId() );
		$this->getServiceContainer()->getRevisionStore()->insertRevisionOn( $rev, $this->db );
	}

	/**
	 * Change the user_editcount field in the DB
	 *
	 * @param UserIdentity $user
	 * @param int|null $count
	 */
	private function setDbEditCount( $user, $count ) {
		$this->db->update(
			'user',
			[ 'user_editcount' => $count ],
			[ 'user_id' => $user->getId() ],
			__METHOD__ );
	}

	public function testGetUserEditCount() {
		// Set user_editcount to 5
		$user = $this->getMutableTestUser()->getUser();
		$update = new UserEditCountUpdate( $user, 5 );
		$update->doUpdate();

		$tracker = $this->getServiceContainer()->getUserEditTracker();
		$this->assertSame( 5, $tracker->getUserEditCount( $user ) );

		// Now fetch from cache
		$this->assertSame( 5, $tracker->getUserEditCount( $user ) );
	}

	public function testGetUserEditCount_exception() {
		// getUserEditCount throws if the user id is falsy
		$userId = 0;
		$user = new UserIdentityValue( $userId, __CLASS__ );
		$tracker = $this->getServiceContainer()->getUserEditTracker();
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'requires a user ID' );
		$tracker->getUserEditCount( $user );
	}

	public function testGetUserEditCount_null() {
		// getUserEditCount doesn't find a value in user_editcount and calls
		// initializeUserEditCount
		$user = $this->getMutableTestUser()->getUserIdentity();
		$this->setDbEditCount( $user, null );
		$tracker = $this->getServiceContainer()->getUserEditTracker();
		$this->assertSame( 0, $tracker->getUserEditCount( $user ) );
	}

	public function testInitializeUserEditCount() {
		$user = $this->getMutableTestUser()->getUser();
		$this->editTrackerDoEdit( $user, '20200101000000', true );
		$tracker = $this->getServiceContainer()->getUserEditTracker();
		$tracker->initializeUserEditCount( $user );
		$this->runJobs();
		$this->assertSame( 1, $tracker->getUserEditCount( $user ) );
	}

	public function testGetEditTimestamp() {
		$user = $this->getMutableTestUser()->getUser();
		$tracker = $this->getServiceContainer()->getUserEditTracker();
		$this->assertFalse( $tracker->getFirstEditTimestamp( $user ) );
		$this->assertFalse( $tracker->getLatestEditTimestamp( $user ) );

		$ts1 = '20010101000000';
		$ts2 = '20020101000000';
		$ts3 = '20030101000000';
		$this->editTrackerDoEdit( $user, $ts3, false );
		$this->editTrackerDoEdit( $user, $ts2, false );
		$this->editTrackerDoEdit( $user, $ts1, true );

		$this->assertSame( $ts1, $tracker->getFirstEditTimestamp( $user ) );
		$this->assertSame( $ts3, $tracker->getLatestEditTimestamp( $user ) );
	}

	public function testGetEditTimestamp_anon() {
		$user = $this->getServiceContainer()->getUserFactory()
			->newFromName( '127.0.0.1', UserFactory::RIGOR_NONE );
		$tracker = $this->getServiceContainer()->getUserEditTracker();
		$this->editTrackerDoEdit( $user, '20200101000000', true );
		$this->assertFalse( $tracker->getFirstEditTimestamp( $user ) );
		$this->assertFalse( $tracker->getLatestEditTimestamp( $user ) );
	}

	public function testClearUserEditCache() {
		$user = $this->getMutableTestUser()->getUser();
		$tracker = $this->getServiceContainer()->getUserEditTracker();
		$this->assertSame( 0, $tracker->getUserEditCount( $user ) );
		$this->setDbEditCount( $user, 1 );
		$this->assertSame( 0, $tracker->getUserEditCount( $user ) );
		$tracker->clearUserEditCache( $user );
		$this->assertSame( 1, $tracker->getUserEditCount( $user ) );
	}

}
