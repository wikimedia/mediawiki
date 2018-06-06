<?php

namespace MediaWiki\Tests\Storage;

use CommentStoreComment;
use Content;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\RevisionRecord;
use MediaWikiTestCase;
use RecentChange;
use Revision;
use TextContent;
use Title;
use WikiPage;

/**
 * @covers \MediaWiki\Storage\PageUpdater
 * @group Database
 */
class PageUpdaterTest extends MediaWikiTestCase {

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
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$page = WikiPage::factory( $title );
		$updater = $page->newPageUpdater( $user );

		$oldStats = $this->db->selectRow( 'site_stats', '*', '1=1' );

		$this->assertFalse( $updater->wasCommitted(), 'wasCommitted' );
		$this->assertFalse( $updater->getBaseRevisionId(), 'getBaseRevisionId' );
		$this->assertSame( 0, $updater->getUndidRevisionId(), 'getUndidRevisionId' );

		$updater->setBaseRevisionId( 0 );
		$this->assertSame( 0, $updater->getBaseRevisionId(), 'getBaseRevisionId' );

		$updater->addTag( 'foo' );
		$updater->addTags( [ 'bar', 'qux' ] );

		$tags = $updater->getExplicitTags();
		sort( $tags );
		$this->assertSame( [ 'bar', 'foo', 'qux' ], $tags, 'getExplicitTags' );

		// TODO: MCR: test additional slots
		$content = new TextContent( 'Lorem Ipsum' );
		$updater->setContent( 'main', $content );

		$parent = $updater->grabParentRevision();

		// TODO: test that hasEditConflict() grabs the parent revision
		$this->assertNull( $parent, 'getParentRevision' );
		$this->assertFalse( $updater->wasCommitted(), 'wasCommitted' );
		$this->assertFalse( $updater->hasEditConflict(), 'hasEditConflict' );

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

		$rev = $updater->getNewRevision();
		$revContent = $rev->getContent( 'main' );
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
		$updater->setContent( 'main', $content );

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
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$this->insertPage( $title );

		$page = WikiPage::factory( $title );
		$parentId = $page->getLatest();

		$updater = $page->newPageUpdater( $user );

		$oldStats = $this->db->selectRow( 'site_stats', '*', '1=1' );

		// TODO: test page update does not fail with mismatching base rev ID
		$baseRev = $title->getLatestRevID( Title::GAID_FOR_UPDATE );
		$updater->setBaseRevisionId( $baseRev );
		$this->assertSame( $baseRev, $updater->getBaseRevisionId(), 'getBaseRevisionId' );

		// TODO: MCR: test additional slots
		$updater->setContent( 'main', new TextContent( 'Lorem Ipsum' ) );

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

		// TODO: Test null revision (with different user): new revision!

		$rev = $updater->getNewRevision();
		$revContent = $rev->getContent( 'main' );
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
		$updater->setContent( 'main', new TextContent( 'dolor sit amet' ) );

		$summary = CommentStoreComment::newUnsavedComment( 're-edit' );
		$updater->saveRevision( $summary );
		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertTrue( $updater->getStatus()->isOK(), 'getStatus()->isOK()' );

		// check site stats - this asserts that derived data updates where run.
		$stats = $this->db->selectRow( 'site_stats', '*', '1=1' );
		$this->assertSame( $oldStats->ss_total_pages + 0, (int)$stats->ss_total_pages );
		$this->assertSame( $oldStats->ss_total_edits + 2, (int)$stats->ss_total_edits );
	}

	/**
	 * Creates a revision in the database.
	 *
	 * @param WikiPage $page
	 * @param $summary
	 * @param null|string|Content $content
	 *
	 * @return RevisionRecord|null
	 */
	private function createRevision( WikiPage $page, $summary, $content = null ) {
		$user = $this->getTestUser()->getUser();
		$comment = CommentStoreComment::newUnsavedComment( $summary );

		if ( !$content instanceof Content ) {
			$content = new TextContent( $content === null ? $summary : $content );
		}

		$updater = $page->newPageUpdater( $user );
		$updater->setContent( 'main', $content );
		$rev = $updater->saveRevision( $comment );
		return $rev;
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
		$updater->setContent( 'main', new TextContent( 'Lorem ipsum' ) );
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
		$updater->setContent( 'main', new TextContent( 'dolor sit amet' ) );
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
		$updater->setContent( 'main', new TextContent( 'Lorem ipsum' ) );
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
		$updater->setContent( 'main', new TextContent( 'dolor sit amet' ) );
		$updater->saveRevision( $summary, EDIT_NEW );
		$status = $updater->getStatus();

		$this->assertFalse( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertFalse( $status->isOK(), 'getStatus()->isOK()' );
		$this->assertTrue( $status->hasMessage( 'edit-already-exists' ), 'edit-already-exists' );
	}

	/**
	 * @covers \MediaWiki\Storage\PageUpdater::saveRevision()
	 * @covers \MediaWiki\Storage\PageUpdater::setBaseRevisionId()
	 */
	public function testFailureOnBaseRevision() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );

		// start editing non-existing page
		$page = WikiPage::factory( $title );
		$updater = $page->newPageUpdater( $user );

		// update for base revision 7 should fail
		$summary = CommentStoreComment::newUnsavedComment( 'udpate?!' );
		$updater->setBaseRevisionId( 7 ); // expect page to exist
		$updater->setContent( 'main', new TextContent( 'Lorem ipsum' ) );
		$updater->saveRevision( $summary );
		$status = $updater->getStatus();

		$this->assertFalse( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertFalse( $status->isOK(), 'getStatus()->isOK()' );
		$this->assertTrue( $status->hasMessage( 'edit-gone-missing' ), 'edit-gone-missing' );

		// create the page
		$this->createRevision( $page, __METHOD__ );

		// update for base revision 0 should fail
		$summary = CommentStoreComment::newUnsavedComment( 'create?!' );
		$updater = $page->newPageUpdater( $user );
		$updater->setBaseRevisionId( 0 ); // expect page to not exist
		$updater->setContent( 'main', new TextContent( 'dolor sit amet' ) );
		$updater->saveRevision( $summary );
		$status = $updater->getStatus();

		$this->assertFalse( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertFalse( $status->isOK(), 'getStatus()->isOK()' );
		$this->assertTrue( $status->hasMessage( 'edit-already-exists' ), 'edit-already-exists' );
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
		$updater->setContent( 'main', new TextContent( 'Lorem ipsum ' . $patrolled ) );
		$updater->setRcPatrolStatus( $patrolled );
		$rev = $updater->saveRevision( $summary );

		$rc = $revisionStore->getRecentChange( $rev );
		$this->assertEquals( $patrolled, $rc->getAttribute( 'rc_patrolled' ) );
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
		$updater->setContent( 'main', new TextContent( 'Lorem ipsum' ) );
		$rev1 = $updater->saveRevision( $summary, EDIT_NEW );

		$updater = $page->newPageUpdater( $user );
		$summary = CommentStoreComment::newUnsavedComment( 'two' );
		$updater->setContent( 'main', new TextContent( 'Foo Bar' ) );
		$rev2 = $updater->saveRevision( $summary, EDIT_UPDATE );

		$updater = $page->newPageUpdater( $user );
		$summary = CommentStoreComment::newUnsavedComment( 'three' );
		$updater->inheritSlot( $rev1->getSlot( 'main' ) );
		$rev3 = $updater->saveRevision( $summary, EDIT_UPDATE );

		$this->assertNotSame( $rev1->getId(), $rev3->getId() );
		$this->assertNotSame( $rev2->getId(), $rev3->getId() );

		$main1 = $rev1->getSlot( 'main' );
		$main3 = $rev3->getSlot( 'main' );

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
		$updater->setContent( 'main', new TextContent( 'Lorem Ipsum' ) );

		// empty comment triggers auto-summary
		$summary = CommentStoreComment::newUnsavedComment( '' );
		$updater->saveRevision( $summary, EDIT_AUTOSUMMARY );

		$rev = $updater->getNewRevision();
		$comment = $rev->getComment( RevisionRecord::RAW );
		$this->assertSame( '(autosumm-new: Lorem Ipsum)', $comment->text, 'comment text' );

		// check that this also works when blanking the page
		$updater = $page->newPageUpdater( $user );
		$updater->setUseAutomaticEditSummaries( true );
		$updater->setContent( 'main', new TextContent( '' ) );

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
		$updater->setContent( 'main', new TextContent( 'Lorem Ipsum' ) );

		$summary = CommentStoreComment::newUnsavedComment( '' );
		$updater->saveRevision( $summary, EDIT_AUTOSUMMARY );

		$rev = $updater->getNewRevision();
		$comment = $rev->getComment( RevisionRecord::RAW );
		$this->assertSame( '', $comment->text, 'comment text should still be lank' );

		// check that we don't do auto.summaries without the EDIT_AUTOSUMMARY flag
		$updater = $page2->newPageUpdater( $user );
		$updater->setUseAutomaticEditSummaries( true );
		$updater->setContent( 'main', new TextContent( '' ) );

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
		$updater->setContent( 'main', new TextContent( 'Lorem Ipsum' ) );
		$updater->saveRevision( $summary, EDIT_NEW );

		$rev = $updater->getNewRevision();
		$this->assertSelect(
			'logging',
			[ 'log_type', 'log_action' ],
			[ 'log_page' => $rev->getPageId() ],
			$expected
		);
	}

}
