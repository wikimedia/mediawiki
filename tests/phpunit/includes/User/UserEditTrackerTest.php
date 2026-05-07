<?php

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\UserEditCountUpdate;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\User\UserRigorOptions;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MediaWiki\User\UserEditTracker
 * @group Database
 */
class UserEditTrackerTest extends MediaWikiIntegrationTestCase {

	use TempUserTestTrait;

	/**
	 * Do an edit
	 *
	 * @param UserIdentity $user
	 * @param string $timestamp
	 */
	private function editTrackerDoEdit( $user, $timestamp ) {
		$title = Title::newFromText( __FUNCTION__ );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		if ( !$page->exists() ) {
			$page->insertOn( $this->getDb() );
		}

		$rev = MutableRevisionRecord::newFromContent( $title, new WikitextContent( $timestamp ) )
			->setComment( CommentStoreComment::newUnsavedComment( '' ) )
			->setTimestamp( $timestamp )
			->setUser( $user )
			->setPageId( $page->getId() );
		$this->getServiceContainer()->getRevisionStore()->insertRevisionOn( $rev, $this->getDb() );
	}

	/**
	 * Change the user_editcount field in the DB
	 *
	 * @param UserIdentity $user
	 * @param int|null $count
	 */
	private function setDbEditCount( $user, $count ) {
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'user' )
			->set( [ 'user_editcount' => $count ] )
			->where( [ 'user_id' => $user->getId() ] )
			->caller( __METHOD__ )
			->execute();
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

	public function testGetUserEditCount_anon() {
		// getUserEditCount returns null if the user is unregistered
		$anon = UserIdentityValue::newAnonymous( '1.2.3.4' );
		$tracker = $this->getServiceContainer()->getUserEditTracker();
		$this->assertNull( $tracker->getUserEditCount( $anon ) );
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
		$this->editTrackerDoEdit( $user, '20200101000000' );
		$tracker = $this->getServiceContainer()->getUserEditTracker();
		$tracker->initializeUserEditCount( $user );
		$tracker->clearUserEditCache( $user );
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
		$this->editTrackerDoEdit( $user, $ts3 );
		$this->editTrackerDoEdit( $user, $ts2 );
		$this->editTrackerDoEdit( $user, $ts1 );

		// Normally, this would happen naturally on submitting the edit request
		$tracker->clearUserEditCache( $user );
		$tracker->incrementUserEditCount( $user );
		$tracker->incrementUserEditCount( $user );
		$tracker->incrementUserEditCount( $user );
		$this->runDeferredUpdates();

		$this->assertSame( $ts1, $tracker->getFirstEditTimestamp( $user ) );
		$this->assertSame( $ts3, $tracker->getLatestEditTimestamp( $user ) );
	}

	public function testGetEditTimestamp_anon() {
		$this->disableAutoCreateTempUser();
		$user = $this->getServiceContainer()->getUserFactory()
			->newFromName( '127.0.0.1', UserRigorOptions::RIGOR_NONE );
		$tracker = $this->getServiceContainer()->getUserEditTracker();
		$this->editTrackerDoEdit( $user, '20200101000000' );
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

	public function testIncrementUserEditCount() {
		$tracker = $this->getServiceContainer()->getUserEditTracker();
		$user = $this->getMutableTestUser()->getUser();

		$editCountStart = $tracker->getUserEditCount( $user );

		$this->getDb()->startAtomic( __METHOD__ ); // let deferred updates queue up

		$tracker->incrementUserEditCount( $user );
		$this->assertSame(
			1,
			DeferredUpdates::pendingUpdatesCount(),
			'Update queued for registered user'
		);

		$tracker->incrementUserEditCount( UserIdentityValue::newAnonymous( '1.1.1.1' ) );
		$this->assertSame(
			1,
			DeferredUpdates::pendingUpdatesCount(),
			'No update queued for anonymous user'
		);

		$this->getDb()->endAtomic( __METHOD__ ); // run deferred updates
		$this->runDeferredUpdates();

		$this->assertSame(
			0,
			DeferredUpdates::pendingUpdatesCount(),
			'deferred updates ran'
		);

		$editCountEnd = $tracker->getUserEditCount( $user );
		$this->assertSame(
			1,
			$editCountEnd - $editCountStart,
			'Edit count was incremented'
		);
	}

	public function testManualCache() {
		// Make sure manually setting the cached value overrides the database, in case
		// User::loadFromRow() is called with a row containing user_editcount that is
		// different from the actual database value, the row takes precedence
		$user = new UserIdentityValue( 123, __METHOD__ );
		$this->setDbEditCount( $user, 5 );

		$tracker = $this->getServiceContainer()->getUserEditTracker();
		$tracker->setCachedUserEditCount( $user, 10 );
		$this->assertSame( 10, $tracker->getUserEditCount( $user ) );
	}

	public function testCachesFromDifferentWikisAreDisjoint() {
		$userLocal = new UserIdentityValue( 123, __METHOD__ );
		$userRemote = new UserIdentityValue( 123, __METHOD__, 'otherwiki' );

		$tracker = $this->getServiceContainer()->getUserEditTracker();
		$tracker->setCachedUserEditCount( $userLocal, 10 );
		$tracker->setCachedUserEditCount( $userRemote, 20 );

		$this->assertSame( 10, $tracker->getUserEditCount( $userLocal ) );
		$this->assertSame( 20, $tracker->getUserEditCount( $userRemote ) );
	}

	public function testReadsFromCacheAfterPreloadForMultipleUsers(): void {
		$user1 = $this->getMutableTestUser()->getUser();
		$user2 = $this->getMutableTestUser()->getUser();

		$this->assertTrue(
			$user1->getId() !== $user2->getId(),
			'getTestUser should provide a different user on each call'
		);

		$tracker = $this->getServiceContainer()->getUserEditTracker();

		$tracker->clearUserEditCache( $user1 );
		$tracker->clearUserEditCache( $user2 );

		// Update the user table with known values, then make the service read
		// them into the cache.
		$this->setDbEditCount( $user1, 1 );
		$this->setDbEditCount( $user2, 2 );

		$tracker->preloadUserEditCountCache( [
			$user1,
			$user2
		] );

		$this->assertSame( 1, $tracker->getUserEditCount( $user1 ) );
		$this->assertSame( 2, $tracker->getUserEditCount( $user2 ) );

		// Change the values in the DB and assert than the service still returns
		// the previously-cached values, which means it does not perform
		// additional queries.
		$this->setDbEditCount( $user1, 3 );
		$this->setDbEditCount( $user2, 4 );

		$this->assertSame( 1, $tracker->getUserEditCount( $user1 ) );
		$this->assertSame( 2, $tracker->getUserEditCount( $user2 ) );
	}

	public function testGetEditTimestamp_caching() {
		$user = $this->getMutableTestUser()->getUser();
		$tracker = $this->getServiceContainer()->getUserEditTracker();

		$mockTime = time();
		$wrappedTracker = TestingAccessWrapper::newFromObject( $tracker );
		// Mock time is passed as reference, so it's only seemingly unused later in this test
		$wrappedTracker->wanObjectCache->setMockTime( $mockTime );

		$this->assertFalse( $tracker->getFirstEditTimestamp( $user ) );

		$ts = '20010101000000';
		$this->editTrackerDoEdit( $user, $ts );

		// Normally, this would happen naturally on submitting the edit request
		$tracker->clearUserEditCache( $user );
		$tracker->incrementUserEditCount( $user );
		$this->runDeferredUpdates();

		$mockTime += 1000;
		$this->assertSame( $ts, $tracker->getFirstEditTimestamp( $user ) );

		// Delete directly from database to ensure that the result is cached
		$this->getDb()->newDeleteQueryBuilder()
			->delete( 'revision' )
			->where( '1=1' )
			->caller( __METHOD__ )
			->execute();

		$this->assertFalse( $tracker->getFirstEditTimestamp( $user, IDBAccessObject::READ_LATEST ) );
		$this->assertSame( $ts, $tracker->getFirstEditTimestamp( $user ) );

		// Ensure that cache is invalidated only if we pass matching timestamp
		$tracker->invalidateCachedFirstEditTimestamps( [
			[ $user, '20260101000000' ],
		] );
		$mockTime += 1000;
		$this->assertSame( $ts, $tracker->getFirstEditTimestamp( $user ) );

		$tracker->invalidateCachedFirstEditTimestamps( [
			[ $user, $ts ],
		] );
		$mockTime += 1000;
		$this->assertFalse( $tracker->getFirstEditTimestamp( $user ) );
	}
}
