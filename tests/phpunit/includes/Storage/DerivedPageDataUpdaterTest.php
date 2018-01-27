<?php

namespace MediaWiki\Tests\Storage;

use CommentStoreComment;
use Content;
use MediaWiki\Storage\MutableRevisionSlots;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\SlotRecord;
use MediaWikiTestCase;
use Title;
use WikiPage;
use WikitextContent;

/**
 * @group Database
 */
class DerivedPageDataUpdaterTest extends MediaWikiTestCase {

	/**
	 * @param $title
	 *
	 * @return WikiPage
	 */
	private function getPage( $title ) {
		$page = WikiPage::factory( Title::makeTitleSafe( $this->getDefaultWikitextNS(), $title ) );
		return $page;
	}

	/**
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
			$content = new WikitextContent( $content === null ? $summary : $content );
		}

		$page->getDerivedDataUpdater(); // flush cached instance before.

		$updater = $page->getPageUpdater( $user );
		$updater->setContent( 'main', $content );
		$rev = $updater->createRevision( $comment );

		$page->getDerivedDataUpdater(); // flush cached instance after.
		return $rev;
	}

	// TODO: test setArticleCountMethod() and isCountable();
	// TODO: test setRcWatchCategoryMembership();
	// TODO: test isContentPublic();
	// TODO: test getRawSlot();
	// TODO: test getRawContent();
	// TODO: test isRedirect() and wasRedirect()
	// TODO: test isChange();
	// TODO: test getCanonicalParserOptions();

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::grabCurrentRevision()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::pageExisted()
	 */
	public function testGrabCurrentRevision() {
		$page = $this->getPage( __METHOD__ );

		$updater0 = $page->getDerivedDataUpdater();
		$this->assertNull( $updater0->grabCurrentRevision() );
		$this->assertFalse( $updater0->pageExisted() );

		$rev1 = $this->createRevision( $page, 'first' );
		$updater1 = $page->getDerivedDataUpdater();
		$this->assertSame( $rev1->getId(), $updater1->grabCurrentRevision()->getId() );
		$this->assertFalse( $updater0->pageExisted() );
		$this->assertTrue( $updater1->pageExisted() );

		$rev2 = $this->createRevision( $page, 'second' );
		$updater2 = $page->getDerivedDataUpdater();
		$this->assertSame( $rev1->getId(), $updater1->grabCurrentRevision()->getId() );
		$this->assertSame( $rev2->getId(), $updater2->grabCurrentRevision()->getId() );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::prepareRevisionCreation()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isContentPrepared()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::pageExisted()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isCreation()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isChange()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getSlots()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getTouchedSlotRoles()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getSlotParserOutput()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getCanonicalParserOutput()
	 */
	public function testPrepareRevisionCreation() {
		$user = $this->getTestUser()->getUser();
		$page = $this->getPage( __METHOD__ );

		$updater = $page->getDerivedDataUpdater();

		$this->assertFalse( $updater->isContentPrepared() );

		// TODO: test stash
		// TODO: MCR: test multiple slots
		$mainContent = new WikitextContent( 'first [[main]] ~~~' );
		$update = new MutableRevisionSlots();
		$update->setContent( 'main', $mainContent );

		$updater->prepareRevisionCreation( $user, $update, false );

		// second be ok to call again with the same params
		$updater->prepareRevisionCreation( $user, $update, false );

		$this->assertNull( $updater->grabCurrentRevision() );
		$this->assertTrue( $updater->isContentPrepared() );
		$this->assertFalse( $updater->isUpdatePrepared() );
		$this->assertFalse( $updater->pageExisted() );
		$this->assertTrue( $updater->isCreation() );
		$this->assertTrue( $updater->isChange() );
		$this->assertTrue( $updater->isContentPublic() );

		$this->assertEquals( [ 'main' ], $updater->getSlots()->getSlotRoles() );
		$this->assertEquals( [ 'main' ], array_keys( $updater->getSlots()->getTouchedSlots() ) );

		$this->assertInstanceOf( SlotRecord::class, $updater->getRawSlot( 'main' ) );
		$this->assertNotContains( '~~~~', $updater->getRawContent( 'main' )->serialize() );
		$this->assertContains( $user->getName(), $updater->getRawContent( 'main' )->serialize() );

		$mainOutput = $updater->getCanonicalParserOutput();
		$this->assertContains( 'first', $mainOutput->getText() );
		$this->assertContains( '<a ', $mainOutput->getText() );
		$this->assertNotEmpty( $mainOutput->getLinks() );

		$canonicalOutput = $updater->getCanonicalParserOutput();
		$this->assertContains( 'first', $canonicalOutput->getText() );
		$this->assertContains( '<a ', $canonicalOutput->getText() );
		$this->assertNotEmpty( $canonicalOutput->getLinks() );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::prepareRevisionCreation()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::pageExisted()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isCreation()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isChange()
	 */
	public function testPrepareRevisionCreationInherit() {
		$user = $this->getTestUser()->getUser();
		$page = $this->getPage( __METHOD__ );

		$mainContent1 = new WikitextContent( 'first [[main]] ~~~' );
		$mainContent2 = new WikitextContent( 'second' );

		$this->createRevision( $page, 'first', $mainContent1 );

		$update = new MutableRevisionSlots();
		$update->setContent( 'main', $mainContent1 );
		$updater1 = $page->getDerivedDataUpdater();
		$updater1->prepareRevisionCreation( $user, $update, false );

		$this->assertNotNull( $updater1->grabCurrentRevision() );
		$this->assertTrue( $updater1->isContentPrepared() );
		$this->assertTrue( $updater1->pageExisted() );
		$this->assertFalse( $updater1->isCreation() );
		$this->assertFalse( $updater1->isChange() );

		// TODO: MCR: test inheritance
		$update = new MutableRevisionSlots();
		$update->setContent( 'main', $mainContent2 );
		$updater2 = $page->getDerivedDataUpdater();
		$updater2->prepareRevisionCreation( $user, $update, false );

		$this->assertFalse( $updater2->isCreation() );
		$this->assertTrue( $updater2->isChange() );
	}

	// TODO: test failure of prepareRevisionCreation() when called again...
	// - with different user
	// - with different update
	// - after calling prepareUpdate()

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::prepareUpdate()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isUpdatePrepared()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isCreation()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getSlots()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getTouchedSlotRoles()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getSlotParserOutput()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getCanonicalParserOutput()
	 */
	public function testPrepareUpdate() {
		$page = $this->getPage( __METHOD__ );

		$mainContent1 = new WikitextContent( 'first [[main]] ~~~' );
		$rev1 = $this->createRevision( $page, 'first', $mainContent1 );
		$updater1 = $page->getDerivedDataUpdater( null, $rev1 );

		$options = []; // TODO: test *all* the options...
		$updater1->prepareUpdate( $rev1, $options );

		$this->assertTrue( $updater1->isUpdatePrepared() );
		$this->assertTrue( $updater1->isContentPrepared() );
		$this->assertTrue( $updater1->isCreation() );
		$this->assertTrue( $updater1->isChange() );
		$this->assertTrue( $updater1->isContentPublic() );

		$this->assertEquals( [ 'main' ], $updater1->getSlots()->getSlotRoles() );
		$this->assertEquals( [ 'main' ], array_keys( $updater1->getSlots()->getTouchedSlots() ) );

		$this->assertInstanceOf( SlotRecord::class, $updater1->getRawSlot( 'main' ) );
		$this->assertNotContains( '~~~~', $updater1->getRawContent( 'main' )->serialize() );

		$mainOutput = $updater1->getCanonicalParserOutput();
		$this->assertContains( 'first', $mainOutput->getText() );
		$this->assertContains( '<a ', $mainOutput->getText() );
		$this->assertNotEmpty( $mainOutput->getLinks() );

		$canonicalOutput = $updater1->getCanonicalParserOutput();
		$this->assertContains( 'first', $canonicalOutput->getText() );
		$this->assertContains( '<a ', $canonicalOutput->getText() );
		$this->assertNotEmpty( $canonicalOutput->getLinks() );

		$mainContent2 = new WikitextContent( 'second' );
		$rev2 = $this->createRevision( $page, 'second', $mainContent2 );
		$updater2 = $page->getDerivedDataUpdater( null, $rev2 );

		$options = []; // TODO: test *all* the options...
		$updater2->prepareUpdate( $rev2, $options );

		$this->assertFalse( $updater2->isCreation() );
		$this->assertTrue( $updater2->isChange() );

		$canonicalOutput = $updater2->getCanonicalParserOutput();
		$this->assertContains( 'second', $canonicalOutput->getText() );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::prepareUpdate()
	 */
	public function testPrepareUpdateReusesParserOutput() {
		$user = $this->getTestUser()->getUser();
		$page = $this->getPage( __METHOD__ );

		$mainContent1 = new WikitextContent( 'first [[main]] ~~~' );

		$update = new MutableRevisionSlots();
		$update->setContent( 'main', $mainContent1 );
		$updater = $page->getDerivedDataUpdater();
		$updater->prepareRevisionCreation( $user, $update, false );

		$mainOutput = $updater->getSlotParserOutput( 'main' );
		$canonicalOutput = $updater->getCanonicalParserOutput();

		$rev = $this->createRevision( $page, 'first', $mainContent1 );

		$options = []; // TODO: test *all* the options...
		$updater->prepareUpdate( $rev, $options );

		$this->assertTrue( $updater->isUpdatePrepared() );
		$this->assertTrue( $updater->isContentPrepared() );

		$this->assertSame( $mainOutput, $updater->getSlotParserOutput( 'main' ) );
		$this->assertSame( $canonicalOutput, $updater->getCanonicalParserOutput() );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::prepareUpdate()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getSlotParserOutput()
	 */
	public function testPrepareUpdateOutputReset() {
		$user = $this->getTestUser()->getUser();
		$page = $this->getPage( __METHOD__ );

		$mainContent1 = new WikitextContent( 'first --{{REVISIONID}}--' );

		$update = new MutableRevisionSlots();
		$update->setContent( 'main', $mainContent1 );
		$updater = $page->getDerivedDataUpdater();
		$updater->prepareRevisionCreation( $user, $update, false );

		$mainOutput = $updater->getSlotParserOutput( 'main' );
		$canonicalOutput = $updater->getCanonicalParserOutput();

		// prevent optimization on matching speculative ID
		$mainOutput->setSpeculativeRevIdUsed( 0 );
		$canonicalOutput->setSpeculativeRevIdUsed( 0 );

		$rev = $this->createRevision( $page, 'first', $mainContent1 );

		$options = []; // TODO: test *all* the options...
		$updater->prepareUpdate( $rev, $options );

		$this->assertTrue( $updater->isUpdatePrepared() );
		$this->assertTrue( $updater->isContentPrepared() );

		// ParserOutput objects should have been flushed.
		$this->assertNotSame( $mainOutput, $updater->getSlotParserOutput( 'main' ) );
		$this->assertNotSame( $canonicalOutput, $updater->getCanonicalParserOutput() );

		$html = $updater->getCanonicalParserOutput()->getText();
		$this->assertContains( '--' . $rev->getId() . '--', $html );
	}


	// TODO: test failure of prepareUpdate() when called again with a different revision
	// TODO: test failure of prepareUpdate() on inconsistency with prepareRevisionCreation.

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isReusableFor()
	 */
	public function testIsReusableFor() {

	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getPreparedEdit()
	 */
	public function testGetPreparedEdit() {

	}

	// TODO: test getSecondaryDataUpdates();
	// TODO: test runSecondaryDataUpdates();
	// TODO: test doUpdates();
	// TODO: test updateParserCache();

}
