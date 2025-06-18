<?php

namespace MediaWiki\Tests\Page;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Content\Content;
use MediaWiki\Content\JavaScriptContent;
use MediaWiki\Content\JsonContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Logging\DatabaseLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\Event\PageLatestRevisionChangedEvent;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Page\RollbackPage;
use MediaWiki\Page\WikiPage;
use MediaWiki\Permissions\Authority;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\Tests\ExpectCallbackTrait;
use MediaWiki\Tests\Language\LocalizationUpdateSpyTrait;
use MediaWiki\Tests\recentchanges\ChangeTrackingUpdateSpyTrait;
use MediaWiki\Tests\ResourceLoader\ResourceLoaderUpdateSpyTrait;
use MediaWiki\Tests\Search\SearchUpdateSpyTrait;
use MediaWiki\Tests\Unit\MockServiceDependenciesTrait;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentityValue;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\Assert;
use Wikimedia\Rdbms\ReadOnlyMode;

/**
 * @group Database
 * @covers \MediaWiki\Page\RollbackPage
 * @coversDefaultClass \MediaWiki\Page\RollbackPage
 * @method RollbackPage newServiceInstance(string $serviceClass, array $parameterOverrides)
 */
class RollbackPageTest extends MediaWikiIntegrationTestCase {
	use ChangeTrackingUpdateSpyTrait;
	use ExpectCallbackTrait;
	use LocalizationUpdateSpyTrait;
	use MockAuthorityTrait;
	use MockServiceDependenciesTrait;
	use ResourceLoaderUpdateSpyTrait;
	use SearchUpdateSpyTrait;
	use TempUserTestTrait;

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::UseRCPatrol, true );
		$this->overrideConfigValue(
			MainConfigNames::SoftwareTags,
			[ ChangeTags::TAG_REVERTED => true, ChangeTags::TAG_ROLLBACK => true ]
		);
	}

	public static function provideAuthorize() {
		yield 'Allowed' => [
			'authoritySpec' => 'ultimate',
			'expect' => true,
		];
		yield 'No edit' => [
			'authoritySpec' => [ 'edit' ],
			'expect' => false,
		];
		yield 'No rollback' => [
			'authoritySpec' => [ 'rollback' ],
			'expect' => false,
		];
	}

	/**
	 * @covers ::authorizeRollback
	 * @dataProvider provideAuthorize
	 */
	public function testAuthorize( $authoritySpec, bool $expect ) {
		$authority = $authoritySpec === 'ultimate'
			? $this->mockRegisteredUltimateAuthority()
			: $this->mockRegisteredAuthorityWithoutPermissions( $authoritySpec );
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
		$this->assertStatusNotOk( $rollback->authorizeRollback() );
	}

	/**
	 * @covers ::authorizeRollback
	 */
	public function testAuthorizePingLimiter() {
		$performer = $this->mockRegisteredUltimateAuthority();
		$userMock = $this->createMock( User::class );
		$userMock->method( 'pingLimiter' )
			->willReturnMap( [
				[ 'rollback', 1, false ],
				[ 'edit', 1, false ],
			] );
		$userFactoryMock = $this->createMock( UserFactory::class );
		$userFactoryMock->method( 'newFromAuthority' )
			->with( $performer )
			->willReturn( $userMock );
		$rollbackPage = $this->newServiceInstance( RollbackPage::class, [
			'performer' => $performer,
			'userFactory' => $userFactoryMock
		] );
		$this->assertStatusGood( $rollbackPage->authorizeRollback() );
	}

	public function testRollbackNotAllowed() {
		$this->assertStatusNotOk( $this->newServiceInstance( RollbackPage::class, [
			'performer' => $this->mockRegisteredNullAuthority()
		] )->rollbackIfAllowed() );
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

		$rev1 = $status1->getNewRevision();
		$rev2 = $status2->getNewRevision();
		$rev3 = $status3->getNewRevision();

		$revisionStore = $this->getServiceContainer()->getRevisionStore();
		/**
		 * We are having issues with doRollback spuriously failing. Apparently
		 * the last revision somehow goes missing or not committed under some
		 * circumstances. So, make sure the revisions have the correct usernames.
		 */
		$this->assertEquals(
			3,
			$revisionStore->countRevisionsByPageId( $this->getDb(), $page->getId() )
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

		$this->runJobs();

		$rc = $revisionStore->getRecentChange( $page->getRevisionRecord() );
		$rc3 = $revisionStore->getRecentChange( $rev3 );

		$this->assertNotNull( $rc, 'RecentChanges entry' );
		$this->assertEquals(
			RecentChange::PRC_AUTOPATROLLED,
			$rc->getAttribute( 'rc_patrolled' ),
			'rc_patrolled'
		);
		$this->assertEquals(
			RecentChange::PRC_PATROLLED,
			$rc3->getAttribute( 'rc_patrolled' )
		);
		$this->assertContains(
			ChangeTags::TAG_REVERTED,
			$this->getServiceContainer()->getChangeTagsStore()
				->getTags( $this->getDB(), $rc3->getAttribute( 'rc_id' ) )
		);

		$mainSlot = $page->getRevisionRecord()->getSlot( SlotRecord::MAIN );
		$this->assertTrue( $mainSlot->isInherited(), 'isInherited' );
		$this->assertSame( $rev2->getId(), $mainSlot->getOrigin(), 'getOrigin' );
	}

	public function testRollbackFailNotCreateNullRevision() {
		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser()->getUser();

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		// Make some edits
		$text = "one";
		$status1 = $this->editPage( $page, $text, "init", NS_MAIN, $admin );
		$this->assertStatusGood( $status1, 'edit 1 success' );

		$status2 = $this->editPage( $page, "$text\n\ntwo", "edit", NS_MAIN, $user1 );
		$this->assertStatusGood( $status2, 'edit 2 success' );

		$status3 = $this->editPage( $page, $text, "undo", NS_MAIN, $user1 );
		$this->assertStatusGood( $status3, 'edit 3 success' );

		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->rollbackIfAllowed();
		$this->assertStatusError( 'alreadyrolled', $rollbackResult );

		$this->runJobs();

		$this->assertEquals( $status3->getNewRevision(), $page->getRevisionRecord() );

		$rc3 = $this->getServiceContainer()->getRevisionStore()
			->getRecentChange( $status3->getNewRevision() );
		$this->assertNotContains(
			ChangeTags::TAG_REVERTED,
			$this->getServiceContainer()->getChangeTagsStore()
				->getTags( $this->getDB(), $rc3->getAttribute( 'rc_id' ) )
		);
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
	private function prepareForRollback(
		Authority $user1,
		Authority $user2,
		WikiPage $page,
		?Content $content1 = null,
		?Content $content2 = null
	): array {
		$result = [];
		$content1 ??= new WikitextContent( "one" );
		$status = $this->editPage( $page, $content1, "revision one", NS_MAIN, $user1 );
		$this->assertStatusGood( $status, 'edit 1 success' );
		$result['revision-one'] = $status->getNewRevision();

		$content2 ??= new WikitextContent( "two" );
		$status = $this->editPage( $page, $content2, "revision two", NS_MAIN, $user2 );
		$this->assertStatusGood( $status, 'edit 2 success' );
		$result['revision-two'] = $status->getNewRevision();
		return $result;
	}

	public function testRollbackTagging() {
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
		$this->assertContains( ChangeTags::TAG_ROLLBACK, $rollbackResult->getValue()['tags'] );
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

	public static function provideRollbackPatrolAndBot() {
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
		$rev3 = $status->getNewRevision();

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
			RecentChange::PRC_PATROLLED,
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
		$logRow = DatabaseLogEntry::newSelectQueryBuilder( $this->getDb() )
			->where( [ 'log_namespace' => NS_MAIN, 'log_title' => __METHOD__, 'log_type' => 'contentmodel' ] )
			->caller( __METHOD__ )->fetchRow();
		$this->assertNotNull( $logRow );
		$this->assertSame( $admin->getUser()->getName(), $logRow->user_name );
		$this->assertSame( 'TESTING', $logRow->log_comment_text );
	}

	public function testRollbackOfIPRevisionWhenTemporaryAccountsAreEnabledT371094() {
		// Set up the test page to have one revision by a user and then the second revision performed by an IP address.
		$this->disableAutoCreateTempUser();
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		$admin = $this->getTestSysop()->getUser();
		$anonUser = $this->mockAnonUltimateAuthority();

		$this->prepareForRollback( $admin, $anonUser, $page );

		// Enable temporary accounts and then perform the rollback
		$this->enableAutoCreateTempUser();
		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $anonUser->getUser() )
			->rollbackIfAllowed();
		// Ensure that the rollback worked as expected, as previously this failed with an exception if
		// rolling back a IP revision.
		$this->assertStatusGood( $rollbackResult );
	}

	public function testEventEmission() {
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( Title::newFromText( __METHOD__ ) );
		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser()->getUser();

		$this->prepareForRollback( $admin, $user1, $page );

		// clear the queue
		$this->runJobs();

		$this->expectDomainEvent(
			PageLatestRevisionChangedEvent::TYPE, 1,
			static function ( PageLatestRevisionChangedEvent $event ) use ( $admin ) {
				Assert::assertTrue(
					$event->hasCause( PageLatestRevisionChangedEvent::CAUSE_ROLLBACK ),
					PageLatestRevisionChangedEvent::CAUSE_ROLLBACK
				);

				Assert::assertTrue( $event->isRevert(), 'isRevert' );

				Assert::assertFalse( $event->isSilent(), 'isSilent' );
				Assert::assertFalse( $event->isImplicit(), 'isImplicit' );
				Assert::assertFalse( $event->isCreation(), 'isCreation' );
				Assert::assertTrue( $event->isEffectiveContentChange(), 'isEffectiveContentChange' );
				Assert::assertSame( $event->getPerformer(), $admin, 'getPerformer' );

				$editResult = $event->getEditResult();
				Assert::assertNotNull( $editResult, 'getEditResult' );
				Assert::assertTrue( $editResult->isRevert(), 'EditResult::isRevert' );
				Assert::assertSame(
					EditResult::REVERT_ROLLBACK,
					$editResult->getRevertMethod(),
					'EditResult::getRevertMethod'
				);
			}
		);

		// Now do the rollback
		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->rollbackIfAllowed();
		$this->assertStatusGood( $rollbackResult );
	}

	public static function provideUpdatePropagation() {
		static $counter = 1;
		$name = __METHOD__ . $counter++;

		yield 'article' => [ PageIdentityValue::localIdentity( 0, NS_MAIN, $name ) ];
		yield 'user talk' => [ PageIdentityValue::localIdentity( 0, NS_USER_TALK, $name ) ];
		yield 'message' => [ PageIdentityValue::localIdentity( 0, NS_MEDIAWIKI, $name ) ];
		yield 'script' => [
			PageIdentityValue::localIdentity( 0, NS_USER, "$name/common.js" ),
			new JavaScriptContent( 'console.log("kittens")' ),
			new JavaScriptContent( 'console.log("puppies")' ),
		];
	}

	/**
	 * @dataProvider provideUpdatePropagation
	 */
	public function testUpdatePropagation(
		ProperPageIdentity $page,
		?Content $content1 = null,
		?Content $content2 = null
	) {
		// Clear some extension hook handlers that may interfere with mock object expectations.
		$this->clearHooks( [
			'PageSaveComplete',
			'RevisionRecordInserted',
		] );

		$page = $this->getServiceContainer()->getWikiPageFactory()
			->newFromTitle( Title::newFromText( __METHOD__ ) );

		$admin = $this->getTestSysop()->getUser();
		$user1 = $this->getTestUser()->getUser();

		$this->prepareForRollback( $admin, $user1, $page, $content1, $content2 );

		// clear the queue
		$this->runJobs();

		// Should generate an RC entry for rollback
		$this->expectChangeTrackingUpdates(
			1, 0, 1,
			$page->getNamespace() === NS_USER_TALK ? 1 : 0,
			1
		);

		$this->expectSearchUpdates( 1 );
		$this->expectLocalizationUpdate( $page->getNamespace() === NS_MEDIAWIKI ? 1 : 0 );
		$this->expectResourceLoaderUpdates(
			$content1 && ( $content1->getModel() === CONTENT_MODEL_JAVASCRIPT ? 1 : 0 )
		);

		// Now do the rollback
		$rollbackResult = $this->getServiceContainer()
			->getRollbackPageFactory()
			->newRollbackPage( $page, $admin, $user1 )
			->rollbackIfAllowed();
		$this->assertStatusGood( $rollbackResult );

		$this->runDeferredUpdates();
	}
}
