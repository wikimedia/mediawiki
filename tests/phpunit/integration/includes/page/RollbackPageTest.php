<?php

namespace MediaWiki\Tests\Page;

use ChangeTags;
use DatabaseLogEntry;
use JsonContent;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\RollbackPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Unit\MockServiceDependenciesTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use ReadOnlyMode;
use RecentChange;
use Title;
use User;
use WikiPage;
use WikitextContent;

/**
 * @group Database
 * @covers \MediaWiki\Page\RollbackPage
 * @coversDefaultClass \MediaWiki\Page\RollbackPage
 * @package MediaWiki\Tests\Page
 * @method RollbackPage newServiceInstance(string $serviceClass, array $parameterOverrides)
 */
class RollbackPageTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;
	use MockServiceDependenciesTrait;

	protected function setUp(): void {
		parent::setUp();
		$this->overrideConfigValue( MainConfigNames::UseRCPatrol, true );
		$this->tablesUsed = array_merge( $this->tablesUsed, [
			'page',
			'recentchanges',
			'logging',
		] );
	}

	public function provideAuthorize() {
		yield 'Allowed' => [
			'authority' => $this->mockRegisteredUltimateAuthority(),
			'expect' => true,
		];
		yield 'No edit' => [
			'authority' => $this->mockRegisteredAuthorityWithoutPermissions( [ 'edit' ] ),
			'expect' => true,
		];
		yield 'No rollback' => [
			'authority' => $this->mockRegisteredAuthorityWithoutPermissions( [ 'rollback' ] ),
			'expect' => true,
		];
	}

	/**
	 * @covers ::authorizeRollback
	 * @dataProvider provideAuthorize
	 */
	public function testAuthorize( Authority $authority, bool $expect ) {
		$this->assertSame(
			$expect,
			$this->getServiceContainer()
				->getRollbackPageFactory()
				->newRollbackPage(
					new PageIdentityValue( 10, NS_MAIN, 'Test', PageIdentity::LOCAL ),
					$authority,
					new UserIdentityValue( 0, '127.0.0.1' )
				)
				->authorizeRollback()
				->isGood()
		);
	}

	public function testAuthorizeReadOnly() {
		$mockReadOnly = $this->createMock( ReadOnlyMode::class );
		$mockReadOnly->method( 'isReadOnly' )->willReturn( true );
		$rollback = $this->newServiceInstance( RollbackPage::class, [
			'readOnlyMode' => $mockReadOnly,
			'performer' => $this->mockRegisteredUltimateAuthority()
		] );
		$this->assertFalse( $rollback->authorizeRollback()->isGood() );
	}

	/**
	 * @covers ::authorizeRollback
	 */
	public function testAuthorizePingLimiter() {
		$performer = $this->mockRegisteredUltimateAuthority();
		$userMock = $this->createMock( User::class );
		$userMock->method( 'pingLimiter' )
			->withConsecutive( [ 'rollback', 1 ], [ 'edit', 1 ] )
			->willReturnOnConsecutiveCalls( false, false );
		$userFactoryMock = $this->createMock( UserFactory::class );
		$userFactoryMock->method( 'newFromAuthority' )
			->with( $performer )
			->willReturn( $userMock );
		$rollbackPage = $this->newServiceInstance( RollbackPage::class, [
			'performer' => $performer,
			'userFactory' => $userFactoryMock
		] );
		$this->assertTrue( $rollbackPage->authorizeRollback()->isGood() );
	}

	public function testRollbackNotAllowed() {
		$this->assertFalse( $this->newServiceInstance( RollbackPage::class, [
			'performer' => $this->mockRegisteredNullAuthority()
		] )->rollbackIfAllowed()->isGood() );
	}

	public function testRollback() {
		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser()->getUser();
		// Use the confirmed group for user2 to make sure the user is different
		$user2 = $this->getTestUser( [ 'confirmed' ] )->getUser();

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		// Make some edits
		$text = "one";
		$status1 = $this->editPage( $page, $text, "section one", NS_MAIN, $admin );
		$this->assertStatusGood( $status1, 'edit 1 success' );

		$text .= "\n\ntwo";
		$status2 = $this->editPage( $page, $text, "adding section two", NS_MAIN, $user1 );
		$this->assertStatusGood( $status2, 'edit 2 success' );

		$text .= "\n\nthree";
		$status3 = $this->editPage( $page, $text, "adding section three", NS_MAIN, $user2 );
		$this->assertStatusGood( $status3, 'edit 3 success' );

		/** @var RevisionRecord $rev1 */
		/** @var RevisionRecord $rev2 */
		/** @var RevisionRecord $rev3 */
		$rev1 = $status1->getValue()['revision-record'];
		$rev2 = $status2->getValue()['revision-record'];
		$rev3 = $status3->getValue()['revision-record'];

		$revisionStore = $this->getServiceContainer()->getRevisionStore();
		/**
		 * We are having issues with doRollback spuriously failing. Apparently
		 * the last revision somehow goes missing or not committed under some
		 * circumstances. So, make sure the revisions have the correct usernames.
		 */
		$this->assertEquals(
			3,
			$revisionStore->countRevisionsByPageId( $this->db, $page->getId() )
		);
		$this->assertEquals( $admin->getName(), $rev1->getUser()->getName() );
		$this->assertEquals( $user1->getName(), $rev2->getUser()->getName() );
		$this->assertEquals( $user2->getName(), $rev3->getUser()->getName() );

		$rc3 = $revisionStore->getRecentChange( $rev3 );
		$this->assertEquals(
			RecentChange::PRC_UNPATROLLED,
			$rc3->getAttribute( 'rc_patrolled' )
		);

		// Now, try the actual rollback
		$rollbackStatus = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user2 )
			->rollbackIfAllowed();
		$this->assertStatusGood( $rollbackStatus );

		$this->assertEquals(
			$rev2->getSha1(),
			$page->getRevisionRecord()->getSha1(),
			"rollback did not revert to the correct revision" );
		$this->assertEquals( "one\n\ntwo", $page->getContent()->getText() );

		$rc = $revisionStore->getRecentChange( $page->getRevisionRecord() );
		$rc3 = $revisionStore->getRecentChange( $rev3 );

		$this->assertNotNull( $rc, 'RecentChanges entry' );
		$this->assertEquals(
			RecentChange::PRC_AUTOPATROLLED,
			$rc->getAttribute( 'rc_patrolled' ),
			'rc_patrolled'
		);
		$this->assertEquals(
			RecentChange::PRC_AUTOPATROLLED,
			$rc3->getAttribute( 'rc_patrolled' )
		);

		$mainSlot = $page->getRevisionRecord()->getSlot( SlotRecord::MAIN );
		$this->assertTrue( $mainSlot->isInherited(), 'isInherited' );
		$this->assertSame( $rev2->getId(), $mainSlot->getOrigin(), 'getOrigin' );
	}

	public function testRollbackFailSameContent() {
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser( [ 'sysop' ] )->getUser();

		[ 'revision-one' => $rev1 ] = $this->prepareForRollback( $admin, $user1, $page );

		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->rollbackIfAllowed();
		$this->assertStatusGood( $rollbackResult );

		# now, try the rollback again
		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->rollback();
		$this->assertStatusError( 'alreadyrolled', $rollbackResult );

		$this->assertEquals( $rev1->getSha1(), $page->getRevisionRecord()->getSha1(),
			"rollback did not revert to the correct revision" );
		$this->assertEquals( "one", $page->getContent()->getText() );
	}

	public function testRollbackFailNotExisting() {
		$rollbackStatus = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage(
				new PageIdentityValue( 0, NS_MAIN, __METHOD__, PageIdentityValue::LOCAL ),
				$this->mockRegisteredUltimateAuthority(),
				new UserIdentityValue( 0, '127.0.0.1' )
			)
			->rollback();
		$this->assertStatusError( 'notanarticle', $rollbackStatus );
	}

	/**
	 * @param Authority $user1
	 * @param Authority $user2
	 * @param WikiPage $page
	 * @return array with info about created page:
	 *  'revision-one' => RevisionRecord
	 *  'revision-two' => RevisionRecord
	 */
	private function prepareForRollback( Authority $user1, Authority $user2, WikiPage $page ): array {
		$result = [];
		$text = "one";
		$status = $this->editPage( $page, $text, "section one", NS_MAIN, $user1 );
		$this->assertStatusGood( $status, 'edit 1 success' );
		$result['revision-one'] = $status->getValue()['revision-record'];

		$text .= "\n\ntwo";
		$status = $this->editPage( $page, $text, "adding section two", NS_MAIN, $user2 );
		$this->assertStatusGood( $status, 'edit 2 success' );
		$result['revision-two'] = $status->getValue()['revision-record'];
		return $result;
	}

	public function testRollbackTagging() {
		if ( !in_array( 'mw-rollback', ChangeTags::getSoftwareTags() ) ) {
			$this->markTestSkipped( 'Rollback tag deactivated, skipped the test.' );
		}

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser()->getUser();

		$this->prepareForRollback( $admin, $user1, $page );

		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->setChangeTags( [ 'tag' ] )
			->rollbackIfAllowed();
		$this->assertStatusGood( $rollbackResult );
		$this->assertContains( 'mw-rollback', $rollbackResult->getValue()['tags'] );
		$this->assertContains( 'tag', $rollbackResult->getValue()['tags'] );
	}

	public function testRollbackBot() {
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser()->getUser();

		$this->prepareForRollback( $admin, $user1, $page );

		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->markAsBot( true )
			->rollbackIfAllowed();
		$this->assertStatusGood( $rollbackResult );
		$rc = $this->getServiceContainer()->getRevisionStore()->getRecentChange( $page->getRevisionRecord() );
		$this->assertNotNull( $rc );
		$this->assertSame( '1', $rc->getAttribute( 'rc_bot' ) );
	}

	public function testRollbackBotNotAllowed() {
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		$admin = $this->mockUserAuthorityWithoutPermissions(
			$this->getTestSysop()->getUser(), [ 'markbotedits', 'bot' ] );
		$user1 = $this->getTestUser()->getUser();

		$this->prepareForRollback( $admin, $user1, $page );

		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->markAsBot( true )
			->rollbackIfAllowed();
		$this->assertStatusGood( $rollbackResult );
		$rc = $this->getServiceContainer()->getRevisionStore()->getRecentChange( $page->getRevisionRecord() );
		$this->assertNotNull( $rc );
		$this->assertSame( '0', $rc->getAttribute( 'rc_bot' ) );
	}

	public function provideRollbackPatrolAndBot() {
		yield 'mark as bot' => [ true ];
		yield 'do not mark as bot' => [ false ];
	}

	/**
	 * @dataProvider provideRollbackPatrolAndBot
	 */
	public function testRollbackPatrolAndBot( bool $markAsBot ) {
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser()->getUser();

		[
			'revision-one' => $rev1,
			'revision-two' => $rev2,
		] = $this->prepareForRollback( $admin, $user1, $page );

		$text = "one\n\ntwo\n\nthree";
		$status = $this->editPage( $page, $text, "adding section three", NS_MAIN, $user1 );
		$this->assertStatusGood( $status, 'edit 3 success' );
		$rev3 = $status->getValue()['revision-record'];

		$revisionStore = $this->getServiceContainer()->getRevisionStore();

		$rc1 = $revisionStore->getRecentChange( $rev1 );
		$this->assertEquals(
			RecentChange::PRC_AUTOPATROLLED,
			$rc1->getAttribute( 'rc_patrolled' )
		);

		// manually patrol the first reverted revision
		$rc2 = $revisionStore->getRecentChange( $rev2 );
		$rc2->reallyMarkPatrolled();

		$rc3 = $revisionStore->getRecentChange( $rev3 );
		$this->assertEquals(
			RecentChange::PRC_UNPATROLLED,
			$rc3->getAttribute( 'rc_patrolled' )
		);

		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->markAsBot( $markAsBot )
			->rollback();
		$this->assertStatusGood( $rollbackResult );

		$rc1 = $revisionStore->getRecentChange( $rev1 );
		$this->assertEquals(
			RecentChange::PRC_AUTOPATROLLED,
			$rc1->getAttribute( 'rc_patrolled' )
		);
		$this->assertFalse( (bool)$rc1->getAttribute( 'rc_bot' ) );

		$rc2 = $revisionStore->getRecentChange( $rev2 );
		$this->assertEquals(
			RecentChange::PRC_PATROLLED,
			$rc2->getAttribute( 'rc_patrolled' )
		);
		$this->assertSame( $markAsBot, (bool)$rc2->getAttribute( 'rc_bot' ) );

		$rc3 = $revisionStore->getRecentChange( $rev3 );
		$this->assertEquals(
			RecentChange::PRC_AUTOPATROLLED,
			$rc3->getAttribute( 'rc_patrolled' )
		);
		$this->assertSame( $markAsBot, (bool)$rc3->getAttribute( 'rc_bot' ) );
	}

	public function testRollbackCustomSummary() {
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser()->getUser();

		$revisions = $this->prepareForRollback( $admin, $user1, $page );

		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->setSummary( 'TEST! $1 $2 $3 $4 $5 $6' )
			->rollbackIfAllowed();
		$this->assertStatusGood( $rollbackResult );
		$targetTimestamp = $this->getServiceContainer()
			->getContentLanguage()
			->timeanddate( $revisions['revision-one']->getTimestamp() );
		$currentTimestamp = $this->getServiceContainer()
			->getContentLanguage()
			->timeanddate( $revisions['revision-two']->getTimestamp() );
		$expectedSummary = implode( ' ', [
			'TEST!',
			$admin->getName(),
			$user1->getName(),
			$revisions['revision-one']->getId(),
			$targetTimestamp,
			$revisions['revision-two']->getId(),
			$currentTimestamp
		] );
		$this->assertSame( $expectedSummary, $page->getRevisionRecord()->getComment()->text );
		$rc = $this->getServiceContainer()->getRevisionStore()->getRecentChange( $page->getRevisionRecord() );
		$this->assertNotNull( $rc );
		$this->assertSame( $expectedSummary, $rc->getAttribute( 'rc_comment' ) );
	}

	public function testRollbackChangesContentModel() {
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser()->getUser();

		$status1 = $this->editPage( $page, new JsonContent( '{}' ),
			"it's json", NS_MAIN, $admin );
		$this->assertStatusGood( $status1, 'edit 1 success' );

		$status1 = $this->editPage( $page, new WikitextContent( 'bla' ),
			"no, it's wikitext", NS_MAIN, $user1 );
		$this->assertStatusGood( $status1, 'edit 2 success' );

		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->setSummary( 'TESTING' )
			->rollbackIfAllowed();
		$this->assertStatusGood( $rollbackResult );
		$logQuery = DatabaseLogEntry::getSelectQueryData();
		$logRow = $this->db->selectRow(
			$logQuery['tables'],
			$logQuery['fields'],
			[
				'log_namespace' => NS_MAIN,
				'log_title' => __METHOD__,
				'log_type' => 'contentmodel'
			],
			__METHOD__,
			[],
			$logQuery['join_conds']
		);
		$this->assertNotNull( $logRow );
		$this->assertSame( $admin->getUser()->getName(), $logRow->user_name );
		$this->assertSame( 'TESTING', $logRow->log_comment_text );
	}
}
