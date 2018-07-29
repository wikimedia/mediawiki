<?php

namespace MediaWiki\Tests\Storage;

use CommentStoreComment;
use Content;
use LinksUpdate;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\DerivedPageDataUpdater;
use MediaWiki\Storage\MutableRevisionRecord;
use MediaWiki\Storage\MutableRevisionSlots;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionSlotsUpdate;
use MediaWiki\Storage\SlotRecord;
use MediaWikiTestCase;
use Title;
use User;
use Wikimedia\TestingAccessWrapper;
use WikiPage;
use WikitextContent;

/**
 * @group Database
 *
 * @covers MediaWiki\Storage\DerivedPageDataUpdater
 */
class DerivedPageDataUpdaterTest extends MediaWikiTestCase {

	/**
	 * @param string $title
	 *
	 * @return Title
	 */
	private function getTitle( $title ) {
		return Title::makeTitleSafe( $this->getDefaultWikitextNS(), $title );
	}

	/**
	 * @param string|Title $title
	 *
	 * @return WikiPage
	 */
	private function getPage( $title ) {
		$title = ( $title instanceof Title ) ? $title : $this->getTitle( $title );

		return WikiPage::factory( $title );
	}

	/**
	 * @param string|Title|WikiPage $page
	 *
	 * @return DerivedPageDataUpdater
	 */
	private function getDerivedPageDataUpdater( $page, RevisionRecord $rec = null ) {
		if ( is_string( $page ) || $page instanceof Title ) {
			$page = $this->getPage( $page );
		}

		$page = TestingAccessWrapper::newFromObject( $page );
		return $page->getDerivedDataUpdater( null, $rec );
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
			$content = new WikitextContent( $content ?? $summary );
		}

		$this->getDerivedPageDataUpdater( $page ); // flush cached instance before.

		$updater = $page->newPageUpdater( $user );
		$updater->setContent( 'main', $content );
		$rev = $updater->saveRevision( $comment );

		$this->getDerivedPageDataUpdater( $page ); // flush cached instance after.
		return $rev;
	}

	// TODO: test setArticleCountMethod() and isCountable();
	// TODO: test isRedirect() and wasRedirect()

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getCanonicalParserOptions()
	 */
	public function testGetCanonicalParserOptions() {
		$user = $this->getTestUser()->getUser();
		$page = $this->getPage( __METHOD__ );

		$parentRev = $this->createRevision( $page, 'first' );

		$mainContent = new WikitextContent( 'Lorem ipsum' );

		$update = new RevisionSlotsUpdate();
		$update->modifyContent( 'main', $mainContent );
		$updater = $this->getDerivedPageDataUpdater( $page );
		$updater->prepareContent( $user, $update, false );

		$options1 = $updater->getCanonicalParserOptions();
		$this->assertSame( MediaWikiServices::getInstance()->getContentLanguage(),
			$options1->getUserLangObj() );

		$speculativeId = call_user_func( $options1->getSpeculativeRevIdCallback(), $page->getTitle() );
		$this->assertSame( $parentRev->getId() + 1, $speculativeId );

		$rev = $this->makeRevision(
			$page->getTitle(),
			$update,
			$user,
			$parentRev->getId() + 7,
			$parentRev->getId()
		);
		$updater->prepareUpdate( $rev );

		$options2 = $updater->getCanonicalParserOptions();
		$this->assertNotSame( $options1, $options2 );

		$currentRev = call_user_func( $options2->getCurrentRevisionCallback(), $page->getTitle() );
		$this->assertSame( $rev->getId(), $currentRev->getId() );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::grabCurrentRevision()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::pageExisted()
	 */
	public function testGrabCurrentRevision() {
		$page = $this->getPage( __METHOD__ );

		$updater0 = $this->getDerivedPageDataUpdater( $page );
		$this->assertNull( $updater0->grabCurrentRevision() );
		$this->assertFalse( $updater0->pageExisted() );

		$rev1 = $this->createRevision( $page, 'first' );
		$updater1 = $this->getDerivedPageDataUpdater( $page );
		$this->assertSame( $rev1->getId(), $updater1->grabCurrentRevision()->getId() );
		$this->assertFalse( $updater0->pageExisted() );
		$this->assertTrue( $updater1->pageExisted() );

		$rev2 = $this->createRevision( $page, 'second' );
		$updater2 = $this->getDerivedPageDataUpdater( $page );
		$this->assertSame( $rev1->getId(), $updater1->grabCurrentRevision()->getId() );
		$this->assertSame( $rev2->getId(), $updater2->grabCurrentRevision()->getId() );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::prepareContent()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isContentPrepared()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::pageExisted()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isCreation()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isChange()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getSlots()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getRawSlot()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getRawContent()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getModifiedSlotRoles()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getTouchedSlotRoles()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getSlotParserOutput()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getCanonicalParserOutput()
	 */
	public function testPrepareContent() {
		$user = $this->getTestUser()->getUser();
		$updater = $this->getDerivedPageDataUpdater( __METHOD__ );

		$this->assertFalse( $updater->isContentPrepared() );

		// TODO: test stash
		// TODO: MCR: Test multiple slots. Test slot removal.
		$mainContent = new WikitextContent( 'first [[main]] ~~~' );
		$auxContent = new WikitextContent( 'inherited ~~~ content' );
		$auxSlot = SlotRecord::newSaved(
			10, 7, 'tt:7',
			SlotRecord::newUnsaved( 'aux', $auxContent )
		);

		$update = new RevisionSlotsUpdate();
		$update->modifyContent( 'main', $mainContent );
		$update->modifySlot( SlotRecord::newInherited( $auxSlot ) );
		// TODO: MCR: test removing slots!

		$updater->prepareContent( $user, $update, false );

		// second be ok to call again with the same params
		$updater->prepareContent( $user, $update, false );

		$this->assertNull( $updater->grabCurrentRevision() );
		$this->assertTrue( $updater->isContentPrepared() );
		$this->assertFalse( $updater->isUpdatePrepared() );
		$this->assertFalse( $updater->pageExisted() );
		$this->assertTrue( $updater->isCreation() );
		$this->assertTrue( $updater->isChange() );
		$this->assertTrue( $updater->isContentPublic() );

		$this->assertEquals( [ 'main', 'aux' ], $updater->getSlots()->getSlotRoles() );
		$this->assertEquals( [ 'main' ], array_keys( $updater->getSlots()->getOriginalSlots() ) );
		$this->assertEquals( [ 'aux' ], array_keys( $updater->getSlots()->getInheritedSlots() ) );
		$this->assertEquals( [ 'main', 'aux' ], $updater->getModifiedSlotRoles() );
		$this->assertEquals( [ 'main', 'aux' ], $updater->getTouchedSlotRoles() );

		$mainSlot = $updater->getRawSlot( 'main' );
		$this->assertInstanceOf( SlotRecord::class, $mainSlot );
		$this->assertNotContains( '~~~', $mainSlot->getContent()->serialize(), 'PST should apply.' );
		$this->assertContains( $user->getName(), $mainSlot->getContent()->serialize() );

		$auxSlot = $updater->getRawSlot( 'aux' );
		$this->assertInstanceOf( SlotRecord::class, $auxSlot );
		$this->assertContains( '~~~', $auxSlot->getContent()->serialize(), 'No PST should apply.' );

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
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::prepareContent()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::pageExisted()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isCreation()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isChange()
	 */
	public function testPrepareContentInherit() {
		$user = $this->getTestUser()->getUser();
		$page = $this->getPage( __METHOD__ );

		$mainContent1 = new WikitextContent( 'first [[main]] ~~~' );
		$mainContent2 = new WikitextContent( 'second' );

		$this->createRevision( $page, 'first', $mainContent1 );

		$update = new RevisionSlotsUpdate();
		$update->modifyContent( 'main', $mainContent1 );
		$updater1 = $this->getDerivedPageDataUpdater( $page );
		$updater1->prepareContent( $user, $update, false );

		$this->assertNotNull( $updater1->grabCurrentRevision() );
		$this->assertTrue( $updater1->isContentPrepared() );
		$this->assertTrue( $updater1->pageExisted() );
		$this->assertFalse( $updater1->isCreation() );
		$this->assertFalse( $updater1->isChange() );

		// TODO: MCR: test inheritance from parent
		$update = new RevisionSlotsUpdate();
		$update->modifyContent( 'main', $mainContent2 );
		$updater2 = $this->getDerivedPageDataUpdater( $page );
		$updater2->prepareContent( $user, $update, false );

		$this->assertFalse( $updater2->isCreation() );
		$this->assertTrue( $updater2->isChange() );
	}

	// TODO: test failure of prepareContent() when called again...
	// - with different user
	// - with different update
	// - after calling prepareUpdate()

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::prepareUpdate()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isUpdatePrepared()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isCreation()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getSlots()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getRawSlot()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getRawContent()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getModifiedSlotRoles()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getTouchedSlotRoles()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getSlotParserOutput()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getCanonicalParserOutput()
	 */
	public function testPrepareUpdate() {
		$page = $this->getPage( __METHOD__ );

		$mainContent1 = new WikitextContent( 'first [[main]] ~~~' );
		$rev1 = $this->createRevision( $page, 'first', $mainContent1 );
		$updater1 = $this->getDerivedPageDataUpdater( $page, $rev1 );

		$options = []; // TODO: test *all* the options...
		$updater1->prepareUpdate( $rev1, $options );

		$this->assertTrue( $updater1->isUpdatePrepared() );
		$this->assertTrue( $updater1->isContentPrepared() );
		$this->assertTrue( $updater1->isCreation() );
		$this->assertTrue( $updater1->isChange() );
		$this->assertTrue( $updater1->isContentPublic() );

		$this->assertEquals( [ 'main' ], $updater1->getSlots()->getSlotRoles() );
		$this->assertEquals( [ 'main' ], array_keys( $updater1->getSlots()->getOriginalSlots() ) );
		$this->assertEquals( [], array_keys( $updater1->getSlots()->getInheritedSlots() ) );
		$this->assertEquals( [ 'main' ], $updater1->getModifiedSlotRoles() );
		$this->assertEquals( [ 'main' ], $updater1->getTouchedSlotRoles() );

		// TODO: MCR: test multiple slots, test slot removal!

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
		$updater2 = $this->getDerivedPageDataUpdater( $page, $rev2 );

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

		$update = new RevisionSlotsUpdate();
		$update->modifyContent( 'main', $mainContent1 );
		$updater = $this->getDerivedPageDataUpdater( $page );
		$updater->prepareContent( $user, $update, false );

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

		$update = new RevisionSlotsUpdate();
		$update->modifyContent( 'main', $mainContent1 );
		$updater = $this->getDerivedPageDataUpdater( $page );
		$updater->prepareContent( $user, $update, false );

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

		// TODO: MCR: ensure that when the main slot uses {{REVISIONID}} but another slot is
		// updated, the main slot is still re-rendered!
	}

	// TODO: test failure of prepareUpdate() when called again with a different revision
	// TODO: test failure of prepareUpdate() on inconsistency with prepareContent.

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getPreparedEdit()
	 */
	public function testGetPreparedEditAfterPrepareContent() {
		$user = $this->getTestUser()->getUser();

		$mainContent = new WikitextContent( 'first [[main]] ~~~' );
		$update = new RevisionSlotsUpdate();
		$update->modifyContent( 'main', $mainContent );

		$updater = $this->getDerivedPageDataUpdater( __METHOD__ );
		$updater->prepareContent( $user, $update, false );

		$canonicalOutput = $updater->getCanonicalParserOutput();

		$preparedEdit = $updater->getPreparedEdit();
		$this->assertSame( $canonicalOutput->getCacheTime(), $preparedEdit->timestamp );
		$this->assertSame( $canonicalOutput, $preparedEdit->output );
		$this->assertSame( $mainContent, $preparedEdit->newContent );
		$this->assertSame( $updater->getRawContent( 'main' ), $preparedEdit->pstContent );
		$this->assertSame( $updater->getCanonicalParserOptions(), $preparedEdit->popts );
		$this->assertSame( null, $preparedEdit->revid );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getPreparedEdit()
	 */
	public function testGetPreparedEditAfterPrepareUpdate() {
		$page = $this->getPage( __METHOD__ );

		$mainContent = new WikitextContent( 'first [[main]] ~~~' );
		$update = new MutableRevisionSlots();
		$update->setContent( 'main', $mainContent );

		$rev = $this->createRevision( $page, __METHOD__ );

		$updater = $this->getDerivedPageDataUpdater( $page );
		$updater->prepareUpdate( $rev );

		$canonicalOutput = $updater->getCanonicalParserOutput();

		$preparedEdit = $updater->getPreparedEdit();
		$this->assertSame( $canonicalOutput->getCacheTime(), $preparedEdit->timestamp );
		$this->assertSame( $canonicalOutput, $preparedEdit->output );
		$this->assertSame( $updater->getRawContent( 'main' ), $preparedEdit->pstContent );
		$this->assertSame( $updater->getCanonicalParserOptions(), $preparedEdit->popts );
		$this->assertSame( $rev->getId(), $preparedEdit->revid );
	}

	public function testGetSecondaryDataUpdatesAfterPrepareContent() {
		$user = $this->getTestUser()->getUser();
		$page = $this->getPage( __METHOD__ );
		$this->createRevision( $page, __METHOD__ );

		$mainContent1 = new WikitextContent( 'first' );

		$update = new RevisionSlotsUpdate();
		$update->modifyContent( 'main', $mainContent1 );
		$updater = $this->getDerivedPageDataUpdater( $page );
		$updater->prepareContent( $user, $update, false );

		$dataUpdates = $updater->getSecondaryDataUpdates();

		// TODO: MCR: assert updates from all slots!
		$this->assertNotEmpty( $dataUpdates );

		$linksUpdates = array_filter( $dataUpdates, function ( $du ) {
			return $du instanceof LinksUpdate;
		} );
		$this->assertCount( 1, $linksUpdates );
	}

	/**
	 * Creates a dummy revision object without touching the database.
	 *
	 * @param Title $title
	 * @param RevisionSlotsUpdate $update
	 * @param User $user
	 * @param string $comment
	 * @param int $id
	 * @param int $parentId
	 *
	 * @return MutableRevisionRecord
	 */
	private function makeRevision(
		Title $title,
		RevisionSlotsUpdate $update,
		User $user,
		$comment,
		$id,
		$parentId = 0
	) {
		$rev = new MutableRevisionRecord( $title );

		$rev->applyUpdate( $update );
		$rev->setUser( $user );
		$rev->setComment( CommentStoreComment::newUnsavedComment( $comment ) );
		$rev->setId( $id );
		$rev->setPageId( $title->getArticleID() );
		$rev->setParentId( $parentId );

		return $rev;
	}

	public function provideIsReusableFor() {
		$title = Title::makeTitleSafe( NS_MAIN, __METHOD__ );

		$user1 = User::newFromName( 'Alice' );
		$user2 = User::newFromName( 'Bob' );

		$content1 = new WikitextContent( 'one' );
		$content2 = new WikitextContent( 'two' );

		$update1 = new RevisionSlotsUpdate();
		$update1->modifyContent( 'main', $content1 );

		$update1b = new RevisionSlotsUpdate();
		$update1b->modifyContent( 'xyz', $content1 );

		$update2 = new RevisionSlotsUpdate();
		$update2->modifyContent( 'main', $content2 );

		$rev1 = $this->makeRevision( $title, $update1, $user1, 'rev1', 11 );
		$rev1b = $this->makeRevision( $title, $update1b, $user1, 'rev1', 11 );

		$rev2 = $this->makeRevision( $title, $update2, $user1, 'rev2', 12 );
		$rev2x = $this->makeRevision( $title, $update2, $user2, 'rev2', 12 );
		$rev2y = $this->makeRevision( $title, $update2, $user1, 'rev2', 122 );

		yield 'any' => [
			'$prepUser' => null,
			'$prepRevision' => null,
			'$prepUpdate' => null,
			'$forUser' => null,
			'$forRevision' => null,
			'$forUpdate' => null,
			'$forParent' => null,
			'$isReusable' => true,
		];
		yield 'for any' => [
			'$prepUser' => $user1,
			'$prepRevision' => $rev1,
			'$prepUpdate' => $update1,
			'$forUser' => null,
			'$forRevision' => null,
			'$forUpdate' => null,
			'$forParent' => null,
			'$isReusable' => true,
		];
		yield 'unprepared' => [
			'$prepUser' => null,
			'$prepRevision' => null,
			'$prepUpdate' => null,
			'$forUser' => $user1,
			'$forRevision' => $rev1,
			'$forUpdate' => $update1,
			'$forParent' => 0,
			'$isReusable' => true,
		];
		yield 'match prepareContent' => [
			'$prepUser' => $user1,
			'$prepRevision' => null,
			'$prepUpdate' => $update1,
			'$forUser' => $user1,
			'$forRevision' => null,
			'$forUpdate' => $update1,
			'$forParent' => 0,
			'$isReusable' => true,
		];
		yield 'match prepareUpdate' => [
			'$prepUser' => null,
			'$prepRevision' => $rev1,
			'$prepUpdate' => null,
			'$forUser' => $user1,
			'$forRevision' => $rev1,
			'$forUpdate' => null,
			'$forParent' => 0,
			'$isReusable' => true,
		];
		yield 'match all' => [
			'$prepUser' => $user1,
			'$prepRevision' => $rev1,
			'$prepUpdate' => $update1,
			'$forUser' => $user1,
			'$forRevision' => $rev1,
			'$forUpdate' => $update1,
			'$forParent' => 0,
			'$isReusable' => true,
		];
		yield 'mismatch prepareContent update' => [
			'$prepUser' => $user1,
			'$prepRevision' => null,
			'$prepUpdate' => $update1,
			'$forUser' => $user1,
			'$forRevision' => null,
			'$forUpdate' => $update1b,
			'$forParent' => 0,
			'$isReusable' => false,
		];
		yield 'mismatch prepareContent user' => [
			'$prepUser' => $user1,
			'$prepRevision' => null,
			'$prepUpdate' => $update1,
			'$forUser' => $user2,
			'$forRevision' => null,
			'$forUpdate' => $update1,
			'$forParent' => 0,
			'$isReusable' => false,
		];
		yield 'mismatch prepareContent parent' => [
			'$prepUser' => $user1,
			'$prepRevision' => null,
			'$prepUpdate' => $update1,
			'$forUser' => $user1,
			'$forRevision' => null,
			'$forUpdate' => $update1,
			'$forParent' => 7,
			'$isReusable' => false,
		];
		yield 'mismatch prepareUpdate revision update' => [
			'$prepUser' => null,
			'$prepRevision' => $rev1,
			'$prepUpdate' => null,
			'$forUser' => null,
			'$forRevision' => $rev1b,
			'$forUpdate' => null,
			'$forParent' => 0,
			'$isReusable' => false,
		];
		yield 'mismatch prepareUpdate revision user' => [
			'$prepUser' => null,
			'$prepRevision' => $rev2,
			'$prepUpdate' => null,
			'$forUser' => null,
			'$forRevision' => $rev2x,
			'$forUpdate' => null,
			'$forParent' => 0,
			'$isReusable' => false,
		];
		yield 'mismatch prepareUpdate revision id' => [
			'$prepUser' => null,
			'$prepRevision' => $rev2,
			'$prepUpdate' => null,
			'$forUser' => null,
			'$forRevision' => $rev2y,
			'$forUpdate' => null,
			'$forParent' => 0,
			'$isReusable' => false,
		];
	}

	/**
	 * @dataProvider provideIsReusableFor
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isReusableFor()
	 *
	 * @param User|null $prepUser
	 * @param RevisionRecord|null $prepRevision
	 * @param RevisionSlotsUpdate|null $prepUpdate
	 * @param User|null $forUser
	 * @param RevisionRecord|null $forRevision
	 * @param RevisionSlotsUpdate|null $forUpdate
	 * @param int|null $forParent
	 * @param bool $isReusable
	 */
	public function testIsReusableFor(
		User $prepUser = null,
		RevisionRecord $prepRevision = null,
		RevisionSlotsUpdate $prepUpdate = null,
		User $forUser = null,
		RevisionRecord $forRevision = null,
		RevisionSlotsUpdate $forUpdate = null,
		$forParent = null,
		$isReusable = null
	) {
		$updater = $this->getDerivedPageDataUpdater( __METHOD__ );

		if ( $prepUpdate ) {
			$updater->prepareContent( $prepUser, $prepUpdate, false );
		}

		if ( $prepRevision ) {
			$updater->prepareUpdate( $prepRevision );
		}

		$this->assertSame(
			$isReusable,
			$updater->isReusableFor( $forUser, $forRevision, $forUpdate, $forParent )
		);
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doUpdates()
	 */
	public function testDoUpdates() {
		$page = $this->getPage( __METHOD__ );

		$mainContent1 = new WikitextContent( 'first [[main]]' );
		$rev = $this->createRevision( $page, 'first', $mainContent1 );
		$pageId = $page->getId();
		$oldStats = $this->db->selectRow( 'site_stats', '*', '1=1' );

		$updater = $this->getDerivedPageDataUpdater( $page, $rev );
		$updater->setArticleCountMethod( 'link' );

		$options = []; // TODO: test *all* the options...
		$updater->prepareUpdate( $rev, $options );

		$updater->doUpdates();

		// links table update
		$linkCount = $this->db->selectRowCount( 'pagelinks', '*', [ 'pl_from' => $pageId ] );
		$this->assertSame( 1, $linkCount );

		$pageLinksRow = $this->db->selectRow( 'pagelinks', '*', [ 'pl_from' => $pageId ] );
		$this->assertInternalType( 'object', $pageLinksRow );
		$this->assertSame( 'Main', $pageLinksRow->pl_title );

		// parser cache update
		$pcache = MediaWikiServices::getInstance()->getParserCache();
		$cached = $pcache->get( $page, $updater->getCanonicalParserOptions() );
		$this->assertInternalType( 'object', $cached );
		$this->assertSame( $updater->getCanonicalParserOutput(), $cached );

		// site stats
		$stats = $this->db->selectRow( 'site_stats', '*', '1=1' );
		$this->assertSame( $oldStats->ss_total_pages + 1, (int)$stats->ss_total_pages );
		$this->assertSame( $oldStats->ss_total_edits + 1, (int)$stats->ss_total_edits );
		$this->assertSame( $oldStats->ss_good_articles + 1, (int)$stats->ss_good_articles );

		// TODO: MCR: test data updates for additional slots!
		// TODO: test update for edit without page creation
		// TODO: test message cache purge
		// TODO: test module cache purge
		// TODO: test CDN purge
		// TODO: test newtalk update
		// TODO: test search update
		// TODO: test site stats good_articles while turning the page into (or back from) a redir.
		// TODO: test category membership update (with setRcWatchCategoryMembership())
	}

}
