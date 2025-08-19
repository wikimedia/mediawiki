<?php

namespace MediaWiki\Tests\Storage;

use LogicException;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\Content;
use MediaWiki\Content\JavaScriptContent;
use MediaWiki\Content\TextContent;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Page\Event\PageCreatedEvent;
use MediaWiki\Page\Event\PageLatestRevisionChangedEvent;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\RecentChanges\ChangeTrackingEventIngress;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\RenderedRevision;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Status\Status;
use MediaWiki\Storage\EditResult;
use MediaWiki\Tests\ExpectCallbackTrait;
use MediaWiki\Tests\Language\LocalizationUpdateSpyTrait;
use MediaWiki\Tests\Recentchanges\ChangeTrackingUpdateSpyTrait;
use MediaWiki\Tests\ResourceLoader\ResourceLoaderUpdateSpyTrait;
use MediaWiki\Tests\Search\SearchUpdateSpyTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWikiIntegrationTestCase;
use PHPUnit\Framework\Assert;

/**
 * @covers \MediaWiki\Storage\PageUpdater
 * @group Database
 */
class PageUpdaterTest extends MediaWikiIntegrationTestCase {

	use ChangeTrackingUpdateSpyTrait;
	use SearchUpdateSpyTrait;
	use LocalizationUpdateSpyTrait;
	use ResourceLoaderUpdateSpyTrait;
	use ExpectCallbackTrait;

	protected function setUp(): void {
		parent::setUp();

		// Force enable RC entry creation for category changes
		// so that tests can verify whether CategoryMembershipChangeJobs get enqueued.
		$this->overrideConfigValue( MainConfigNames::RCWatchCategoryMembership, true );

		$slotRoleRegistry = $this->getServiceContainer()->getSlotRoleRegistry();

		if ( !$slotRoleRegistry->isDefinedRole( 'aux' ) ) {
			$slotRoleRegistry->defineRoleWithModel(
				'aux',
				CONTENT_MODEL_WIKITEXT
			);
		}

		if ( !$slotRoleRegistry->isDefinedRole( 'derivedslot' ) ) {
			$slotRoleRegistry->defineRoleWithModel(
				'derivedslot',
				CONTENT_MODEL_WIKITEXT,
				[],
				true
			);
		}

		// protect against service container resets
		$this->setService( 'SlotRoleRegistry', $slotRoleRegistry );

		// Clear some extension hook handlers that may interfere with mock object expectations.
		$this->clearHooks( [
			'RevisionRecordInserted',
			'PageSaveComplete',
			'LinksUpdateComplete',
		] );
	}

	private function getDummyTitle( $method ) {
		return Title::newFromText( $method, $this->getDefaultWikitextNS() );
	}

	/**
	 * @param int $revId
	 *
	 * @return null|RecentChange
	 */
	private function getRecentChangeFor( $revId ) {
		$qi = RecentChange::getQueryInfo();
		$row = $this->getDb()->newSelectQueryBuilder()
			->queryInfo( $qi )
			->where( [ 'rc_this_oldid' => $revId ] )
			->caller( __METHOD__ )
			->fetchRow();

		return $row ? RecentChange::newFromRow( $row ) : null;
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 * @covers \MediaWiki\Page\WikiPage::newPageUpdater()
	 */
	public function testCreatePage() {
		$user = $this->getTestUser()->getUser();
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();

		$title = $this->getDummyTitle( __METHOD__ );
		$page = $wikiPageFactory->newFromTitle( $title );
		$updater = $page->newPageUpdater( $user );

		$oldStats = $this->getDb()->newSelectQueryBuilder()
			->select( '*' )
			->from( 'site_stats' )
			->where( '1=1' )
			->fetchRow();

		$this->assertFalse( $updater->wasCommitted(), 'wasCommitted' );

		$updater->addTag( 'foo' );
		$updater->addTags( [ 'bar', 'qux' ] );

		$tags = $updater->getExplicitTags();
		sort( $tags );
		$this->assertSame( [ 'bar', 'foo', 'qux' ], $tags, 'getExplicitTags' );

		// TODO: MCR: test additional slots
		$content = new TextContent( 'Lorem Ipsum' );
		$updater->setContent( SlotRecord::MAIN, $content );

		$parent = $updater->grabParentRevision();

		$this->assertNull( $parent, 'getParentRevision' );
		$this->assertFalse( $updater->wasCommitted(), 'wasCommitted' );

		// TODO: test that hasEditConflict() grabs the parent revision
		$this->assertFalse( $updater->hasEditConflict( 0 ), 'hasEditConflict' );
		$this->assertTrue( $updater->hasEditConflict( 1 ), 'hasEditConflict' );

		// TODO: test failure with EDIT_UPDATE
		// TODO: test EDIT_BOT, etc
		$updater->setFlags( EDIT_MINOR );
		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$rev = $updater->saveRevision( $summary );

		$this->assertNotNull( $rev );
		$this->assertSame( 0, $rev->getParentId() );
		$this->assertSame( $summary->text, $rev->getComment( RevisionRecord::RAW )->text );
		$this->assertSame( $user->getName(), $rev->getUser( RevisionRecord::RAW )->getName() );

		$this->assertTrue( $updater->wasCommitted(), 'wasCommitted()' );
		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertTrue( $updater->getStatus()->isOK(), 'getStatus()->isOK()' );
		$this->assertTrue( $updater->isNew(), 'isNew()' );
		$this->assertTrue( $updater->wasRevisionCreated(), 'wasRevisionCreated()' );
		$this->assertNotNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertInstanceOf(
			RevisionRecord::class,
			$updater->getStatus()->getNewRevision()
		);

		// check the EditResult object
		$this->assertFalse( $updater->getEditResult()->getOriginalRevisionId(),
			'EditResult::getOriginalRevisionId()' );
		$this->assertSame( 0, $updater->getEditResult()->getUndidRevId(),
			'EditResult::getUndidRevId()' );
		$this->assertTrue( $updater->getEditResult()->isNew(), 'EditResult::isNew()' );
		$this->assertFalse( $updater->getEditResult()->isRevert(), 'EditResult::isRevert()' );

		$rev = $updater->getNewRevision();
		$revContent = $rev->getContent( SlotRecord::MAIN );
		$this->assertSame( 'Lorem Ipsum', $revContent->serialize(), 'revision content' );
		$this->assertTrue( $rev->isMinor(), 'RevisionRecord::isMinor()' );

		// were the WikiPage and Title objects updated?
		$this->assertTrue( $page->exists(), 'WikiPage::exists()' );
		$this->assertTrue( $title->exists(), 'Title::exists()' );
		$this->assertSame( $rev->getId(), $page->getLatest(), 'WikiPage::getLatest()' );
		$this->assertNotNull( $page->getRevisionRecord(), 'WikiPage::getRevisionRecord()' );

		// re-load
		$page2 = $wikiPageFactory->newFromTitle( $title );
		$this->assertTrue( $page2->exists(), 'WikiPage::exists()' );
		$this->assertSame( $rev->getId(), $page2->getLatest(), 'WikiPage::getLatest()' );
		$this->assertNotNull( $page2->getRevisionRecord(), 'WikiPage::getRevisionRecord()' );

		// Check RC entry
		$rc = $this->getRecentChangeFor( $rev->getId() );
		$this->assertNotNull( $rc, 'RecentChange' );

		// check site stats - this asserts that derived data updates where run.
		$stats = $this->getDb()->newSelectQueryBuilder()
			->select( '*' )
			->from( 'site_stats' )
			->where( '1=1' )
			->fetchRow();
		$this->assertSame( $oldStats->ss_total_pages + 1, (int)$stats->ss_total_pages );
		$this->assertSame( $oldStats->ss_total_edits + 1, (int)$stats->ss_total_edits );

		// re-edit with same content - should be a "null-edit"
		$updater = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, $content );

		$summary = CommentStoreComment::newUnsavedComment( 're-edit' );
		$rev = $updater->saveRevision( $summary );
		$status = $updater->getStatus();

		$this->assertNull( $rev, 'getNewRevision()' );
		$this->assertNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertFalse( $updater->wasRevisionCreated(), 'wasRevisionCreated' );
		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertStatusWarning( 'edit-no-change', $status );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 * @covers \MediaWiki\Page\WikiPage::newPageUpdater()
	 */
	public function testUpdatePage() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$this->insertPage( $title );
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();

		$page = $wikiPageFactory->newFromTitle( $title );
		$parentId = $page->getLatest();

		$updater = $page->newPageUpdater( $user );

		$oldStats = $this->getDb()->newSelectQueryBuilder()
			->select( '*' )
			->from( 'site_stats' )
			->where( '1=1' )
			->fetchRow();

		$updater->setOriginalRevisionId( 7 );

		$this->assertFalse( $updater->hasEditConflict( $parentId ), 'hasEditConflict' );
		$this->assertTrue( $updater->hasEditConflict( $parentId - 1 ), 'hasEditConflict' );
		$this->assertTrue( $updater->hasEditConflict( 0 ), 'hasEditConflict' );

		// TODO: MCR: test additional slots
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );

		// Check that prepareUpdate() does not fail, and the flag is applied.
		$updater->prepareUpdate( EDIT_MINOR );

		// TODO: test all flags for saveRevision()!
		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$rev = $updater->saveRevision( $summary );

		$this->assertNotNull( $rev );
		$this->assertSame( $parentId, $rev->getParentId() );
		$this->assertSame( $summary->text, $rev->getComment( RevisionRecord::RAW )->text );
		$this->assertSame( $user->getName(), $rev->getUser( RevisionRecord::RAW )->getName() );

		$this->assertTrue( $updater->wasCommitted(), 'wasCommitted()' );
		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertTrue( $updater->getStatus()->isOK(), 'getStatus()->isOK()' );
		$this->assertFalse( $updater->isNew(), 'isNew()' );
		$this->assertNotNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertInstanceOf(
			RevisionRecord::class,
			$updater->getStatus()->getNewRevision()
		);
		$this->assertTrue( $updater->wasRevisionCreated(), 'wasRevisionCreated()' );

		// check the EditResult object
		$this->assertSame( 7, $updater->getEditResult()->getOriginalRevisionId(),
			'EditResult::getOriginalRevisionId()' );
		$this->assertSame( 0, $updater->getEditResult()->getUndidRevId(),
			'EditResult::getUndidRevId()' );
		$this->assertFalse( $updater->getEditResult()->isNew(), 'EditResult::isNew()' );
		$this->assertFalse( $updater->getEditResult()->isRevert(), 'EditResult::isRevert()' );

		// TODO: Test null revision (with different user): new revision!

		$rev = $updater->getNewRevision();
		$revContent = $rev->getContent( SlotRecord::MAIN );
		$this->assertSame( 'Lorem Ipsum', $revContent->serialize(), 'revision content' );
		$this->assertTrue( $rev->isMinor(), 'RevisionRecord::isMinor()' );

		// were the WikiPage and Title objects updated?
		$this->assertTrue( $page->exists(), 'WikiPage::exists()' );
		$this->assertTrue( $title->exists(), 'Title::exists()' );
		$this->assertSame( $rev->getId(), $page->getLatest(), 'WikiPage::getLatest()' );
		$this->assertNotNull( $page->getRevisionRecord(), 'WikiPage::getRevisionRecord()' );

		// re-load
		$page2 = $wikiPageFactory->newFromTitle( $title );
		$this->assertTrue( $page2->exists(), 'WikiPage::exists()' );
		$this->assertSame( $rev->getId(), $page2->getLatest(), 'WikiPage::getLatest()' );
		$this->assertNotNull( $page2->getRevisionRecord(), 'WikiPage::getRevisionRecord()' );

		// Check RC entry
		$rc = $this->getRecentChangeFor( $rev->getId() );
		$this->assertNotNull( $rc, 'RecentChange' );

		// re-edit
		$updater = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new TextContent( 'dolor sit amet' ) );

		$summary = CommentStoreComment::newUnsavedComment( 're-edit' );
		$updater->saveRevision( $summary );
		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertTrue( $updater->getStatus()->isOK(), 'getStatus()->isOK()' );
		$this->assertNotNull( $updater->getNewRevision(), 'getNewRevision()' );

		$topRevisionId = $updater->getNewRevision()->getId();

		// perform a null edit
		$updater = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new TextContent( 'dolor sit amet' ) );
		$summary = CommentStoreComment::newUnsavedComment( 'null edit' );
		$updater->saveRevision( $summary );

		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertTrue( $updater->getStatus()->isOK(), 'getStatus()->isOK()' );
		$this->assertFalse( $updater->wasRevisionCreated(), 'wasRevisionCreated()' );
		$this->assertTrue(
			$updater->getEditResult()->isNullEdit(),
			'getEditResult()->isNullEdit()'
		);
		$this->assertSame(
			$topRevisionId,
			$updater->getEditResult()->getOriginalRevisionId(),
			'getEditResult()->getOriginalRevisionId()'
		);

		// check site stats - this asserts that derived data updates where run.
		$stats = $this->getDb()->newSelectQueryBuilder()
			->select( '*' )
			->from( 'site_stats' )
			->where( '1=1' )
			->fetchRow();
		$this->assertNotNull( $stats, 'site_stats' );
		$this->assertSame( $oldStats->ss_total_pages + 0, (int)$stats->ss_total_pages );
		$this->assertSame( $oldStats->ss_total_edits + 2, (int)$stats->ss_total_edits );
	}

	/**
	 * Regression test for T379152
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 */
	public function testRevisionFromEditComplete() {
		$user = $this->getTestUser()->getUser();
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();
		$tagsStore = $this->getServiceContainer()->getChangeTagsStore();

		$this->expectHook(
			'RevisionFromEditComplete', 2,
			static function ( $wikiPage, $rev, $originalRevId, $user, &$tags ) {
				$tags[] = ( $rev->getParentId() ? 'test_updated' : 'test_created' );
			}
		);

		$title = $this->getDummyTitle( __METHOD__ );
		$page = $wikiPageFactory->newFromTitle( $title );
		$updater = $page->newPageUpdater( $user );

		$content = new TextContent( 'Lorem Ipsum' );
		$updater->setContent( SlotRecord::MAIN, $content );

		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$rev = $updater->saveRevision( $summary );

		$this->assertArrayContains(
			[ 'test_created' ],
			$tagsStore->getTags( $this->getDb(), null, $rev->getId() )
		);

		// Now, try an update
		$page = $wikiPageFactory->newFromTitle( $title );
		$updater = $page->newPageUpdater( $user );

		$content = new TextContent( 'Lorem Ipsum dolor sit amet' );
		$updater->setContent( SlotRecord::MAIN, $content );

		$summary = CommentStoreComment::newUnsavedComment( 'Next test' );
		$rev = $updater->saveRevision( $summary );

		$this->assertArrayContains(
			[ 'test_updated' ],
			$tagsStore->getTags( $this->getDb(), null, $rev->getId() )
		);
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::saveDummyRevision()
	 */
	public function testDummyRevision() {
		$page = $this->getExistingTestPage();
		$calls = [];

		$this->setTemporaryHook(
			'RevisionFromEditComplete',
			static function () use ( &$calls ) {
				$calls[] = 'RevisionFromEditComplete';
			}
		);

		$user = $this->getTestUser()->getUser();
		$updater = $page->newPageUpdater( $user );

		$oldRevId = $page->getLatest();

		$rev = $updater->saveDummyRevision( 'test', EDIT_MINOR );

		$this->assertNotSame( $oldRevId, $rev->getId() );
		$this->assertSame( $page->getLatest(), $rev->getId() );
		$this->assertTrue( $rev->isMinor(), 'isMinor' );

		$this->assertArrayContains(
			[ 'RevisionFromEditComplete' ],
			$calls
		);
	}

	private function makePageLatestChangedListener(
		array $flags,
		string $cause,
		UserIdentity $performer,
		?RevisionRecord $old,
		$revisionChange = true,
		$contentChange = true,
		$silent = false
	) {
		return static function ( PageLatestRevisionChangedEvent $event ) use (
			&$counter, $flags, $cause, $performer, $old,
			$revisionChange, $contentChange, $silent
		) {
			Assert::assertSame(
				$contentChange,
				$event->isEffectiveContentChange(),
				'isEffectiveContentChange'
			);
			Assert::assertSame( // not dummy, but could be null edit
				$contentChange || !$revisionChange,
				$event->isNominalContentChange(),
				'isNominalContentChange'
			);
			Assert::assertSame(
				$revisionChange,
				$event->changedLatestRevisionId(),
				'changedLatestRevisionId'
			);
			Assert::assertSame( // null edits
				!$revisionChange,
				$event->isReconciliationRequest(),
				'isReconciliationRequest'
			);
			Assert::assertSame(
				$old === null,
				$event->isCreation(),
				'isCreation'
			);
			Assert::assertSame(
				$silent,
				$event->isSilent(),
				'isSilent'
			);
			Assert::assertSame(
				$cause,
				$event->getCause(),
				'getCause'
			);
			Assert::assertSame(
				$performer,
				$event->getPerformer(),
				'getPerformer'
			);
			Assert::assertSame(
				$event->getLatestRevisionAfter()->getUser(),
				$event->getAuthor(),
				'getAuthor'
			);

			Assert::assertNotNull(
				$event->getEditResult(),
				'getEditResult'
			);

			if ( $old ) {
				Assert::assertSame(
					$old->getId(), $event->getLatestRevisionBefore()->getId(), 'getOldRevision'
				);
			} else {
				Assert::assertNull( $event->getLatestRevisionBefore(), 'getOldRevision' );
			}

			foreach ( $flags as $name => $value ) {
				Assert::assertSame( $value, $event->$name(), $name );
			}
		};
	}

	public function testEventEmission_new() {
		$user = $this->getTestUser()->getUser();
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();

		$title = $this->getDummyTitle( __METHOD__ );
		$page = $wikiPageFactory->newFromTitle( $title );
		$updater = $page->newPageUpdater( $user );

		$content = new TextContent( 'Lorem Ipsum' );
		$updater->setContent( SlotRecord::MAIN, $content );

		$this->expectDomainEvent(
			PageLatestRevisionChangedEvent::TYPE, 1,
			$this->makePageLatestChangedListener(
				[], PageLatestRevisionChangedEvent::CAUSE_EDIT, $user, null
			)
		);

		$this->expectDomainEvent(
			PageCreatedEvent::TYPE, 1,
			static function ( PageCreatedEvent $event ) use ( $content ) {
				Assert::assertSame(
					PageLatestRevisionChangedEvent::CAUSE_EDIT,
					$event->getCause(),
					'getCause'
				);

				Assert::assertNull(
					$event->getPageRecordBefore(),
					'getPageRecordBefore should return null'
				);

				Assert::assertNotSame(
					0,
					$event->getPageRecordAfter()->getId(),
					'getPageRecordAfter should return a valid PageRecord'
				);

				Assert::assertSame(
					$event->getPageRecordAfter()->getLatest(),
					$event->getLatestRevisionAfter()->getId()
				);

				Assert::assertSame(
					$content,
					$event->getLatestRevisionAfter()->getMainContentRaw()
				);

				Assert::assertSame( 'Just a test', $event->getReason() );
			}
		);

		$this->expectHook( 'RevisionFromEditComplete', 1 );
		$this->expectHook( 'PageSaveComplete', 1 );

		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$updater->saveRevision( $summary );
	}

	public function testEventEmission_edit() {
		$page = $this->getExistingTestPage();
		$user = $this->getTestUser()->getUser();

		$updater = $page->newPageUpdater( $user );

		$content = new TextContent( 'Lorem Ipsum' );
		$updater->setContent( SlotRecord::MAIN, $content );

		$this->expectDomainEvent(
			PageLatestRevisionChangedEvent::TYPE, 1,
			$this->makePageLatestChangedListener(
				[], PageLatestRevisionChangedEvent::CAUSE_EDIT,
				$user, $page->getRevisionRecord()
			)
		);

		// Also check that we can receive the event under its legacy name
		$this->expectDomainEvent(
			'PageRevisionUpdated', 1,
			$this->makePageLatestChangedListener(
				[], PageLatestRevisionChangedEvent::CAUSE_EDIT,
				$user, $page->getRevisionRecord()
			)
		);

		$this->expectHook( 'RevisionFromEditComplete', 1 );
		$this->expectHook( 'PageSaveComplete', 1 );

		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$updater->saveRevision( $summary );
	}

	public function testEventEmission_suppressed() {
		$page = $this->getExistingTestPage();
		$user = $this->getTestUser()->getUser();

		$this->runDeferredUpdates(); // flush

		$this->expectDomainEvent( PageLatestRevisionChangedEvent::TYPE, 0 );
		$this->expectHook( 'RevisionFromEditComplete', 0 );
		$this->expectHook( 'PageSaveComplete', 0 );

		$updater = $page->newPageUpdater( $user );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );

		$updater->setHints( [ 'suppressDerivedDataUpdates' => true ] )
			->saveRevision( 'Just a test' );
	}

	public function testEventEmission_implicit() {
		$page = $this->getExistingTestPage();
		$user = $this->getTestUser()->getUser();

		$updater = $page->newPageUpdater( $user );

		$content = new TextContent( 'Lorem Ipsum' );
		$updater->setContent( SlotRecord::MAIN, $content );
		$updater->setFlags( EDIT_IMPLICIT );

		$this->expectDomainEvent(
			PageLatestRevisionChangedEvent::TYPE, 1,
			$this->makePageLatestChangedListener(
				[ 'isImplicit' => true ],
				PageLatestRevisionChangedEvent::CAUSE_EDIT,
				$user,
				$page->getRevisionRecord()
			)
		);

		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$updater->saveRevision( $summary );
	}

	public function testEventEmission_null() {
		$page = $this->getExistingTestPage();
		$user = $this->getTestUser()->getUser();

		$updater = $page->newPageUpdater( $user );

		$this->expectDomainEvent(
			PageLatestRevisionChangedEvent::TYPE, 1,
			$this->makePageLatestChangedListener(
				[], PageLatestRevisionChangedEvent::CAUSE_EDIT,
					$user, $page->getRevisionRecord(), false, false
			)
		);

		$this->expectHook( 'RevisionFromEditComplete', 0 );
		$this->expectHook( 'PageSaveComplete', 1 );

		// null-edit
		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$updater->saveRevision( $summary );
	}

	public function testEventEmission_dummy() {
		$page = $this->getExistingTestPage();
		$user = $this->getTestUser()->getUser();

		$updater = $page->newPageUpdater( $user );

		$this->expectDomainEvent(
			PageLatestRevisionChangedEvent::TYPE, 1,
			$this->makePageLatestChangedListener(
				[], PageLatestRevisionChangedEvent::CAUSE_UNDELETE,
					$user, $page->getRevisionRecord(), true, false, true
			)
		);

		$this->expectHook( 'RevisionFromEditComplete', 1 );
		$this->expectHook( 'PageSaveComplete', 1 );

		// dummy revision
		$updater->setCause( PageLatestRevisionChangedEvent::CAUSE_UNDELETE );
		$updater->saveDummyRevision( 'Just a test', EDIT_SILENT | EDIT_MINOR );
	}

	public function testEventEmission_revert() {
		$page = $this->getExistingTestPage();
		$originalContent = $page->getContent();

		$this->editPage( $page, 'Other content for ' . __METHOD__ );
		$this->assertFalse( $page->getContent()->equals( $originalContent ) );

		$user = $this->getTestUser()->getUser();
		$updater = $page->newPageUpdater( $user );

		$this->expectDomainEvent(
			PageLatestRevisionChangedEvent::TYPE, 1,
			$this->makePageLatestChangedListener(
				[ 'isRevert' => true ], PageLatestRevisionChangedEvent::CAUSE_EDIT,
				$user, $page->getRevisionRecord()
			)
		);

		$this->expectHook( 'RevisionFromEditComplete', 1 );
		$this->expectHook( 'PageSaveComplete', 1 );

		// revert to original content
		$updater->setContent( SlotRecord::MAIN, $originalContent );
		$updater->markAsRevert( EditResult::REVERT_MANUAL, $page->getLatest() );
		$updater->saveRevision( 'Just a test' );
	}

	public function testEventEmission_derived() {
		$page = $this->getExistingTestPage();
		$user = $this->getTestUser()->getUser();

		$updater = $page->newPageUpdater( $user );

		$this->expectDomainEvent( PageLatestRevisionChangedEvent::TYPE, 0 );

		$this->expectHook( 'RevisionFromEditComplete', 0 );

		// NOTE: it's not clear whether PageSaveComplete should relaly be fired here
		$this->expectHook( 'PageSaveComplete', 1 );

		// derived slot update
		$content = new WikitextContent( 'A' );
		$derived = SlotRecord::newDerived( 'derivedslot', $content );
		$updater->setSlot( $derived );
		$updater->updateRevision();
	}

	public static function provideUpdatePropagation() {
		static $counter = 1;
		$name = strtr( __METHOD__, '\\:', '--' ) . $counter++;

		yield 'article' => [ PageIdentityValue::localIdentity( 0, NS_MAIN, $name ) ];
		yield 'user talk' => [
			PageIdentityValue::localIdentity( 0, NS_USER_TALK, $name ),
			null,
			$name,
		];
		yield 'message' => [ PageIdentityValue::localIdentity( 0, NS_MEDIAWIKI, $name ) ];
		yield 'script' => [
			PageIdentityValue::localIdentity( 0, NS_USER, "$name/common.js" ),
			new JavaScriptContent( 'console.log("hi")' ),
		];
	}

	private function makeUser( string $name ) {
		$user = $this->getServiceContainer()->getUserFactory()
			->newFromName( $name );

		$user->addToDatabase();
		return $user;
	}

	/**
	 * Test update propagation.
	 * Includes regression test for T381225
	 * @dataProvider provideUpdatePropagation
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 */
	public function testUpdatePropagation( PageIdentity $title, $content = null, $userName = null ) {
		if ( $userName ) {
			// For testing talk page behavior, the corresponding user must exist.
			$this->makeUser( $userName );
		}

		$user = $this->getTestUser()->getUser();
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();

		$page = $wikiPageFactory->newFromTitle( $title );
		$content ??= new TextContent( 'Lorem Ipsum' );

		$this->expectChangeTrackingUpdates(
			1, 0, 1,
			$page->getNamespace() === NS_USER_TALK ? 1 : 0,
			1
		);

		$this->expectSearchUpdates( 1 );
		$this->expectLocalizationUpdate( $page->getNamespace() === NS_MEDIAWIKI ? 1 : 0 );
		$this->expectResourceLoaderUpdates(
			$content->getModel() === CONTENT_MODEL_JAVASCRIPT ? 1 : 0
		);

		// Perform edit
		$updater = $page->newPageUpdater( $user );
		$updater->setContent( SlotRecord::MAIN, $content );

		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$updater->saveRevision( $summary );

		// NOTE: assertions are applied by the spies installed earlier.
		$this->runDeferredUpdates();
	}

	/**
	 * Test update propagation for null edits.
	 * @dataProvider provideUpdatePropagation
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 */
	public function testUpdatePropagation_null( PageIdentity $title, $content = null, $userName = null ) {
		if ( $userName ) {
			// For testing talk page behavior, the corresponding user must exist.
			$this->makeUser( $userName );
		}

		$user = $this->getTestUser()->getUser();
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();

		$wikiPageFactory->newFromTitle( $title );
		$content ??= new TextContent( 'Lorem Ipsum' );
		$this->editPage( $title, $content );

		// Flush...
		$this->runJobs();
		$page = $wikiPageFactory->newFromTitle( $title );

		// Null edits should not go into recentchanges, should not
		// increment counters, and should not trigger talk page notifications.
		$this->expectChangeTrackingUpdates( 0, 0, 0, 0, 0 );

		// Update derived data on null edits
		$this->expectSearchUpdates( 1 );
		$this->expectLocalizationUpdate(
			$page->getNamespace() === NS_MEDIAWIKI ? 1 : 0
		);

		// NOTE: The resource loader cache is currently purged *twice*
		// for null edits. That's not necessary and may change.
		$this->expectResourceLoaderUpdates(
			$content->getModel() === CONTENT_MODEL_JAVASCRIPT ? 2 : 0
		);

		// Do null edit
		$updater = $page->newPageUpdater( $user );
		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$updater->saveRevision( $summary );
	}

	/**
	 * Test update propagation for dummy revisions.
	 * @dataProvider provideUpdatePropagation
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 */
	public function testUpdatePropagation_dummy( PageIdentity $title, $content = null, $userName = null ) {
		if ( $userName ) {
			// For testing talk page behavior, the corresponding user must exist.
			$this->makeUser( $userName );
		}

		$user = $this->getTestUser()->getUser();
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();

		$wikiPageFactory->newFromTitle( $title );
		$content ??= new TextContent( 'Lorem Ipsum' );
		$this->editPage( $title, $content );

		// Flush...
		$this->runJobs();
		$page = $wikiPageFactory->newFromTitle( $title );

		// Silent dummy revisions should not go into recentchanges,
		// should not increment counters, and should not trigger talk page
		// notifications.
		$this->expectChangeTrackingUpdates( 0, 0, 0, 0, 0 );

		// Do not update derived data on dummy revisions!
		$this->expectSearchUpdates( 0 );
		$this->expectLocalizationUpdate( 0 );
		$this->expectResourceLoaderUpdates( 0 );

		// Create dummy revision
		$updater = $page->newPageUpdater( $user );
		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$updater->setForceEmptyRevision( true ); // dummy revision, not null edit
		$updater->saveRevision( $summary, EDIT_SUPPRESS_RC );
	}

	public function testSetForceEmptyRevisionSetsOriginalRevisionId() {
		$user = $this->getTestUser()->getUser();
		$title = $this->getDummyTitle( __METHOD__ );
		$this->insertPage( $title );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$parentId = $page->getLatest();
		$updater = $page->newPageUpdater( $user );
		$updater->setForceEmptyRevision( true );
		// Saving without changing the content should now create a new revisiopns
		$summary = CommentStoreComment::newUnsavedComment( 'dummy revision' );
		$rev = $updater->saveRevision( $summary );
		$status = $updater->getStatus();
		$this->assertNotNull( $rev, 'getNewRevision()' );
		$this->assertNotNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertNotSame( $parentId, $rev->getId(), 'new revision ID' );
		$this->assertTrue( $updater->wasRevisionCreated(), 'wasRevisionCreated' );
		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertStatusGood( $status );
		// Setting setForceEmptyRevision causes the original revision to be set.
		$this->assertEquals( $parentId, $updater->getEditResult()->getOriginalRevisionId() );
	}

	public function testSetForceEmptyRevisionCausesSaveToFailWithChangedContent() {
		$user = $this->getTestUser()->getUser();
		$title = $this->getDummyTitle( __METHOD__ );
		$this->insertPage( $title );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$updater = $page->newPageUpdater( $user );
		$updater->setForceEmptyRevision( true );
		// Setting setForceEmptyRevision causes saveRevision() to fail if the content is changed.
		// The positive case with setForceEmptyRevision() causing a new revision to be created
		// is tested
		$this->expectException( LogicException::class );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Changed Content' ) );
		$summary = CommentStoreComment::newUnsavedComment( 'dummy revision' );
		$updater->saveRevision( $summary );
	}

	public function testRevert() {
		// Setup a page with some edits
		$page = $this->getExistingTestPage( __METHOD__ );

		$user = $this->getTestUser()->getUser();

		$summary = CommentStoreComment::newUnsavedComment( '1' );
		$updater = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new TextContent( '1' ) );
		$updater->saveRevision( $summary );
		$revId1 = $updater->getNewRevision()->getId();

		$summary = CommentStoreComment::newUnsavedComment( '2' );
		$updater = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new TextContent( '2' ) );
		$updater->saveRevision( $summary );
		$revId2 = $updater->getNewRevision()->getId();

		// Perform a rollback
		$updater = $page->newPageUpdater( $this->getTestSysop()->getUser() )
			->setContent( SlotRecord::MAIN, new TextContent( '1' ) )
			->markAsRevert( EditResult::REVERT_ROLLBACK, $revId2, $revId1 );
		$summary = CommentStoreComment::newUnsavedComment( 'revert' );
		$updater->saveRevision( $summary );

		// Do some basic assertions on PageUpdater
		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertTrue( $updater->getStatus()->isOK(), 'getStatus()->isOK()' );

		$editResult = $updater->getEditResult();
		$this->assertNotNull( $editResult, 'getEditResult()' );
		$this->assertTrue( $editResult->isRevert(), 'EditResult::isRevert()' );
		$this->assertTrue( $editResult->isExactRevert(), 'EditResult::isExactRevert()' );
		$this->assertSame(
			$revId1,
			$editResult->getOriginalRevisionId(),
			'EditResult::getOriginalRevisionId()'
		);
		$this->assertSame(
			EditResult::REVERT_ROLLBACK,
			$editResult->getRevertMethod(),
			'EditResult::getRevertMethod()'
		);
		$this->assertSame(
			$revId2,
			$editResult->getOldestRevertedRevisionId(),
			'EditResult::getOldestRevertedRevisionId()'
		);
		$this->assertSame(
			$revId2,
			$editResult->getNewestRevertedRevisionId(),
			'EditResult::getNewestRevertedRevisionId()'
		);

		// Ensure all deferred updates are run
		DeferredUpdates::doUpdates();

		// Retrieve the mw-rollback change tag and verify it
		$newRevId = $updater->getNewRevision()->getId();
		$this->newSelectQueryBuilder()
			->select( 'ct_params' )
			->from( 'change_tag' )
			->where( [ 'ct_rev_id' => $newRevId ] )
			->assertFieldValue( FormatJson::encode( $editResult ) );
	}

	/**
	 * Creates a revision in the database.
	 *
	 * @param WikiPage $page
	 * @param string|Message|CommentStoreComment $summary
	 * @param null|string|Content $content
	 *
	 * @return RevisionRecord|null
	 */
	private function createRevision( WikiPage $page, $summary, $content = null ) {
		$user = $this->getTestUser()->getUser();

		$comment = CommentStoreComment::newUnsavedComment( $summary );

		if ( !$content instanceof Content ) {
			$content = new TextContent( $content ?? $summary );
		}

		return $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, $content )
			->saveRevision( $comment );
	}

	/**
	 * Verify that MultiContentSave hook is called by saveRevision() with correct parameters.
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 */
	public function testMultiContentSaveHook() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );

		// TODO: MCR: test additional slots
		$slots = [
			SlotRecord::MAIN => new TextContent( 'Lorem Ipsum' )
		];

		// start editing non-existing page
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$updater = $page->newPageUpdater( $user );
		foreach ( $slots as $slot => $content ) {
			$updater->setContent( $slot, $content );
		}

		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );

		$expected = [
			'user' => $user,
			'title' => $title,
			'slots' => $slots,
			'summary' => $summary
		];
		$hookFired = false;
		$this->expectHook( 'MultiContentSave', 1,
			function ( RenderedRevision $renderedRevision, UserIdentity $user,
				$summary, $flags, Status $hookStatus
			) use ( &$hookFired, $expected ) {
				$hookFired = true;

				$this->assertSame( $expected['summary'], $summary );
				$this->assertSame( EDIT_NEW, $flags );

				$this->assertTrue(
					$expected['title']->isSamePageAs( $renderedRevision->getRevision()->getPage() )
				);
				$this->assertTrue(
					$expected['title']->isSameLinkAs( $renderedRevision->getRevision()->getPageAsLinkTarget() )
				);

				$slots = $renderedRevision->getRevision()->getSlots();
				foreach ( $expected['slots'] as $slot => $content ) {
					$this->assertSame( $content, $slots->getSlot( $slot )->getContent() );
				}

				// Don't abort this edit.
				return true;
			}
		);

		$rev = $updater->saveRevision( $summary );
		$this->assertTrue( $hookFired, "MultiContentSave hook wasn't called." );
		$this->assertNotNull( $rev,
			"MultiContentSave returned true, but revision wasn't created." );
	}

	/**
	 * Verify that MultiContentSave hook can abort saveRevision() by returning false.
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 */
	public function testMultiContentSaveHookAbort() {
		$user = $this->getTestUser()->getUser();
		$title = $this->getDummyTitle( __METHOD__ );

		// start editing non-existing page
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$updater = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );

		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );

		$expectedError = 'aborted-by-test-hook';
		$this->expectHook( 'MultiContentSave', 1,
			static function ( RenderedRevision $renderedRevision, UserIdentity $user,
				$summary, $flags, Status $hookStatus
			) use ( $expectedError ) {
				$hookStatus->fatal( $expectedError );

				// Returning false should disallow saveRevision() to continue saving this revision.
				return false;
			}
		);

		$rev = $updater->saveRevision( $summary );
		$this->assertNull( $rev,
			"MultiContentSave returned false, but revision was still created." );

		$status = $updater->getStatus();
		$this->assertStatusError( $expectedError, $status,
			"MultiContentSave returned false, but Status is not fatal." );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::grabParentRevision()
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 */
	public function testCompareAndSwapFailure() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();

		// start editing non-existing page
		$page = $wikiPageFactory->newFromTitle( $title );
		$updater = $page->newPageUpdater( $user );
		$updater->grabParentRevision();

		// create page concurrently
		$concurrentPage = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$this->createRevision( $concurrentPage, __METHOD__ . '-one' );

		// try creating the page - should trigger CAS failure.
		$summary = CommentStoreComment::newUnsavedComment( 'create?!' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem ipsum' ) );
		$updater->saveRevision( $summary );
		$status = $updater->getStatus();

		$this->assertFalse( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertStatusError( 'edit-already-exists', $status, 'edit-conflict' );

		// start editing existing page
		$page = $wikiPageFactory->newFromTitle( $title );
		$updater = $page->newPageUpdater( $user );
		$updater->grabParentRevision();

		// update page concurrently
		$concurrentPage = $wikiPageFactory->newFromTitle( $title );
		$this->createRevision( $concurrentPage, __METHOD__ . '-two' );

		// try creating the page - should trigger CAS failure.
		$summary = CommentStoreComment::newUnsavedComment( 'edit?!' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'dolor sit amet' ) );
		$updater->saveRevision( $summary );
		$status = $updater->getStatus();

		$this->assertFalse( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertStatusError( 'edit-conflict', $status, 'edit-conflict' );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 */
	public function testFailureOnEditFlags() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );

		// start editing non-existing page
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$updater = $page->newPageUpdater( $user );

		// update with EDIT_UPDATE flag should fail
		$summary = CommentStoreComment::newUnsavedComment( 'udpate?!' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem ipsum' ) );
		$updater->saveRevision( $summary, EDIT_UPDATE );
		$status = $updater->getStatus();

		$this->assertFalse( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertStatusError( 'edit-gone-missing', $status, 'edit-gone-missing' );

		// create the page
		$this->createRevision( $page, __METHOD__ );

		// update with EDIT_NEW flag should fail
		$summary = CommentStoreComment::newUnsavedComment( 'create?!' );
		$updater = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new TextContent( 'dolor sit amet' ) );
		$updater->saveRevision( $summary, EDIT_NEW );
		$status = $updater->getStatus();

		$this->assertFalse( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertStatusError( 'edit-already-exists', $status, 'edit-already-exists' );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 */
	public function testFailureOnBadContentModel() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );

		// start editing non-existing page
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		// plain text content should fail in aux slot (the main slot doesn't care)
		$updater = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new TextContent( 'Main Content' ) )
			->setContent( 'aux', new TextContent( 'Aux Content' ) );

		$summary = CommentStoreComment::newUnsavedComment( 'udpate?!' );
		$updater->saveRevision( $summary, EDIT_UPDATE );
		$status = $updater->getStatus();

		$this->assertFalse( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertStatusError( 'content-not-allowed-here', $status );
	}

	public static function provideSetRcPatrolStatus() {
		yield [ RecentChange::PRC_UNPATROLLED ];
		yield [ RecentChange::PRC_AUTOPATROLLED ];
	}

	/**
	 * @dataProvider provideSetRcPatrolStatus
	 * @covers \MediaWiki\Storage\PageUpdater::setRcPatrolStatus()
	 */
	public function testSetRcPatrolStatus( $patrolled ) {
		$revisionStore = $this->getServiceContainer()->getRevisionStore();

		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$summary = CommentStoreComment::newUnsavedComment( 'Lorem ipsum ' . $patrolled );
		$rev = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new TextContent( 'Lorem ipsum ' . $patrolled ) )
			->setRcPatrolStatus( $patrolled )
			->saveRevision( $summary );

		$rc = $revisionStore->getRecentChange( $rev );
		$this->assertEquals( $patrolled, $rc->getAttribute( 'rc_patrolled' ) );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::makeNewRevision()
	 */
	public function testStalePageID() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$summary = CommentStoreComment::newUnsavedComment( 'testing...' );
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();

		// Create page
		$page = $wikiPageFactory->newFromTitle( $title );
		$updater = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new TextContent( 'Content 1' ) );
		$updater->saveRevision( $summary, EDIT_NEW );
		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );

		// Create a clone of $title and $page.
		$title = Title::makeTitle( $title->getNamespace(), $title->getDBkey() );
		$page = $wikiPageFactory->newFromTitle( $title );

		// start editing existing page using bad page ID
		$updater = $page->newPageUpdater( $user );
		$updater->grabParentRevision();

		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Content 2' ) );

		// Force the article ID to something invalid,
		// to emulate confusion due to a page move.
		$title->resetArticleID( 886655 );

		@$updater->saveRevision( $summary, EDIT_UPDATE );

		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::inheritSlot()
	 * @covers \MediaWiki\Storage\PageUpdater::setContent()
	 */
	public function testInheritSlot() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$updater = $page->newPageUpdater( $user );
		$summary = CommentStoreComment::newUnsavedComment( 'one' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem ipsum' ) );
		$rev1 = $updater->saveRevision( $summary, EDIT_NEW );

		$updater = $page->newPageUpdater( $user );
		$summary = CommentStoreComment::newUnsavedComment( 'two' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Foo Bar' ) );
		$rev2 = $updater->saveRevision( $summary, EDIT_UPDATE );

		$updater = $page->newPageUpdater( $user );
		$summary = CommentStoreComment::newUnsavedComment( 'three' );
		$updater->inheritSlot( $rev1->getSlot( SlotRecord::MAIN ) );
		$rev3 = $updater->saveRevision( $summary, EDIT_UPDATE );

		$this->assertNotSame( $rev1->getId(), $rev3->getId() );
		$this->assertNotSame( $rev2->getId(), $rev3->getId() );

		$main1 = $rev1->getSlot( SlotRecord::MAIN );
		$main3 = $rev3->getSlot( SlotRecord::MAIN );

		$this->assertNotSame( $main1->getRevision(), $main3->getRevision() );
		$this->assertSame( $main1->getAddress(), $main3->getAddress() );
		$this->assertTrue( $main1->getContent()->equals( $main3->getContent() ) );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::setSlot()
	 * @covers \MediaWiki\Storage\PageUpdater::updateRevision()
	 */
	public function testUpdatingDerivedSlot() {
		$user = $this->getTestUser()->getUser();
		$title = $this->getDummyTitle( __METHOD__ );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$updater = $page->newPageUpdater( $user );
		$summary = CommentStoreComment::newUnsavedComment( 'one' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem ipsum' ) );
		$updater->saveRevision( $summary, EDIT_NEW );

		// Clear pending jobs so the spies don't get confused
		$this->runJobs();

		$this->expectChangeTrackingUpdates( 0, 0, 0, 0, 0 );
		$this->expectSearchUpdates( 0 );

		$updater = $page->newPageUpdater( $user );
		$content = new WikitextContent( 'A' );
		$derived = SlotRecord::newDerived( 'derivedslot', $content );
		$updater->setSlot( $derived );
		$updater->updateRevision();

		$status = $updater->getStatus();
		$this->assertStatusGood( $status );
		$rev = $status->getNewRevision();
		$slot = $rev->getSlot( 'derivedslot' );
		$this->assertTrue( $slot->getContent()->equals( $content ) );

		// Make sure all events are processed so the spies are happy
		$this->runDeferredUpdates();
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::setSlot()
	 * @covers \MediaWiki\Storage\PageUpdater::updateRevision()
	 */
	public function testUpdatingDerivedSlotCurrentRevision() {
		$user = $this->getTestUser()->getUser();
		$title = $this->getDummyTitle( __METHOD__ );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$updater = $page->newPageUpdater( $user );
		$summary = CommentStoreComment::newUnsavedComment( 'one' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem ipsum' ) );
		$rev1 = $updater->saveRevision( $summary, EDIT_NEW );

		$updater = $page->newPageUpdater( $user );
		$content = new WikitextContent( 'A' );
		$derived = SlotRecord::newDerived( 'derivedslot', $content );
		$updater->setSlot( $derived );
		$updater->updateRevision( $rev1->getId( $rev1->getWikiId() ) );

		$rev2 = $updater->getStatus()->getNewRevision();
		$slot = $rev2->getSlot( 'derivedslot' );
		$this->assertTrue( $slot->getContent()->equals( $content ) );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::setSlot()
	 * @covers \MediaWiki\Storage\PageUpdater::updateRevision()
	 */
	public function testUpdatingDerivedSlotOldRevision() {
		$user = $this->getTestUser()->getUser();
		$title = $this->getDummyTitle( __METHOD__ );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$updater = $page->newPageUpdater( $user );
		$summary = CommentStoreComment::newUnsavedComment( 'one' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem ipsum' ) );
		$rev1 = $updater->saveRevision( $summary, EDIT_NEW );

		$updater = $page->newPageUpdater( $user );
		$summary = CommentStoreComment::newUnsavedComment( 'two' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Something different' ) );
		$updater->saveRevision( $summary, EDIT_UPDATE );

		$updater = $page->newPageUpdater( $user );
		$content = new WikitextContent( 'A' );
		$derived = SlotRecord::newDerived( 'derivedslot', $content );
		$updater->setSlot( $derived );
		$updater->updateRevision( $rev1->getId( $rev1->getWikiId() ) );

		$rev3 = $updater->getStatus()->getNewRevision();
		$slot = $rev3->getSlot( 'derivedslot' );
		$this->assertTrue( $slot->getContent()->equals( $content ) );
	}

	// TODO: MCR: test adding multiple slots, inheriting parent slots, and removing slots.

	public function testSetUseAutomaticEditSummaries() {
		$this->setContentLang( 'qqx' );
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();
		$page = $wikiPageFactory->newFromTitle( $title );

		$updater = $page->newPageUpdater( $user )
			->setUseAutomaticEditSummaries( true )
			->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );

		// empty comment triggers auto-summary
		$summary = CommentStoreComment::newUnsavedComment( '' );
		$updater->saveRevision( $summary, EDIT_AUTOSUMMARY );

		$rev = $updater->getNewRevision();
		$comment = $rev->getComment( RevisionRecord::RAW );
		$this->assertSame( '(autosumm-new: Lorem Ipsum)', $comment->text, 'comment text' );

		// check that this also works when blanking the page
		$updater = $page->newPageUpdater( $user )
			->setUseAutomaticEditSummaries( true )
			->setContent( SlotRecord::MAIN, new TextContent( '' ) );

		$summary = CommentStoreComment::newUnsavedComment( '' );
		$updater->saveRevision( $summary, EDIT_AUTOSUMMARY );

		$rev = $updater->getNewRevision();
		$comment = $rev->getComment( RevisionRecord::RAW );
		$this->assertSame( '(autosumm-blank)', $comment->text, 'comment text' );

		// check that we can also disable edit-summaries
		$title2 = $this->getDummyTitle( __METHOD__ . '/2' );
		$page2 = $wikiPageFactory->newFromTitle( $title2 );

		$updater = $page2->newPageUpdater( $user )
			->setUseAutomaticEditSummaries( false )
			->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );

		$summary = CommentStoreComment::newUnsavedComment( '' );
		$updater->saveRevision( $summary, EDIT_AUTOSUMMARY );

		$rev = $updater->getNewRevision();
		$comment = $rev->getComment( RevisionRecord::RAW );
		$this->assertSame( '', $comment->text, 'comment text should still be blank' );

		// check that we don't do auto.summaries without the EDIT_AUTOSUMMARY flag
		$updater = $page2->newPageUpdater( $user )
			->setUseAutomaticEditSummaries( true )
			->setContent( SlotRecord::MAIN, new TextContent( '' ) );

		$summary = CommentStoreComment::newUnsavedComment( '' );
		$updater->saveRevision( $summary, 0 );

		$rev = $updater->getNewRevision();
		$comment = $rev->getComment( RevisionRecord::RAW );
		$this->assertSame( '', $comment->text, 'comment text' );
	}

	public static function provideSetUsePageCreationLog() {
		yield [ true, [ [ 'create', 'create' ] ] ];
		yield [ false, [] ];
	}

	/**
	 * @dataProvider provideSetUsePageCreationLog
	 * @covers \MediaWiki\RecentChanges\ChangeTrackingEventIngress
	 */
	public function testSetUsePageCreationLog( $use, $expected ) {
		$this->hideDeprecated( 'MediaWiki\Storage\PageUpdater::setUsePageCreationLog' );

		$services = $this->getServiceContainer();
		$ingress = ChangeTrackingEventIngress::newForTesting(
			$services->getChangeTagsStore(),
			$services->getUserEditTracker(),
			$services->getPermissionManager(),
			$services->getWikiPageFactory(),
			$services->getHookContainer(),
			$services->getUserNameUtils(),
			$services->getTalkPageNotificationManager(),
			$services->getMainConfig(),
			$services->getJobQueueGroup(),
			$services->getContentHandlerFactory(),
			$services->getRecentChangeStore()
		);

		$services->getDomainEventSource()
			->registerSubscriber( $ingress );

		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ . ( $use ? '_logged' : '_unlogged' ) );
		$page = $services->getWikiPageFactory()->newFromTitle( $title );

		$summary = CommentStoreComment::newUnsavedComment( 'cmt' );
		$updater = $page->newPageUpdater( $user )
			->setUsePageCreationLog( $use )
			->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );

		$updater->saveRevision( $summary, EDIT_NEW );

		$rev = $updater->getNewRevision();
		$this->newSelectQueryBuilder()
			->select( [ 'log_type', 'log_action' ] )
			->from( 'logging' )
			->where( [ 'log_page' => $rev->getPageId() ] )
			->assertResultSet( $expected );
	}

	public static function provideMagicWords() {
		yield 'PAGEID' => [
			'Test {{PAGEID}} Test',
			static function ( RevisionRecord $rev ) {
				return $rev->getPageId();
			}
		];

		yield 'REVISIONID' => [
			'Test {{REVISIONID}} Test',
			static function ( RevisionRecord $rev ) {
				return $rev->getId();
			}
		];

		yield 'REVISIONUSER' => [
			'Test {{REVISIONUSER}} Test',
			static function ( RevisionRecord $rev ) {
				return $rev->getUser()->getName();
			}
		];

		yield 'REVISIONTIMESTAMP' => [
			'Test {{REVISIONTIMESTAMP}} Test',
			static function ( RevisionRecord $rev ) {
				return $rev->getTimestamp();
			}
		];

		yield 'subst:REVISIONUSER' => [
			'Test {{subst:REVISIONUSER}} Test',
			static function ( RevisionRecord $rev ) {
				return $rev->getUser()->getName();
			},
			'subst'
		];

		yield 'subst:PAGENAME' => [
			'Test {{subst:PAGENAME}} Test',
			static function ( RevisionRecord $rev ) {
				return 'PageUpdaterTest::testMagicWords';
			},
			'subst'
		];
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 *
	 * Integration test for PageUpdater, DerivedPageDataUpdater, RevisionRenderer
	 * and RenderedRevision, that ensures that magic words depending on revision meta-data
	 * are handled correctly. Note that each magic word needs to be tested separately,
	 * to assert correct behavior for each "vary" flag in the ParserOutput.
	 *
	 * @dataProvider provideMagicWords
	 */
	public function testMagicWords( $wikitext, $callback, $subst = false ) {
		$user = User::newFromName( 'A user for ' . __METHOD__ );
		$user->addToDatabase();

		$title = $this->getDummyTitle( __METHOD__ );
		$this->insertPage( $title );

		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$updater = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new \MediaWiki\Content\WikitextContent( $wikitext ) );

		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$rev = $updater->saveRevision( $summary, EDIT_UPDATE );

		if ( !$rev ) {
			$this->fail( $updater->getStatus()->getWikiText() );
		}

		$expected = strval( $callback( $rev ) );

		$output = $page->getParserOutput( ParserOptions::newFromAnon() );
		$html = $output->getRawText();
		$text = $rev->getContent( SlotRecord::MAIN )->serialize();

		if ( $subst ) {
			$this->assertStringContainsString( $expected, $text, 'In Wikitext' );
		}

		$this->assertStringContainsString( $expected, $html, 'In HTML' );
	}

	public function testChangeTagsSuppressRecentChange() {
		$page = PageIdentityValue::localIdentity( 0, NS_MAIN, __METHOD__ );
		$wikiPageFactory = $this->getServiceContainer()->getWikiPageFactory();
		$revision = $this->getServiceContainer()
			->getPageUpdaterFactory()
			->newPageUpdater(
				$wikiPageFactory->newFromTitle( $page ),
				$this->getTestUser()->getUser()
			)
			->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) )
			->addTag( 'foo' )
			->setFlags( EDIT_SUPPRESS_RC )
			->saveRevision( CommentStoreComment::newUnsavedComment( 'Comment' ) );
		$this->assertArrayEquals(
			[ 'foo' ],
			$this->getServiceContainer()->getChangeTagsStore()->getTags(
				$this->getDb(), null, $revision->getId()
			)
		);

		$revision2 = $this->getServiceContainer()
			->getPageUpdaterFactory()
			->newPageUpdater(
				$wikiPageFactory->newFromTitle( $page ),
				$this->getTestUser()->getUser()
			)
			->setContent( SlotRecord::MAIN, new TextContent( 'Other content' ) )
			->addTag( 'bar' )
			->setFlags( EDIT_SUPPRESS_RC )
			->saveRevision( CommentStoreComment::newUnsavedComment( 'Comment' ) );
		$this->assertArrayEquals(
			[ 'bar' ],
			$this->getServiceContainer()->getChangeTagsStore()->getTags(
				$this->getDb(), null, $revision2->getId()
			)
		);
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::prepareUpdate()
	 * @covers \MediaWiki\Page\WikiPage::getCurrentUpdate()
	 */
	public function testPrepareUpdate() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$updater = $page->newPageUpdater( $user );

		$this->assertSame( $page->getCurrentUpdate(), $updater->prepareUpdate() );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::preventChange
	 * @covers \MediaWiki\Storage\PageUpdater::doModify
	 * @covers \MediaWiki\Storage\PageUpdater::isChange
	 */
	public function testPreventChange_modify() {
		$user = $this->getTestUser()->getUser();
		$title = $this->getDummyTitle( __METHOD__ );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$updater = $page->newPageUpdater( $user );

		// Creation
		$summary = CommentStoreComment::newUnsavedComment( 'one' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem ipsum' ) );
		$updater->prepareUpdate();
		$rev = $updater->saveRevision( $summary, EDIT_NEW );
		$this->assertInstanceOf( RevisionRecord::class, $rev );

		// Null edit
		$updater = $page->newPageUpdater( $user );
		$summary = CommentStoreComment::newUnsavedComment( 'one' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem ipsum' ) );
		$updater->prepareUpdate();
		$updater->preventChange();
		$this->assertFalse( $updater->isChange() );
		$rev = $updater->saveRevision( $summary );
		$this->assertNull( $rev );

		// Prevented edit
		$updater = $page->newPageUpdater( $user );
		$summary = CommentStoreComment::newUnsavedComment( 'one' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'dolor sit amet' ) );
		$updater->prepareUpdate();
		$updater->preventChange();
		$this->expectException( LogicException::class );
		$updater->saveRevision( $summary );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::preventChange
	 * @covers \MediaWiki\Storage\PageUpdater::doCreate
	 * @covers \MediaWiki\Storage\PageUpdater::isChange
	 */
	public function testPreventChange_create() {
		$user = $this->getTestUser()->getUser();
		$title = $this->getDummyTitle( __METHOD__ );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$updater = $page->newPageUpdater( $user );
		$summary = CommentStoreComment::newUnsavedComment( 'one' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem ipsum' ) );
		$updater->prepareUpdate();
		$updater->preventChange();
		$this->assertTrue( $updater->isChange() );
		$this->expectException( LogicException::class );
		$updater->saveRevision( $summary, EDIT_NEW );
	}

	public function testUpdateAuthor() {
		$title = $this->getDummyTitle( __METHOD__ );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$user = new User;
		$user->setName( 'PageUpdaterTest' );
		$updater = $page->newPageUpdater( $user );
		$summary = CommentStoreComment::newUnsavedComment( 'one' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( '~~~~' ) );

		$user = User::createNew( $user->getName() );
		$updater->updateAuthor( $user );
		$rev = $updater->saveRevision( $summary, EDIT_NEW );
		$this->assertGreaterThan( 0, $rev->getUser()->getId() );
	}
}
