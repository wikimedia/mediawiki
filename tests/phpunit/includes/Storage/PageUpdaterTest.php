<?php

namespace MediaWiki\Tests\Storage;

use CommentStoreComment;
use Content;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RenderedRevision;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWikiIntegrationTestCase;
use ParserOptions;
use RecentChange;
use Revision;
use Status;
use TextContent;
use Title;
use User;
use Wikimedia\AtEase\AtEase;
use WikiPage;

/**
 * @covers \MediaWiki\Storage\PageUpdater
 * @group Database
 */
class PageUpdaterTest extends MediaWikiIntegrationTestCase {

	protected function setUp() : void {
		parent::setUp();

		$slotRoleRegistry = MediaWikiServices::getInstance()->getSlotRoleRegistry();

		if ( !$slotRoleRegistry->isDefinedRole( 'aux' ) ) {
			$slotRoleRegistry->defineRoleWithModel(
				'aux',
				CONTENT_MODEL_WIKITEXT
			);
		}

		$this->tablesUsed[] = 'logging';
		$this->tablesUsed[] = 'recentchanges';
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

	// TODO: test setAjaxEditStash();

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 * @covers \WikiPage::newPageUpdater()
	 */
	public function testCreatePage() {
		$this->hideDeprecated( 'WikiPage::getRevision' );
		$this->hideDeprecated( "MediaWiki\Storage\PageUpdater::doCreate status get 'revision'" );
		$this->hideDeprecated( "MediaWiki\Storage\PageUpdater::doModify status get 'revision'" );
		$this->hideDeprecated( 'Revision::__construct' );

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
		// TODO: test EDIT_MINOR, EDIT_BOT, etc
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
		$this->assertFalse( $updater->isUnchanged(), 'isUnchanged()' );
		$this->assertNotNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertInstanceOf( Revision::class, $updater->getStatus()->value['revision'] );

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

		// were the WikiPage and Title objects updated?
		$this->assertTrue( $page->exists(), 'WikiPage::exists()' );
		$this->assertTrue( $title->exists(), 'Title::exists()' );
		$this->assertSame( $rev->getId(), $page->getLatest(), 'WikiPage::getRevision()' );
		$this->assertNotNull( $page->getRevision(), 'WikiPage::getRevision()' );

		// re-load
		$page2 = WikiPage::factory( $title );
		$this->assertTrue( $page2->exists(), 'WikiPage::exists()' );
		$this->assertSame( $rev->getId(), $page2->getLatest(), 'WikiPage::getRevision()' );
		$this->assertNotNull( $page2->getRevision(), 'WikiPage::getRevision()' );

		// Check RC entry
		$rc = $this->getRecentChangeFor( $rev->getId() );
		$this->assertNotNull( $rc, 'RecentChange' );

		// check site stats - this asserts that derived data updates where run.
		$stats = $this->db->selectRow( 'site_stats', '*', '1=1' );
		$this->assertSame( $oldStats->ss_total_pages + 1, (int)$stats->ss_total_pages );
		$this->assertSame( $oldStats->ss_total_edits + 1, (int)$stats->ss_total_edits );

		// re-edit with same content - should be a "null-edit"
		$updater = $page->newPageUpdater( $user );
		$updater->setContent( SlotRecord::MAIN, $content );

		$summary = CommentStoreComment::newUnsavedComment( 'to to re-edit' );
		$rev = $updater->saveRevision( $summary );
		$status = $updater->getStatus();

		$this->assertNull( $rev, 'getNewRevision()' );
		$this->assertNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertTrue( $updater->isUnchanged(), 'isUnchanged' );
		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertTrue( $status->isOK(), 'getStatus()->isOK()' );
		$this->assertTrue( $status->hasMessage( 'edit-no-change' ), 'edit-no-change' );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 * @covers \WikiPage::newPageUpdater()
	 */
	public function testUpdatePage() {
		$this->hideDeprecated( 'WikiPage::getRevision' );
		$this->hideDeprecated( "MediaWiki\Storage\PageUpdater::doCreate status get 'revision'" );
		$this->hideDeprecated( "MediaWiki\Storage\PageUpdater::doModify status get 'revision'" );
		$this->hideDeprecated( 'Revision::__construct' );

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
		$this->assertInstanceOf( Revision::class, $updater->getStatus()->value['revision'] );
		$this->assertFalse( $updater->isUnchanged(), 'isUnchanged()' );

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

		// were the WikiPage and Title objects updated?
		$this->assertTrue( $page->exists(), 'WikiPage::exists()' );
		$this->assertTrue( $title->exists(), 'Title::exists()' );
		$this->assertSame( $rev->getId(), $page->getLatest(), 'WikiPage::getRevision()' );
		$this->assertNotNull( $page->getRevision(), 'WikiPage::getRevision()' );

		// re-load
		$page2 = WikiPage::factory( $title );
		$this->assertTrue( $page2->exists(), 'WikiPage::exists()' );
		$this->assertSame( $rev->getId(), $page2->getLatest(), 'WikiPage::getRevision()' );
		$this->assertNotNull( $page2->getRevision(), 'WikiPage::getRevision()' );

		// Check RC entry
		$rc = $this->getRecentChangeFor( $rev->getId() );
		$this->assertNotNull( $rc, 'RecentChange' );

		// re-edit
		$updater = $page->newPageUpdater( $user );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'dolor sit amet' ) );

		$summary = CommentStoreComment::newUnsavedComment( 're-edit' );
		$updater->saveRevision( $summary );
		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertTrue( $updater->getStatus()->isOK(), 'getStatus()->isOK()' );
		$this->assertNotNull( $updater->getNewRevision(), 'getNewRevision()' );

		$topRevisionId = $updater->getNewRevision()->getId();

		// perform a null edit
		$updater = $page->newPageUpdater( $user );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'dolor sit amet' ) );
		$summary = CommentStoreComment::newUnsavedComment( 'null edit' );
		$updater->saveRevision( $summary );

		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertTrue( $updater->getStatus()->isOK(), 'getStatus()->isOK()' );
		$this->assertTrue( $updater->isUnchanged(), 'isUnchanged()' );
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

		$updater = $page->newPageUpdater( $user );
		$updater->setContent( SlotRecord::MAIN, $content );
		$rev = $updater->saveRevision( $comment );
		return $rev;
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
			function ( RenderedRevision $renderedRevision, User $user,
				$summary, $flags, Status $hookStatus
			) use ( &$hookFired, $expected ) {
				$hookFired = true;

				$this->assertSame( $expected['summary'], $summary );
				$this->assertSame( EDIT_NEW, $flags );

				$title = $renderedRevision->getRevision()->getPageAsLinkTarget();
				$this->assertSame( $expected['title']->getFullText(), $title->getFullText() );

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
		$updater = $page->newPageUpdater( $user );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );

		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );

		$expectedError = 'aborted-by-test-hook';
		$this->setTemporaryHook( 'MultiContentSave',
			function ( RenderedRevision $renderedRevision, User $user,
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
		$updater = $page->newPageUpdater( $user );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'dolor sit amet' ) );
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
		$updater = $page->newPageUpdater( $user );

		// plain text content should fail in aux slot (the main slot doesn't care)
		$updater->setContent( 'main', new TextContent( 'Main Content' ) );
		$updater->setContent( 'aux', new TextContent( 'Aux Content' ) );

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
		$revisionStore = MediaWikiServices::getInstance()->getRevisionStore();

		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );

		$page = WikiPage::factory( $title );
		$updater = $page->newPageUpdater( $user );

		$summary = CommentStoreComment::newUnsavedComment( 'Lorem ipsum ' . $patrolled );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem ipsum ' . $patrolled ) );
		$updater->setRcPatrolStatus( $patrolled );
		$rev = $updater->saveRevision( $summary );

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
		$updater = $page->newPageUpdater( $user );
		$updater->setContent( 'main', new TextContent( 'Content 1' ) );
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

		AtEase::suppressWarnings();
		$updater->saveRevision( $summary, EDIT_UPDATE );
		AtEase::restoreWarnings();

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

	// TODO: MCR: test adding multiple slots, inheriting parent slots, and removing slots.

	public function testSetUseAutomaticEditSummaries() {
		$this->setContentLang( 'qqx' );
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$page = WikiPage::factory( $title );

		$updater = $page->newPageUpdater( $user );
		$updater->setUseAutomaticEditSummaries( true );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );

		// empty comment triggers auto-summary
		$summary = CommentStoreComment::newUnsavedComment( '' );
		$updater->saveRevision( $summary, EDIT_AUTOSUMMARY );

		$rev = $updater->getNewRevision();
		$comment = $rev->getComment( RevisionRecord::RAW );
		$this->assertSame( '(autosumm-new: Lorem Ipsum)', $comment->text, 'comment text' );

		// check that this also works when blanking the page
		$updater = $page->newPageUpdater( $user );
		$updater->setUseAutomaticEditSummaries( true );
		$updater->setContent( SlotRecord::MAIN, new TextContent( '' ) );

		$summary = CommentStoreComment::newUnsavedComment( '' );
		$updater->saveRevision( $summary, EDIT_AUTOSUMMARY );

		$rev = $updater->getNewRevision();
		$comment = $rev->getComment( RevisionRecord::RAW );
		$this->assertSame( '(autosumm-blank)', $comment->text, 'comment text' );

		// check that we can also disable edit-summaries
		$title2 = $this->getDummyTitle( __METHOD__ . '/2' );
		$page2 = WikiPage::factory( $title2 );

		$updater = $page2->newPageUpdater( $user );
		$updater->setUseAutomaticEditSummaries( false );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );

		$summary = CommentStoreComment::newUnsavedComment( '' );
		$updater->saveRevision( $summary, EDIT_AUTOSUMMARY );

		$rev = $updater->getNewRevision();
		$comment = $rev->getComment( RevisionRecord::RAW );
		$this->assertSame( '', $comment->text, 'comment text should still be lank' );

		// check that we don't do auto.summaries without the EDIT_AUTOSUMMARY flag
		$updater = $page2->newPageUpdater( $user );
		$updater->setUseAutomaticEditSummaries( true );
		$updater->setContent( SlotRecord::MAIN, new TextContent( '' ) );

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
	 * @param bool $use
	 */
	public function testSetUsePageCreationLog( $use, $expected ) {
		$user = $this->getTestUser()->getUser();
		$title = $this->getDummyTitle( __METHOD__ . ( $use ? '_logged' : '_unlogged' ) );
		$page = WikiPage::factory( $title );

		$updater = $page->newPageUpdater( $user );
		$updater->setUsePageCreationLog( $use );
		$summary = CommentStoreComment::newUnsavedComment( 'cmt' );
		$updater->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );
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
			function ( RevisionRecord $rev ) {
				return $rev->getPageId();
			}
		];

		yield 'REVISIONID' => [
			'Test {{REVISIONID}} Test',
			function ( RevisionRecord $rev ) {
				return $rev->getId();
			}
		];

		yield 'REVISIONUSER' => [
			'Test {{REVISIONUSER}} Test',
			function ( RevisionRecord $rev ) {
				return $rev->getUser()->getName();
			}
		];

		yield 'REVISIONTIMESTAMP' => [
			'Test {{REVISIONTIMESTAMP}} Test',
			function ( RevisionRecord $rev ) {
				return $rev->getTimestamp();
			}
		];

		yield 'subst:REVISIONUSER' => [
			'Test {{subst:REVISIONUSER}} Test',
			function ( RevisionRecord $rev ) {
				return $rev->getUser()->getName();
			},
			'subst'
		];

		yield 'subst:PAGENAME' => [
			'Test {{subst:PAGENAME}} Test',
			function ( RevisionRecord $rev ) {
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
		$updater = $page->newPageUpdater( $user );

		$updater->setContent( SlotRecord::MAIN, new \WikitextContent( $wikitext ) );

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

}
