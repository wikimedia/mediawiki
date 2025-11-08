<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\MainConfigNames;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\TalkPageNotificationManager;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Utils\MWTimestamp;

/**
 * @covers \MediaWiki\User\TalkPageNotificationManager
 * @group Database
 */
class TalkPageNotificationManagerTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;

	private function editUserTalk( UserIdentity $user, string $text ): RevisionRecord {
		// UserIdentity doesn't have getUserPage/getTalkPage, but we can easily recreate
		// it, and its easier than needing to depend on a full user object
		$userTalk = Title::makeTitle( NS_USER_TALK, $user->getName() );
		$status = $this->editPage(
			$userTalk,
			$text,
			'',
			NS_MAIN,
			$this->getTestSysop()->getUser()
		);
		$this->assertStatusGood( $status, 'create revision of user talk' );
		return $status->getNewRevision();
	}

	private function getManager(
		bool $disableAnonTalk = false,
		bool $isReadOnly = false,
		?RevisionLookup $revisionLookup = null
	) {
		$services = $this->getServiceContainer();
		return new TalkPageNotificationManager(
			new ServiceOptions(
				TalkPageNotificationManager::CONSTRUCTOR_OPTIONS,
				new HashConfig( [
					MainConfigNames::DisableAnonTalk => $disableAnonTalk
				] )
			),
			$services->getConnectionProvider(),
			$this->getDummyReadOnlyMode( $isReadOnly ),
			$revisionLookup ?? $services->getRevisionLookup(),
			$this->createHookContainer(),
			$services->getUserFactory()
		);
	}

	public static function provideUserHasNewMessages() {
		yield 'Registered user' => [ UserIdentityValue::newRegistered( 123, 'MyName' ) ];
		yield 'Anonymous user' => [ UserIdentityValue::newAnonymous( '1.2.3.4' ) ];
	}

	/**
	 * @dataProvider provideUserHasNewMessages
	 * @covers \MediaWiki\User\TalkPageNotificationManager::userHasNewMessages
	 * @covers \MediaWiki\User\TalkPageNotificationManager::setUserHasNewMessages
	 * @covers \MediaWiki\User\TalkPageNotificationManager::clearInstanceCache
	 * @covers \MediaWiki\User\TalkPageNotificationManager::removeUserHasNewMessages
	 */
	public function testUserHasNewMessages( UserIdentity $user ) {
		$manager = $this->getManager();
		$this->assertFalse( $manager->userHasNewMessages( $user ),
			'Should be false before updated' );
		$revRecord = $this->editUserTalk( $user, __METHOD__ );
		$manager->setUserHasNewMessages( $user, $revRecord );
		$this->assertTrue( $manager->userHasNewMessages( $user ),
			'Should be true after updated' );
		$manager->clearInstanceCache( $user );
		$this->assertTrue( $manager->userHasNewMessages( $user ),
			'Should be true after cache cleared' );
		$manager->removeUserHasNewMessages( $user );
		$this->assertFalse( $manager->userHasNewMessages( $user ),
			'Should be false after updated' );
		$manager->clearInstanceCache( $user );
		$this->assertFalse( $manager->userHasNewMessages( $user ),
			'Should be false after cache cleared' );
		$manager->setUserHasNewMessages( $user, null );
		$this->assertTrue( $manager->userHasNewMessages( $user ),
			'Should be true after updated' );
		$manager->removeUserHasNewMessages( $user );
		$this->assertFalse( $manager->userHasNewMessages( $user ),
			'Should be false after updated' );
	}

	/**
	 * @covers \MediaWiki\User\TalkPageNotificationManager::userHasNewMessages
	 * @covers \MediaWiki\User\TalkPageNotificationManager::setUserHasNewMessages
	 */
	public function testUserHasNewMessagesDisabledAnon() {
		$user = new UserIdentityValue( 0, '1.2.3.4' );
		$revRecord = $this->editUserTalk( $user, __METHOD__ );
		$manager = $this->getManager( true );
		$this->assertFalse( $manager->userHasNewMessages( $user ),
			'New anon should have no new messages' );
		$manager->setUserHasNewMessages( $user, $revRecord );
		$this->assertFalse( $manager->userHasNewMessages( $user ),
			'Must not set new messages for anon if disabled' );
		$manager->clearInstanceCache( $user );
		$this->assertFalse( $manager->userHasNewMessages( $user ),
			'Must not set to database if anon messages disabled' );
	}

	/**
	 * @covers \MediaWiki\User\TalkPageNotificationManager::getLatestSeenMessageTimestamp
	 */
	public function testGetLatestSeenMessageTimestamp() {
		$user = $this->getTestUser()->getUser();
		$firstRev = $this->editUserTalk( $user, __METHOD__ . ' 1' );
		$secondRev = $this->editUserTalk( $user, __METHOD__ . ' 2' );
		$manager = $this->getManager();
		$manager->setUserHasNewMessages( $user, $secondRev );
		$this->assertSame( $firstRev->getTimestamp(), $manager->getLatestSeenMessageTimestamp( $user ) );
	}

	/**
	 * @covers \MediaWiki\User\TalkPageNotificationManager::getLatestSeenMessageTimestamp
	 */
	public function testGetLatestSeenMessageTimestampOutOfOrderRevision() {
		$user = $this->getTestUser()->getUser();
		$firstRev = $this->editUserTalk( $user, __METHOD__ . ' 1' );
		$secondRev = $this->editUserTalk( $user, __METHOD__ . ' 2' );
		$thirdRev = $this->editUserTalk( $user, __METHOD__ . ' 3' );
		$veryOldTimestamp = MWTimestamp::convert( TS_MW, 1 );
		$mockOldRev = $this->createMock( RevisionRecord::class );
		$mockOldRev->method( 'getTimestamp' )
			->willReturn( $veryOldTimestamp );
		$mockRevLookup = $this->getMockForAbstractClass( RevisionLookup::class );
		$mockRevLookup->method( 'getPreviousRevision' )
			->willReturnCallback( static function ( RevisionRecord $rev )
				use ( $firstRev, $secondRev, $thirdRev, $mockOldRev )
			{
				if ( $rev === $secondRev ) {
					return $firstRev;
				}
				if ( $rev === $thirdRev ) {
					return $mockOldRev;
				}
				self::fail(
					'RevisionLookup::getPreviousRevision called with wrong rev ' . $rev->getId()
				);
			} );
		$manager = $this->getManager( false, false, $mockRevLookup );
		$manager->setUserHasNewMessages( $user, $thirdRev );
		$this->assertSame( $veryOldTimestamp, $manager->getLatestSeenMessageTimestamp( $user ) );
		$manager->setUserHasNewMessages( $user, $secondRev );
		$this->assertSame( $veryOldTimestamp, $manager->getLatestSeenMessageTimestamp( $user ) );
	}

	/**
	 * @covers \MediaWiki\User\TalkPageNotificationManager::getLatestSeenMessageTimestamp
	 */
	public function testGetLatestSeenMessageTimestampNoNewMessages() {
		$user = $this->getTestUser()->getUser();
		$manager = $this->getManager();
		$this->assertNull( $manager->getLatestSeenMessageTimestamp( $user ),
			'Must be null if no new messages' );
	}

	/**
	 * @covers \MediaWiki\User\TalkPageNotificationManager::userHasNewMessages
	 * @covers \MediaWiki\User\TalkPageNotificationManager::setUserHasNewMessages
	 * @covers \MediaWiki\User\TalkPageNotificationManager::removeUserHasNewMessages
	 */
	public function testDoesNotCrashOnReadOnly() {
		$user = $this->getTestUser()->getUser();
		$this->editUserTalk( $user, __METHOD__ );

		$manager = $this->getManager( false, true );
		$this->assertTrue( $manager->userHasNewMessages( $user ) );
		$manager->removeUserHasNewMessages( $user );
		$this->assertFalse( $manager->userHasNewMessages( $user ) );
	}

	/**
	 * @covers \MediaWiki\User\TalkPageNotificationManager::clearForPageView
	 */
	public function testClearForPageView() {
		$user = $this->getTestUser()->getUser();
		$title = $user->getTalkPage();
		$revision = new MutableRevisionRecord( $title );
		$revision->setPageId( 100 );
		$revision->setId( 101 );
		$manager = $this->getManager();
		$manager->setUserHasNewMessages( $user );
		$this->assertTrue( $manager->userHasNewMessages( $user ) );

		// DB should have the notification
		$this->newSelectQueryBuilder()
			->select( 'user_id' )
			->from( 'user_newtalk' )
			->where( [ 'user_id' => $user->getId() ] )
			->assertFieldValue( $user->getId() );

		$this->getDb()->startAtomic( __METHOD__ ); // let deferred updates queue up

		$updateCountBefore = DeferredUpdates::pendingUpdatesCount();
		$manager->clearForPageView( $user, $revision );
		// Cache should already be updated
		$this->assertFalse( $manager->userHasNewMessages( $user ) );

		$updateCountAfter = DeferredUpdates::pendingUpdatesCount();
		$this->assertGreaterThan( $updateCountBefore, $updateCountAfter, 'An update should have been queued' );

		$this->getDb()->endAtomic( __METHOD__ ); // run deferred updates
		$this->runDeferredUpdates();

		$this->assertSame( 0, DeferredUpdates::pendingUpdatesCount(), 'No pending updates' );

		// Notification should have been deleted from the DB
		$this->newSelectQueryBuilder()
			->select( 'user_id' )
			->from( 'user_newtalk' )
			->where( [ 'user_id' => $user->getId() ] )
			->assertEmptyResult();
	}

}
