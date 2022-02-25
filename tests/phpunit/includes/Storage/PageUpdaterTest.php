<?php

namespace MediaWiki\Tests\Storage;

use ChangeTags;
use CommentStoreComment;
use Content;
use DeferredUpdates;
use FormatJson;
use LogicException;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\RenderedRevision;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\EditResult;
use MediaWiki\User\UserIdentity;
use MediaWikiIntegrationTestCase;
use Message;
use ParserOptions;
use RecentChange;
use Status;
use TextContent;
use Title;
use User;
use WikiPage;
use WikitextContent;

/**
 * @covers \MediaWiki\Storage\PageUpdater
 * @group Database
 */
class PageUpdaterTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

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

		$this->tablesUsed[] = 'logging';
		$this->tablesUsed[] = 'recentchanges';
		$this->tablesUsed[] = 'change_tag';
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
		$row = $this->db->selectRow(
			$qi['tables'],
			$qi['fields'],
			[ 'rc_this_oldid' => $revId ],
			__METHOD__,
			[],
			$qi['joins']
		);

		return $row ? RecentChange::newFromRow( $row ) : null;
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 * @covers \WikiPage::newPageUpdater()
	 */
	public function testCreatePage() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$page = WikiPage::factory( $title );
		$updater = $page->newPageUpdater( $user );

		$oldStats = $this->db->selectRow( 'site_stats', '*', '1=1' );

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
			$updater->getStatus()->value['revision-record']
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
		$page2 = WikiPage::factory( $title );
		$this->assertTrue( $page2->exists(), 'WikiPage::exists()' );
		$this->assertSame( $rev->getId(), $page2->getLatest(), 'WikiPage::getLatest()' );
		$this->assertNotNull( $page2->getRevisionRecord(), 'WikiPage::getRevisionRecord()' );

		// Check RC entry
		$rc = $this->getRecentChangeFor( $rev->getId() );
		$this->assertNotNull( $rc, 'RecentChange' );

		// check site stats - this asserts that derived data updates where run.
		$stats = $this->db->selectRow( 'site_stats', '*', '1=1' );
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
		$this->assertTrue( $status->isOK(), 'getStatus()->isOK()' );
		$this->assertTrue( $status->hasMessage( 'edit-no-change' ), 'edit-no-change' );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 * @covers \WikiPage::newPageUpdater()
	 */
	public function testUpdatePage() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$this->insertPage( $title );

		$page = WikiPage::factory( $title );
		$parentId = $page->getLatest();

		$updater = $page->newPageUpdater( $user );

		$oldStats = $this->db->selectRow( 'site_stats', '*', '1=1' );

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
			$updater->getStatus()->value['revision-record']
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
		$page2 = WikiPage::factory( $title );
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
		$stats = $this->db->selectRow( 'site_stats', '*', '1=1' );
		$this->assertNotNull( $stats, 'site_stats' );
		$this->assertSame( $oldStats->ss_total_pages + 0, (int)$stats->ss_total_pages );
		$this->assertSame( $oldStats->ss_total_edits + 2, (int)$stats->ss_total_edits );
	}

	public function testSetForceEmptyRevisionSetsOriginalRevisionId() {
		$user = $this->getTestUser()->getUser();
		$title = $this->getDummyTitle( __METHOD__ );
		$this->insertPage( $title );
		$page = WikiPage::factory( $title );
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
		$this->assertTrue( $status->isOK(), 'getStatus()->isOK()' );
		$this->assertFalse( $status->hasMessage( 'edit-no-change' ), 'edit-no-change' );
		// Setting setForceEmptyRevision causes the original revision to be set.
		$this->assertEquals( $parentId, $updater->getEditResult()->getOriginalRevisionId() );
	}

	public function testSetForceEmptyRevisionCausesSaveToFailWithChangedContent() {
		$user = $this->getTestUser()->getUser();
		$title = $this->getDummyTitle( __METHOD__ );
		$this->insertPage( $title );
		$page = WikiPage::factory( $title );
		$updater = $page->newPageUpdater( $user );
		$updater->setForceEmptyRevision( true );
		// Setting setForceEmptyRevision causes saveRevision() to fail if the content is changed.
		// The positive case with setForceEmptyRevision() causing a new revision to be created
		// is tested
		$this->expectException( LogicException::class );
		$updater->setContent( 'main', new TextContent( 'Changed Content' ) );
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
		$this->assertSelect(
			'change_tag',
			'ct_params',
			[ 'ct_rev_id' => $newRevId ],
			[ [ FormatJson::encode( $editResult ) ] ]
		);
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
		$page = WikiPage::factory( $title );
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
		$this->setTemporaryHook( 'MultiContentSave',
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
		$page = WikiPage::factory( $title );
		$updater = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );

		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );

		$expectedError = 'aborted-by-test-hook';
		$this->setTemporaryHook( 'MultiContentSave',
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
		$this->assertFalse( $status->isOK(),
			"MultiContentSave returned false, but Status is not fatal." );
		$this->assertSame( $expectedError, $status->getMessage()->getKey() );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::grabParentRevision()
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 */
	public function testCompareAndSwapFailure() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );

		// start editing non-existing page
		$page = WikiPage::factory( $title );
		$updater = $page->newPageUpdater( $user );
		$updater->grabParentRevision();

		// create page concurrently
		$concurrentPage = WikiPage::factory( $title );
		$this->createRevision( $concurrentPage, __METHOD__ . '-one' );

		// try creating the page - should trigger CAS failure.
		$summary = CommentStoreComment::newUnsavedComment( 'create?!' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem ipsum' ) );
		$updater->saveRevision( $summary );
		$status = $updater->getStatus();

		$this->assertFalse( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertFalse( $status->isOK(), 'getStatus()->isOK()' );
		$this->assertTrue( $status->hasMessage( 'edit-already-exists' ), 'edit-conflict' );

		// start editing existing page
		$page = WikiPage::factory( $title );
		$updater = $page->newPageUpdater( $user );
		$updater->grabParentRevision();

		// update page concurrently
		$concurrentPage = WikiPage::factory( $title );
		$this->createRevision( $concurrentPage, __METHOD__ . '-two' );

		// try creating the page - should trigger CAS failure.
		$summary = CommentStoreComment::newUnsavedComment( 'edit?!' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'dolor sit amet' ) );
		$updater->saveRevision( $summary );
		$status = $updater->getStatus();

		$this->assertFalse( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertFalse( $status->isOK(), 'getStatus()->isOK()' );
		$this->assertTrue( $status->hasMessage( 'edit-conflict' ), 'edit-conflict' );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 */
	public function testFailureOnEditFlags() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );

		// start editing non-existing page
		$page = WikiPage::factory( $title );
		$updater = $page->newPageUpdater( $user );

		// update with EDIT_UPDATE flag should fail
		$summary = CommentStoreComment::newUnsavedComment( 'udpate?!' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem ipsum' ) );
		$updater->saveRevision( $summary, EDIT_UPDATE );
		$status = $updater->getStatus();

		$this->assertFalse( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertFalse( $status->isOK(), 'getStatus()->isOK()' );
		$this->assertTrue( $status->hasMessage( 'edit-gone-missing' ), 'edit-gone-missing' );

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
		$this->assertFalse( $status->isOK(), 'getStatus()->isOK()' );
		$this->assertTrue( $status->hasMessage( 'edit-already-exists' ), 'edit-already-exists' );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 */
	public function testFailureOnBadContentModel() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );

		// start editing non-existing page
		$page = WikiPage::factory( $title );

		// plain text content should fail in aux slot (the main slot doesn't care)
		$updater = $page->newPageUpdater( $user )
			->setContent( 'main', new TextContent( 'Main Content' ) )
			->setContent( 'aux', new TextContent( 'Aux Content' ) );

		$summary = CommentStoreComment::newUnsavedComment( 'udpate?!' );
		$updater->saveRevision( $summary, EDIT_UPDATE );
		$status = $updater->getStatus();

		$this->assertFalse( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertFalse( $status->isOK(), 'getStatus()->isOK()' );
		$this->assertTrue(
			$status->hasMessage( 'content-not-allowed-here' ),
			'content-not-allowed-here'
		);
	}

	public function provideSetRcPatrolStatus( $patrolled ) {
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

		$page = WikiPage::factory( $title );
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

		// Create page
		$page = WikiPage::factory( $title );
		$updater = $page->newPageUpdater( $user )
			->setContent( 'main', new TextContent( 'Content 1' ) );
		$updater->saveRevision( $summary, EDIT_NEW );
		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );

		// Create a clone of $title and $page.
		$title = Title::makeTitle( $title->getNamespace(), $title->getDBkey() );
		$page = WikiPage::factory( $title );

		// start editing existing page using bad page ID
		$updater = $page->newPageUpdater( $user );
		$updater->grabParentRevision();

		$updater->setContent( 'main', new TextContent( 'Content 2' ) );

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
		$page = WikiPage::factory( $title );

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

		$updater = $page->newPageUpdater( $user );
		$content = new WikitextContent( 'A' );
		$derived = SlotRecord::newDerived( 'derivedslot', $content );
		$updater->setSlot( $derived );
		$updater->updateRevision();

		$status = $updater->getStatus();
		$this->assertTrue( $status->isOK() );
		$rev = $status->getValue()['revision-record'];
		$slot = $rev->getSlot( 'derivedslot' );
		$this->assertTrue( $slot->getContent()->equals( $content ) );
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

		$rev2 = $updater->getStatus()->getValue()['revision-record'];
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

		$rev3 = $updater->getStatus()->getValue()['revision-record'];
		$slot = $rev3->getSlot( 'derivedslot' );
		$this->assertTrue( $slot->getContent()->equals( $content ) );
	}

	// TODO: MCR: test adding multiple slots, inheriting parent slots, and removing slots.

	public function testSetUseAutomaticEditSummaries() {
		$this->setContentLang( 'qqx' );
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$page = WikiPage::factory( $title );

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
		$page2 = WikiPage::factory( $title2 );

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

	public function provideSetUsePageCreationLog() {
		yield [ true, [ [ 'create', 'create' ] ] ];
		yield [ false, [] ];
	}

	/**
	 * @dataProvider provideSetUsePageCreationLog
	 */
	public function testSetUsePageCreationLog( $use, $expected ) {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ . ( $use ? '_logged' : '_unlogged' ) );
		$page = WikiPage::factory( $title );

		$summary = CommentStoreComment::newUnsavedComment( 'cmt' );
		$updater = $page->newPageUpdater( $user )
			->setUsePageCreationLog( $use )
			->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );
		$updater->saveRevision( $summary, EDIT_NEW );

		$rev = $updater->getNewRevision();
		$this->assertSelect(
			'logging',
			[ 'log_type', 'log_action' ],
			[ 'log_page' => $rev->getPageId() ],
			$expected
		);
	}

	public function provideMagicWords() {
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

		$title = $this->getDummyTitle( __METHOD__ . '-' . $this->getName() );
		$this->insertPage( $title );

		$page = WikiPage::factory( $title );
		$updater = $page->newPageUpdater( $user )
			->setContent( SlotRecord::MAIN, new \WikitextContent( $wikitext ) );

		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$rev = $updater->saveRevision( $summary, EDIT_UPDATE );

		if ( !$rev ) {
			$this->fail( $updater->getStatus()->getWikiText() );
		}

		$expected = strval( $callback( $rev ) );

		$output = $page->getParserOutput( ParserOptions::newCanonical( 'canonical' ) );
		$html = $output->getText();
		$text = $rev->getContent( SlotRecord::MAIN )->serialize();

		if ( $subst ) {
			$this->assertStringContainsString( $expected, $text, 'In Wikitext' );
		}

		$this->assertStringContainsString( $expected, $html, 'In HTML' );
	}

	public function testChangeTagsSuppressRecentChange() {
		$page = PageIdentityValue::localIdentity( 0, NS_MAIN, __METHOD__ );
		$revision = $this->getServiceContainer()
			->getPageUpdaterFactory()
			->newPageUpdater(
				WikiPage::factory( $page ),
				$this->getTestUser()->getUser()
			)
			->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) )
			->addTag( 'foo' )
			->setFlags( EDIT_SUPPRESS_RC )
			->saveRevision( CommentStoreComment::newUnsavedComment( 'Comment' ) );
		$this->assertArrayEquals( [ 'foo' ], ChangeTags::getTags( $this->db, null, $revision->getId() ) );

		$revision2 = $this->getServiceContainer()
			->getPageUpdaterFactory()
			->newPageUpdater(
				WikiPage::factory( $page ),
				$this->getTestUser()->getUser()
			)
			->setContent( SlotRecord::MAIN, new TextContent( 'Other content' ) )
			->addTag( 'bar' )
			->setFlags( EDIT_SUPPRESS_RC )
			->saveRevision( CommentStoreComment::newUnsavedComment( 'Comment' ) );
		$this->assertArrayEquals( [ 'bar' ], ChangeTags::getTags( $this->db, null, $revision2->getId() ) );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::prepareUpdate()
	 * @covers \WikiPage::getCurrentUpdate()
	 */
	public function testPrepareUpdate() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$page = WikiPage::factory( $title );
		$updater = $page->newPageUpdater( $user );

		$this->assertSame( $page->getCurrentUpdate(), $updater->prepareUpdate() );
	}
}
