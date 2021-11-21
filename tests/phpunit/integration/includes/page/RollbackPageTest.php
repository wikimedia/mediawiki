<?php

namespace MediaWiki\Tests\Page;

use ChangeTags;
use DatabaseLogEntry;
use JsonContent;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\RollbackPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\RevisionRecord;
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
		$this->setMwGlobals( 'wgUseRCPatrol', true );
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

		$page = new WikiPage( Title::newFromText( __METHOD__ ) );
		// Make some edits
		$text = "one";
		$status1 = $this->editPage( $page, $text, "section one", NS_MAIN, $admin );
		$this->assertTrue( $status1->isGood(), 'edit 1 success' );

		$text .= "\n\ntwo";
		$status2 = $this->editPage( $page, $text, "adding section two", NS_MAIN, $user1 );
		$this->assertTrue( $status2->isGood(), 'edit 2 success' );

		$text .= "\n\nthree";
		$status3 = $this->editPage( $page, $text, "adding section three", NS_MAIN, $user2 );
		$this->assertTrue( $status3->isGood(), 'edit 3 success' );

		/** @var RevisionRecord $rev1 */
		/** @var RevisionRecord $rev2 */
		/** @var RevisionRecord $rev3 */
		$rev1 = $status1->getValue()['revision-record'];
		$rev2 = $status2->getValue()['revision-record'];
		$rev3 = $status3->getValue()['revision-record'];

		/**
		 * We are having issues with doRollback spuriously failing. Apparently
		 * the last revision somehow goes missing or not committed under some
		 * circumstances. So, make sure the revisions have the correct usernames.
		 */
		$this->assertEquals(
			3,
			$this->getServiceContainer()
				->getRevisionStore()
				->countRevisionsByPageId( $this->db, $page->getId() )
		);
		$this->assertEquals( $admin->getName(), $rev1->getUser()->getName() );
		$this->assertEquals( $user1->getName(), $rev2->getUser()->getName() );
		$this->assertEquals( $user2->getName(), $rev3->getUser()->getName() );

		// Now, try the actual rollback
		$rollbackStatus = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user2 )
			->rollbackIfAllowed();
		$this->assertTrue( $rollbackStatus->isGood() );

		$this->assertEquals(
			$rev2->getSha1(),
			$page->getRevisionRecord()->getSha1(),
			"rollback did not revert to the correct revision" );
		$this->assertEquals( "one\n\ntwo", $page->getContent()->getText() );

		$rc = $this->getServiceContainer()->getRevisionStore()->getRecentChange(
			$page->getRevisionRecord()
		);

		$this->assertNotNull( $rc, 'RecentChanges entry' );
		$this->assertEquals(
			RecentChange::PRC_AUTOPATROLLED,
			$rc->getAttribute( 'rc_patrolled' ),
			'rc_patrolled'
		);

		$mainSlot = $page->getRevisionRecord()->getSlot( SlotRecord::MAIN );
		$this->assertTrue( $mainSlot->isInherited(), 'isInherited' );
		$this->assertSame( $rev2->getId(), $mainSlot->getOrigin(), 'getOrigin' );
	}

	public function testRollbackFailSameContent() {
		$admin = $this->getTestSysop()->getUser();
		$page = new WikiPage( Title::newFromText( __METHOD__ ) );

		$text = "one";
		$status1 = $this->editPage( $page, $text, "section one", NS_MAIN, $admin );
		$this->assertTrue( $status1->isGood(), 'edit 1 success' );
		$rev1 = $page->getRevisionRecord();

		$user1 = $this->getTestUser( [ 'sysop' ] )->getUser();
		$text .= "\n\ntwo";
		$status1 = $this->editPage( $page, $text, "adding section two", NS_MAIN, $user1 );
		$this->assertTrue( $status1->isGood(), 'edit 2 success' );

		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->rollbackIfAllowed();
		$this->assertTrue( $rollbackResult->isGood() );

		# now, try the rollback again
		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->rollback();
		$this->assertFalse( $rollbackResult->isGood() );
		$this->assertTrue( $rollbackResult->hasMessage( 'alreadyrolled' ) );

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
		$this->assertFalse( $rollbackStatus->isGood() );
		$this->assertTrue( $rollbackStatus->hasMessage( 'notanarticle' ) );
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
		$this->assertTrue( $status->isGood(), 'edit 1 success' );
		$result['revision-one'] = $status->getValue()['revision-record'];

		$text .= "\n\ntwo";
		$status = $this->editPage( $page, $text, "adding section two", NS_MAIN, $user2 );
		$this->assertTrue( $status->isGood(), 'edit 2 success' );
		$result['revision-two'] = $status->getValue()['revision-record'];
		return $result;
	}

	public function testRollbackTagging() {
		if ( !in_array( 'mw-rollback', ChangeTags::getSoftwareTags() ) ) {
			$this->markTestSkipped( 'Rollback tag deactivated, skipped the test.' );
		}

		$page = new WikiPage( Title::newFromText( __METHOD__ ) );
		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser()->getUser();

		$this->prepareForRollback( $admin, $user1, $page );

		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->setChangeTags( [ 'tag' ] )
			->rollbackIfAllowed();
		$this->assertTrue( $rollbackResult->isGood() );
		$this->assertContains( 'mw-rollback', $rollbackResult->getValue()['tags'] );
		$this->assertContains( 'tag', $rollbackResult->getValue()['tags'] );
	}

	public function testRollbackBot() {
		$page = new WikiPage( Title::newFromText( __METHOD__ ) );
		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser()->getUser();

		$this->prepareForRollback( $admin, $user1, $page );

		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->markAsBot( true )
			->rollbackIfAllowed();
		$this->assertTrue( $rollbackResult->isGood() );
		$rc = $this->getServiceContainer()->getRevisionStore()->getRecentChange( $page->getRevisionRecord() );
		$this->assertNotNull( $rc );
		$this->assertSame( '1', $rc->getAttribute( 'rc_bot' ) );
	}

	public function testRollbackBotNotAllowed() {
		$page = new WikiPage( Title::newFromText( __METHOD__ ) );
		$admin = $this->mockUserAuthorityWithoutPermissions(
			$this->getTestSysop()->getUser(), [ 'markbotedits', 'bot' ] );
		$user1 = $this->getTestUser()->getUser();

		$this->prepareForRollback( $admin, $user1, $page );

		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->markAsBot( true )
			->rollbackIfAllowed();
		$this->assertTrue( $rollbackResult->isGood() );
		$rc = $this->getServiceContainer()->getRevisionStore()->getRecentChange( $page->getRevisionRecord() );
		$this->assertNotNull( $rc );
		$this->assertSame( '0', $rc->getAttribute( 'rc_bot' ) );
	}

	public function testRollbackCustomSummary() {
		$page = new WikiPage( Title::newFromText( __METHOD__ ) );
		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser()->getUser();

		$revisions = $this->prepareForRollback( $admin, $user1, $page );

		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->setSummary( 'TEST! $1 $2 $3 $4 $5 $6' )
			->rollbackIfAllowed();
		$this->assertTrue( $rollbackResult->isGood() );
		$targetTimestamp = $this->getServiceContainer()
			->getContentLanguage()
			->timeanddate( $revisions['revision-one']->getTimestamp() );
		$currentTimestamp = $this->getServiceContainer()
			->getContentLanguage()
			->timeanddate( $revisions['revision-two']->getTimestamp() );
		$expectedSummary = "TEST! {$admin->getName()} {$user1->getName()}" .
			" {$revisions['revision-one']->getId()}" .
			" {$targetTimestamp}" .
			" {$revisions['revision-two']->getId()}" .
			" {$currentTimestamp}";
		$this->assertSame( $expectedSummary, $page->getRevisionRecord()->getComment()->text );
		$rc = $this->getServiceContainer()->getRevisionStore()->getRecentChange( $page->getRevisionRecord() );
		$this->assertNotNull( $rc );
		$this->assertSame( $expectedSummary, $rc->getAttribute( 'rc_comment' ) );
	}

	public function testRollbackChangesContentModel() {
		$page = new WikiPage( Title::newFromText( __METHOD__ ) );
		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser()->getUser();

		$status1 = $this->editPage( $page, new JsonContent( '{}' ),
			"it's json", NS_MAIN, $admin );
		$this->assertTrue( $status1->isGood(), 'edit 1 success' );

		$status1 = $this->editPage( $page, new WikitextContent( 'bla' ),
			"no, it's wikitext", NS_MAIN, $user1 );
		$this->assertTrue( $status1->isGood(), 'edit 2 success' );

		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->setSummary( 'TESTING' )
			->rollbackIfAllowed();
		$this->assertTrue( $rollbackResult->isGood() );
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
