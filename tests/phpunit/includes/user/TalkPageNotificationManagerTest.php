<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\TalkPageNotificationManager;
use PHPUnit\Framework\AssertionFailedError;

/**
 * @covers \MediaWiki\User\TalkPageNotificationManager
 * @group Database
 */
class TalkPageNotificationManagerTest extends MediaWikiIntegrationTestCase {
	use MediaWikiCoversValidator;

	protected function setUp(): void {
		parent::setUp();
		$this->tablesUsed[] = 'user_newtalk';
	}

	private function editUserTalk( User $user, string $text ) : RevisionRecord {
		$userTalk = $user->getTalkPage();
		$status = $this->editPage(
			$userTalk->getPrefixedText(),
			$text,
			'',
			NS_MAIN,
			$this->getTestSysop()->getUser()
		);
		$this->assertTrue( $status->isGood(), 'Sanity: create revision of user talk' );
		return $status->getValue()['revision-record'];
	}

	private function getManager(
		bool $disableAnonTalk = false,
		ReadOnlyMode $readOnlyMode = null,
		RevisionLookup $revisionLookup = null
	) {
		$services = MediaWikiServices::getInstance();
		return new TalkPageNotificationManager(
			new ServiceOptions(
				TalkPageNotificationManager::CONSTRUCTOR_OPTIONS,
				new HashConfig( [
					'DisableAnonTalk' => $disableAnonTalk
				] )
			),
			$services->getDBLoadBalancer(),
			$readOnlyMode ?? $services->getReadOnlyMode(),
			$revisionLookup ?? $services->getRevisionLookup()
		);
	}

	private function doTestUserHasNewMessages( User $user ) {
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
	 * @covers \MediaWiki\User\TalkPageNotificationManager::clearInstanceCache
	 * @covers \MediaWiki\User\TalkPageNotificationManager::removeUserHasNewMessages
	 */
	public function testUserHasNewMessagesRegistered() {
		$this->doTestUserHasNewMessages( $this->getTestUser()->getUser() );
	}

	/**
	 * @covers \MediaWiki\User\TalkPageNotificationManager::userHasNewMessages
	 * @covers \MediaWiki\User\TalkPageNotificationManager::setUserHasNewMessages
	 * @covers \MediaWiki\User\TalkPageNotificationManager::clearInstanceCache
	 * @covers \MediaWiki\User\TalkPageNotificationManager::removeUserHasNewMessages
	 */
	public function testUserHasNewMessagesAnon() {
		$this->doTestUserHasNewMessages( User::newFromName( 'testUserHasNewMessagesAnon' ) );
	}

	/**
	 * @covers \MediaWiki\User\TalkPageNotificationManager::userHasNewMessages
	 * @covers \MediaWiki\User\TalkPageNotificationManager::setUserHasNewMessages
	 */
	public function testUserHasNewMessagesDisabledAnon() {
		$user = User::newFromName( 'testUserHasNewMessagesAnon' );
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
			->willReturnCallback( function ( RevisionRecord $rev )
				use ( $firstRev, $secondRev, $thirdRev, $mockOldRev )
			{
				if ( $rev === $secondRev ) {
					return $firstRev;
				}
				if ( $rev === $thirdRev ) {
					return $mockOldRev;
				}
				throw new AssertionFailedError(
					'RevisionLookup::getPreviousRevision called with wrong rev ' . $rev->getId()
				);
			} );
		$manager = $this->getManager( false, null, $mockRevLookup );
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
		$mockReadOnly = $this->createMock( ReadOnlyMode::class );
		$mockReadOnly->method( 'isReadOnly' )->willReturn( true );

		$manager = $this->getManager( false, $mockReadOnly );
		$this->assertTrue( $manager->userHasNewMessages( $user ) );
		$manager->removeUserHasNewMessages( $user );
		$this->assertFalse( $manager->userHasNewMessages( $user ) );
	}
}
