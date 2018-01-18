<?php

namespace MediaWiki\Tests\Storage;

use CommentStoreComment;
use MediaWiki\Storage\PageUpdater;
use MediaWikiTestCase;
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

	public function testCreatePage() {
		$user = $this->getTestUser()->getUser();

		$title = $this->getDummyTitle( __METHOD__ );
		$page = WikiPage::factory( $title );
		$updater = $page->getUpdater();

		// TODO: test setAjaxEditStash();
		// TODO: test setArticleCountMethod();
		// TODO: test setRcWatchCategoryMembership();
		// TODO: test setUndidRevisionId();
		// TODO: test setUseAutomaticEditSummaries();
		// TODO: test setUseNPPatrol();
		// TODO: test setUseRCPatrol();

		$this->assertFalse( $updater->wasBegun(), 'wasBegun' );
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
		$updater->beginEdit();

		// TODO: test getParentRevision() and hasEditConflict() fail before beginEdit()
		$this->assertTrue( $updater->wasBegun(), 'wasBegun' );
		$this->assertFalse( $updater->wasCommitted(), 'wasCommitted' );
		$this->assertNull( $updater->getParentRevision(), 'getParentRevision' );
		$this->assertFalse( $updater->hasEditConflict(), 'hasEditConflict' );

		// TODO: test failure with EDIT_UPDATE
		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$updater->commitEdit( $summary, $user );

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
		$updater = $page->getUpdater();

		// TODO: test it working without calling setBaseRevisionId()
		// TODO: test page update fails with a base rev id == 0
		// TODO: test page update does not fail with mismatching base rev ID
		$baseRev = $title->getLatestRevID( Title::GAID_FOR_UPDATE );
		$updater->setBaseRevisionId( $baseRev );
		$this->assertSame( $baseRev, $updater->getBaseRevisionId(), 'getBaseRevisionId' );

		// TODO: MCR: test additional slots
		$updater->setContent( 'main', new TextContent( 'Lorem Ipsum' ) );

		// TODO: test with explicit beginEdit()
		// TODO: test failure with EDIT_CREATE
		$summary = CommentStoreComment::newUnsavedComment( 'Just a test' );
		$updater->commitEdit( $summary, $user );

		$this->assertTrue( $updater->wasCommitted(), 'wasCommitted()' );
		$this->assertTrue( $updater->wasSuccessful(), 'wasSuccessful()' );
		$this->assertFalse( $updater->isNew(), 'isNew()' );
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

		// TODO: test late edit conflict
		// TODO: check PST
		// TODO: check {{#revision}} in parser cache
		// TODO: check that page updates were run!
	}

	// TODO: test prepareContentForEdit
	// TODO: test doEditUpdates

}
