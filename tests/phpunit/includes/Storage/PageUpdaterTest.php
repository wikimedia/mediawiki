<?php

namespace MediaWiki\Tests\Storage;

use CommentStoreComment;
use MediaWiki\Storage\RevisionRecord;
use MediaWikiTestCase;
use RecentChange;
use TextContent;
use Title;
use WikiPage;

/**
 * @covers \MediaWiki\Storage\PageUpdater
 * @group Database
 */
class PageUpdaterTest extends MediaWikiTestCase {

	// FIXME: test prepareEdit
	// FIXME: test doEditUpdates

	// FIXME: test prepareUpdate
	// FIXME: test getSecondaryDataUpdates

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

	public function testCreatePage() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$page = WikiPage::factory( $title );
		$updater = $page->getPageUpdater( $user );

		// TODO: test setAjaxEditStash();
		// TODO: test setArticleCountMethod();
		// TODO: test setRcWatchCategoryMembership();
		// TODO: test setUndidRevisionId();
		// TODO: test setUseNPPatrol();
		// TODO: test setUseRCPatrol();

		$this->assertFalse( $updater->wasCommitted(), 'wasCommitted' );
		$this->assertFalse( $updater->getBaseRevisionId(), 'getBaseRevisionId' );
		$this->assertSame( 0, $updater->getUndidRevisionId(), 'getUndidRevisionId' );

		// TODO: test it working without calling setBaseRevisionId()
		// TODO: test page creation fails with a base rev id > 0
		$updater->setBaseRevisionId( 0 );
		$this->assertSame( 0, $updater->getBaseRevisionId(), 'getBaseRevisionId' );

		$updater->addTag( 'foo' );
		$updater->addTag( 'bar' );

		$tags = $updater->getExplicitTags();
		sort( $tags );
		$this->assertSame( [ 'bar', 'foo' ], $tags, 'getExplicitTags' );

		// TODO: MCR: test additional slots
		$updater->setContent( 'main', new TextContent( 'Lorem Ipsum' ) );

		// TODO: test without explicit beginEdit()
		$parent = $updater->grabParentRevision();

		// TODO: test that hasEditConflict() grabs the parent revision
		$this->assertNull( $parent, 'getParentRevision' );
		$this->assertFalse( $updater->wasCommitted(), 'wasCommitted' );
		$this->assertFalse( $updater->hasEditConflict(), 'hasEditConflict' );

		// TODO: test failure with EDIT_UPDATE
		// TODO: test EDIT_MINOR, EDIT_BOT, etc
		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$updater->doEdit( $summary );

		$this->assertTrue( $updater->wasCommitted(), 'wasCommitted()' );
		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertTrue( $updater->isNew(), 'isNew()' );
		$this->assertNotNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertFalse( $updater->isUnchanged(), 'isUnchanged()' );

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

		// TODO: test late edit conflict
		// TODO: check PST
		// TODO: check {{#revision}} in parser cache
		// TODO: check that page updates were run!
	}

	public function testUpdatePage() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$this->insertPage( $title );

		$page = WikiPage::factory( $title );
		$updater = $page->getPageUpdater( $user );

		// TODO: test it working without calling setBaseRevisionId()
		// TODO: test page update fails with a base rev id == 0
		// TODO: test page update does not fail with mismatching base rev ID
		$baseRev = $title->getLatestRevID( Title::GAID_FOR_UPDATE );
		$updater->setBaseRevisionId( $baseRev );
		$this->assertSame( $baseRev, $updater->getBaseRevisionId(), 'getBaseRevisionId' );

		// TODO: MCR: test additional slots
		$updater->setContent( 'main', new TextContent( 'Lorem Ipsum' ) );

		// TODO: test with explicit grabParentRevision()
		// TODO: test failure with EDIT_CREATE
		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$updater->doEdit( $summary );

		$this->assertTrue( $updater->wasCommitted(), 'wasCommitted()' );
		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertFalse( $updater->isNew(), 'isNew()' );
		$this->assertNotNull( $updater->getNewRevision(), 'getNewRevision()' );
		$this->assertFalse( $updater->isUnchanged(), 'isUnchanged()' );

		// TODO: Test null edit (with different user): no new revision, but LinksUpdate runs!

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
		$updater = $page->getPageUpdater( $user );
		$updater->setContent( 'main', new TextContent( 'dolor sit amet' ) );

		$summary = CommentStoreComment::newUnsavedComment( 're-edit' );
		$updater->doEdit( $summary );
		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );

		// TODO: test late edit conflict!
		// TODO: check PST
		// TODO: check {{#revision}} in parser cache
		// TODO: check that page updates were run!
	}

	public function testSetUseAutomaticEditSummaries() {
		$this->setContentLang( 'qqx' );
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$page = WikiPage::factory( $title );

		$updater = $page->getPageUpdater( $user );
		$updater->setUseAutomaticEditSummaries( true );
		$updater->setContent( 'main', new TextContent( 'Lorem Ipsum' ) );

		// empty comment triggers auto-summary
		$summary = CommentStoreComment::newUnsavedComment( '' );
		$updater->doEdit( $summary, EDIT_AUTOSUMMARY );

		$rev = $updater->getNewRevision();
		$comment = $rev->getComment( RevisionRecord::RAW );
		$this->assertSame( '(autosumm-new: Lorem Ipsum)', $comment->text, 'comment text' );

		// check that this also works when blanking the page
		$updater = $page->getPageUpdater( $user );
		$updater->setUseAutomaticEditSummaries( true );
		$updater->setContent( 'main', new TextContent( '' ) );

		$summary = CommentStoreComment::newUnsavedComment( '' );
		$updater->doEdit( $summary, EDIT_AUTOSUMMARY );

		$rev = $updater->getNewRevision();
		$comment = $rev->getComment( RevisionRecord::RAW );
		$this->assertSame( '(autosumm-blank)', $comment->text, 'comment text' );

		// check that we can also disable edit-summaries
		$title2 = $this->getDummyTitle( __METHOD__ . '/2' );
		$page2 = WikiPage::factory( $title2 );

		$updater = $page2->getPageUpdater( $user );
		$updater->setUseAutomaticEditSummaries( false );
		$updater->setContent( 'main', new TextContent( 'Lorem Ipsum' ) );

		$summary = CommentStoreComment::newUnsavedComment( '' );
		$updater->doEdit( $summary, EDIT_AUTOSUMMARY );

		$rev = $updater->getNewRevision();
		$comment = $rev->getComment( RevisionRecord::RAW );
		$this->assertSame( '', $comment->text, 'comment text should still be lank' );

		// check that we don't do auto.summaries without the EDIT_AUTOSUMMARY flag
		$updater = $page2->getPageUpdater( $user );
		$updater->setUseAutomaticEditSummaries( true );
		$updater->setContent( 'main', new TextContent( '' ) );

		$summary = CommentStoreComment::newUnsavedComment( '' );
		$updater->doEdit( $summary, 0 );

		$rev = $updater->getNewRevision();
		$comment = $rev->getComment( RevisionRecord::RAW );
		$this->assertSame( '', $comment->text, 'comment text' );
	}

}
