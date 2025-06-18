<?php

namespace MediaWiki\Tests\Storage;

use ArrayUtils;
use DummyContentHandlerForTesting;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Content\Content;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\JavaScriptContent;
use MediaWiki\Content\TextContent;
use MediaWiki\Content\TextContentHandler;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Content\WikitextContentHandler;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Deferred\LinksUpdate\LinksUpdate;
use MediaWiki\Deferred\MWCallableUpdate;
use MediaWiki\Edit\ParsoidRenderID;
use MediaWiki\Logging\LogPage;
use MediaWiki\Logging\ManualLogEntry;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Page\Event\PageLatestRevisionChangedEvent;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\ParserOutputAccess;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserCacheFactory;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\MutableRevisionSlots;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\DerivedPageDataUpdater;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\EditResultCache;
use MediaWiki\Storage\RevisionSlotsUpdate;
use MediaWiki\Tests\ExpectCallbackTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Utils\MWTimestamp;
use MediaWikiIntegrationTestCase;
use MockTitleTrait;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\Rdbms\Platform\ISQLPlatform;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @group Database
 *
 * @covers \MediaWiki\Storage\DerivedPageDataUpdater
 */
class DerivedPageDataUpdaterTest extends MediaWikiIntegrationTestCase {
	use MockTitleTrait;
	use ExpectCallbackTrait;

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

		return $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
	}

	/**
	 * @param string|Title|WikiPage $page
	 * @param RevisionRecord|null $rec
	 * @param User|null $user
	 *
	 * @return DerivedPageDataUpdater
	 */
	private function getDerivedPageDataUpdater(
		$page, ?RevisionRecord $rec = null, ?User $user = null
	) {
		if ( is_string( $page ) || $page instanceof Title ) {
			$page = $this->getPage( $page );
		}

		$page = TestingAccessWrapper::newFromObject( $page );
		return $page->getDerivedDataUpdater( $user, $rec );
	}

	/**
	 * Creates a revision in the database.
	 *
	 * @param WikiPage $page
	 * @param string|Message|CommentStoreComment $summary
	 * @param null|string|Content|Content[] $content
	 * @param User|null $user
	 *
	 * @return RevisionRecord|null
	 */
	private function createRevision( WikiPage $page, $summary, $content = null, $user = null ) {
		$user ??= $this->getTestUser()->getUser();
		$comment = CommentStoreComment::newUnsavedComment( $summary );

		if ( $content === null || is_string( $content ) ) {
			$content = new WikitextContent( $content ?? $summary );
		}

		if ( !is_array( $content ) ) {
			$content = [ SlotRecord::MAIN => $content ];
		}

		$this->getDerivedPageDataUpdater( $page ); // flush cached instance before.

		$updater = $page->newPageUpdater( $user );

		foreach ( $content as $role => $c ) {
			$updater->setContent( $role, $c );
		}

		$rev = $updater->saveRevision( $comment );
		if ( !$updater->wasSuccessful() ) {
			$this->fail( $updater->getStatus()->getWikiText() );
		}

		$this->getDerivedPageDataUpdater( $page ); // flush cached instance after.
		$this->runJobs( [ 'minJobs' => 0 ] ); // flush pending updates
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
		$update->modifyContent( SlotRecord::MAIN, $mainContent );
		$updater = $this->getDerivedPageDataUpdater( $page );
		$updater->prepareContent( $user, $update, false );

		$options1 = $updater->getCanonicalParserOptions();
		$this->assertSame( $this->getServiceContainer()->getContentLanguage(),
			$options1->getUserLangObj() );

		$speculativeId = $options1->getSpeculativeRevId();
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

		$currentRev = $options2->getCurrentRevisionRecordCallback()( $page->getTitle() );
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
		$slotRoleRegistry = $this->getServiceContainer()->getSlotRoleRegistry();
		if ( !$slotRoleRegistry->isDefinedRole( 'aux' ) ) {
			$slotRoleRegistry->defineRoleWithModel(
				'aux',
				CONTENT_MODEL_WIKITEXT
			);
		}

		$sysop = $this->getTestUser( [ 'sysop' ] )->getUser();
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
		$update->modifyContent( SlotRecord::MAIN, $mainContent );
		$update->modifySlot( SlotRecord::newInherited( $auxSlot ) );
		// TODO: MCR: test removing slots!

		$updater->prepareContent( $sysop, $update, false );

		// second be ok to call again with the same params
		$updater->prepareContent( $sysop, $update, false );

		$this->assertNull( $updater->grabCurrentRevision() );
		$this->assertTrue( $updater->isContentPrepared() );
		$this->assertFalse( $updater->isUpdatePrepared() );
		$this->assertFalse( $updater->pageExisted() );
		$this->assertTrue( $updater->isCreation() );
		$this->assertTrue( $updater->isChange() );
		$this->assertFalse( $updater->isContentDeleted() );

		$this->assertNotNull( $updater->getRevision() );
		$this->assertNotNull( $updater->getRenderedRevision() );

		$this->assertEquals( [ SlotRecord::MAIN, 'aux' ], $updater->getSlots()->getSlotRoles() );
		$this->assertEquals( [ SlotRecord::MAIN ], array_keys( $updater->getSlots()->getOriginalSlots() ) );
		$this->assertEquals( [ 'aux' ], array_keys( $updater->getSlots()->getInheritedSlots() ) );
		$this->assertEquals( [ SlotRecord::MAIN, 'aux' ], $updater->getModifiedSlotRoles() );
		$this->assertEquals( [ SlotRecord::MAIN, 'aux' ], $updater->getTouchedSlotRoles() );

		$mainSlot = $updater->getRawSlot( SlotRecord::MAIN );
		$this->assertInstanceOf( SlotRecord::class, $mainSlot );
		$this->assertStringNotContainsString(
			'~~~',
			$mainSlot->getContent()->serialize(),
			'PST should apply.'
		);
		$this->assertStringContainsString( $sysop->getName(), $mainSlot->getContent()->serialize() );

		$auxSlot = $updater->getRawSlot( 'aux' );
		$this->assertInstanceOf( SlotRecord::class, $auxSlot );
		$this->assertStringContainsString(
			'~~~',
			$auxSlot->getContent()->serialize(),
			'No PST should apply.'
		);

		$mainOutput = $updater->getSlotParserOutput( SlotRecord::MAIN );
		$text = $mainOutput->getRawText();
		$this->assertStringContainsString( 'first', $text );
		$this->assertStringContainsString( '<a ', $text );
		$this->assertNotEmpty( $mainOutput->getLinks() );

		$canonicalOutput = $updater->getCanonicalParserOutput();
		$text = $canonicalOutput->getRawText();
		$this->assertStringContainsString( 'first', $text );
		$this->assertStringContainsString( '<a ', $text );
		$this->assertStringContainsString( 'inherited ', $text );
		$this->assertNotEmpty( $canonicalOutput->getLinks() );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::prepareContent()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::pageExisted()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isCreation()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isChange()
	 */
	public function testPrepareContentInherit() {
		$sysop = $this->getTestUser( [ 'sysop' ] )->getUser();
		$page = $this->getPage( __METHOD__ );

		$mainContent1 = new WikitextContent( 'first [[main]] ({{REVISIONUSER}}) #~~~#' );
		$mainContent2 = new WikitextContent( 'second ({{subst:REVISIONUSER}}) #~~~#' );

		$rev = $this->createRevision( $page, 'first', $mainContent1 );
		$mainContent1 = $rev->getContent( SlotRecord::MAIN ); // get post-pst content
		$userName = $rev->getUser()->getName();
		$sysopName = $sysop->getName();

		$update = new RevisionSlotsUpdate();
		$update->modifyContent( SlotRecord::MAIN, $mainContent1 );
		$updater1 = $this->getDerivedPageDataUpdater( $page );
		$updater1->prepareContent( $sysop, $update, false );

		$this->assertNotNull( $updater1->grabCurrentRevision() );
		$this->assertTrue( $updater1->isContentPrepared() );
		$this->assertTrue( $updater1->pageExisted() );
		$this->assertFalse( $updater1->isCreation() );
		$this->assertFalse( $updater1->isChange() );

		$this->assertNotNull( $updater1->getRevision() );
		$this->assertNotNull( $updater1->getRenderedRevision() );

		// parser-output for null-edit uses the original author's name
		$html = $updater1->getRenderedRevision()->getRevisionParserOutput()->getRawText();
		$this->assertStringNotContainsString( $sysopName, $html, '{{REVISIONUSER}}' );
		$this->assertStringNotContainsString( '{{REVISIONUSER}}', $html, '{{REVISIONUSER}}' );
		$this->assertStringNotContainsString( '~~~', $html, 'signature ~~~' );
		$this->assertStringContainsString( '(' . $userName . ')', $html, '{{REVISIONUSER}}' );
		$this->assertStringContainsString( '>' . $userName . '<', $html, 'signature ~~~' );

		// prepare forced dummy revision
		$emptyUpdate = new RevisionSlotsUpdate();
		$updater1 = $this->getDerivedPageDataUpdater( $page );
		$updater1->setForceEmptyRevision( true );
		$updater1->prepareContent( $sysop, $emptyUpdate, false );

		// dummy revision inherits slots, but not revision ID
		$mainSlot0 = $rev->getSlot( SlotRecord::MAIN );
		$dummyRev = $updater1->getRevision();
		$dummySlot = $dummyRev->getSlot( SlotRecord::MAIN );
		$this->assertSame( $mainSlot0->getAddress(), $dummySlot->getAddress() );
		$this->assertNull( $dummyRev->getId() );
		$this->assertSame( $rev->getId(), $dummyRev->getParentId() );

		// prepare non-null
		$update = new RevisionSlotsUpdate();
		$update->modifyContent( SlotRecord::MAIN, $mainContent2 );
		$updater2 = $this->getDerivedPageDataUpdater( $page );
		$updater2->prepareContent( $sysop, $update, false );

		// non-null edit use the new user name in PST
		$pstText = $updater2->getSlots()->getContent( SlotRecord::MAIN )->serialize();
		$this->assertStringNotContainsString(
			'{{subst:REVISIONUSER}}',
			$pstText,
			'{{subst:REVISIONUSER}}'
		);
		$this->assertStringNotContainsString( '~~~', $pstText, 'signature ~~~' );
		$this->assertStringContainsString( '(' . $sysopName . ')', $pstText, '{{subst:REVISIONUSER}}' );
		$this->assertStringContainsString( ':' . $sysopName . '|', $pstText, 'signature ~~~' );

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
		$this->assertFalse( $updater1->isContentDeleted() );

		$this->assertNotNull( $updater1->getRevision() );
		$this->assertNotNull( $updater1->getRenderedRevision() );

		$this->assertEquals( [ SlotRecord::MAIN ], $updater1->getSlots()->getSlotRoles() );
		$this->assertEquals( [ SlotRecord::MAIN ], array_keys( $updater1->getSlots()->getOriginalSlots() ) );
		$this->assertEquals( [], array_keys( $updater1->getSlots()->getInheritedSlots() ) );
		$this->assertEquals( [ SlotRecord::MAIN ], $updater1->getModifiedSlotRoles() );
		$this->assertEquals( [ SlotRecord::MAIN ], $updater1->getTouchedSlotRoles() );

		// TODO: MCR: test multiple slots, test slot removal!

		$this->assertInstanceOf( SlotRecord::class, $updater1->getRawSlot( SlotRecord::MAIN ) );
		$this->assertStringNotContainsString(
			'~~~~',
			$updater1->getRawContent( SlotRecord::MAIN )->serialize()
		);

		$mainOutput = $updater1->getSlotParserOutput( SlotRecord::MAIN );
		$mainText = $mainOutput->getRawText();
		$this->assertStringContainsString( 'first', $mainText );
		$this->assertStringContainsString( '<a ', $mainText );
		$this->assertNotEmpty( $mainOutput->getLinks() );

		$canonicalOutput = $updater1->getCanonicalParserOutput();
		$canonicalText = $canonicalOutput->getRawText();
		$this->assertStringContainsString( 'first', $canonicalText );
		$this->assertStringContainsString( '<a ', $canonicalText );
		$this->assertNotEmpty( $canonicalOutput->getLinks() );

		$mainContent2 = new WikitextContent( 'second' );
		$rev2 = $this->createRevision( $page, 'second', $mainContent2 );
		$updater2 = $this->getDerivedPageDataUpdater( $page, $rev2 );

		$options = []; // TODO: test *all* the options...
		$updater2->prepareUpdate( $rev2, $options );

		$this->assertFalse( $updater2->isCreation() );
		$this->assertTrue( $updater2->isChange() );

		$canonicalOutput = $updater2->getCanonicalParserOutput();
		$this->assertStringContainsString( 'second', $canonicalOutput->getRawText() );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::prepareUpdate()
	 */
	public function testPrepareUpdate_null() {
		$page = $this->getExistingTestPage( __METHOD__ );
		$rev1 = $page->getRevisionRecord();

		$page = $this->getPage( $page->getTitle() );
		$updater1 = $this->getDerivedPageDataUpdater( $page, $rev1 );

		$editResult = new EditResult(
			false, $rev1->getId(), null, null, null, false, true, []
		);

		$options = [
			'editResult' => $editResult,
			'changed' => false
		];
		$updater1->grabCurrentRevision();
		$updater1->prepareContent(
			$rev1->getUser(),
			RevisionSlotsUpdate::newFromContent( [
				SlotRecord::MAIN => $rev1->getMainContentRaw()
			] )
		);
		$updater1->prepareUpdate( $rev1, $options );

		$this->assertTrue( $updater1->isUpdatePrepared() );
		$this->assertTrue( $updater1->isContentPrepared() );
		$this->assertFalse( $updater1->isCreation() );
		$this->assertFalse( $updater1->isChange() );

		$this->expectDomainEvent(
			PageLatestRevisionChangedEvent::TYPE, 1,
			static function ( PageLatestRevisionChangedEvent $event ) use ( $rev1, $editResult ) {
				Assert::assertSame( $rev1, $event->getLatestRevisionAfter() );
				Assert::assertSame( $rev1->getId(), $event->getLatestRevisionBefore()->getId() );
				Assert::assertSame( $editResult, $event->getEditResult() );
				Assert::assertFalse( $event->isCreation() );
				Assert::assertFalse( $event->changedLatestRevisionId() );
				Assert::assertTrue( $event->isReconciliationRequest() );
			}
		);

		$updater1->doUpdates();
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::prepareUpdate()
	 */
	public function testPrepareUpdateReusesParserOutput() {
		$user = $this->getTestUser()->getUser();
		$page = $this->getPage( __METHOD__ );

		$mainContent1 = new WikitextContent( 'first [[main]] ~~~' );

		$update = new RevisionSlotsUpdate();
		$update->modifyContent( SlotRecord::MAIN, $mainContent1 );
		$updater = $this->getDerivedPageDataUpdater( $page );
		$updater->prepareContent( $user, $update, false );

		$mainOutput = $updater->getSlotParserOutput( SlotRecord::MAIN );
		$canonicalOutput = $updater->getCanonicalParserOutput();

		$rev = $this->createRevision( $page, 'first', $mainContent1 );

		$options = []; // TODO: test *all* the options...
		$updater->prepareUpdate( $rev, $options );

		$this->assertTrue( $updater->isUpdatePrepared() );
		$this->assertTrue( $updater->isContentPrepared() );

		$this->assertSame( $mainOutput, $updater->getSlotParserOutput( SlotRecord::MAIN ) );
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
		$update->modifyContent( SlotRecord::MAIN, $mainContent1 );
		$updater = $this->getDerivedPageDataUpdater( $page );
		$updater->prepareContent( $user, $update, false );

		$mainOutput = $updater->getSlotParserOutput( SlotRecord::MAIN );
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
		$this->assertNotSame( $mainOutput, $updater->getSlotParserOutput( SlotRecord::MAIN ) );
		$this->assertNotSame( $canonicalOutput, $updater->getCanonicalParserOutput() );

		$html = $updater->getCanonicalParserOutput()->getRawText();
		$this->assertStringContainsString( '--' . $rev->getId() . '--', $html );

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
		$update->modifyContent( SlotRecord::MAIN, $mainContent );

		$updater = $this->getDerivedPageDataUpdater( __METHOD__ );
		$updater->prepareContent( $user, $update, false );

		$canonicalOutput = $updater->getCanonicalParserOutput();

		$preparedEdit = $updater->getPreparedEdit();
		$this->assertSame( $canonicalOutput->getCacheTime(), $preparedEdit->timestamp );
		$this->assertSame( $canonicalOutput, $preparedEdit->output );
		$this->assertSame( $mainContent, $preparedEdit->newContent );
		$this->assertSame( $updater->getRawContent( SlotRecord::MAIN ), $preparedEdit->pstContent );
		$this->assertSame( $updater->getCanonicalParserOptions(), $preparedEdit->popts );
		$this->assertSame( null, $preparedEdit->revid );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::getPreparedEdit()
	 */
	public function testGetPreparedEditAfterPrepareUpdate() {
		$clock = MWTimestamp::convert( TS_UNIX, '20100101000000' );
		MWTimestamp::setFakeTime( static function () use ( &$clock ) {
			return $clock++;
		} );

		$page = $this->getPage( __METHOD__ );

		$mainContent = new WikitextContent( 'first [[main]] ~~~' );
		$update = new MutableRevisionSlots();
		$update->setContent( SlotRecord::MAIN, $mainContent );

		$rev = $this->createRevision( $page, __METHOD__ );

		$updater = $this->getDerivedPageDataUpdater( $page );
		$updater->prepareUpdate( $rev );

		$canonicalOutput = $updater->getCanonicalParserOutput();

		$preparedEdit = $updater->getPreparedEdit();
		$this->assertSame( $canonicalOutput->getCacheTime(), $preparedEdit->timestamp );
		$this->assertSame( $canonicalOutput, $preparedEdit->output );
		$this->assertSame( $updater->getRawContent( SlotRecord::MAIN ), $preparedEdit->pstContent );
		$this->assertSame( $updater->getCanonicalParserOptions(), $preparedEdit->popts );
		$this->assertSame( $rev->getId(), $preparedEdit->revid );
	}

	public function testGetSecondaryDataUpdatesAfterPrepareContent() {
		$user = $this->getTestUser()->getUser();
		$page = $this->getPage( __METHOD__ );
		$this->createRevision( $page, __METHOD__ );

		$mainContent1 = new WikitextContent( 'first' );

		$update = new RevisionSlotsUpdate();
		$update->modifyContent( SlotRecord::MAIN, $mainContent1 );
		$updater = $this->getDerivedPageDataUpdater( $page );
		$updater->prepareContent( $user, $update, false );

		$dataUpdates = $updater->getSecondaryDataUpdates();

		$this->assertNotEmpty( $dataUpdates );

		$linksUpdates = array_filter( $dataUpdates, static function ( $du ) {
			return $du instanceof LinksUpdate;
		} );
		$this->assertCount( 1, $linksUpdates );
	}

	public function testAvoidSecondaryDataUpdatesOnNonHTMLContentHandlers() {
		$this->overrideConfigValue(
			MainConfigNames::ContentHandlers,
			[
				CONTENT_MODEL_WIKITEXT => [
					'class' => WikitextContentHandler::class,
					'services' => [
						'TitleFactory',
						'ParserFactory',
						'GlobalIdGenerator',
						'LanguageNameUtils',
						'LinkRenderer',
						'MagicWordFactory',
						'ParsoidParserFactory',
					],
				],
				'testing' => DummyContentHandlerForTesting::class,
			]
		);

		$user = $this->getTestUser()->getUser();
		$page = $this->getPage( __METHOD__ );
		$this->createRevision( $page, __METHOD__ );

		$contentHandler = new DummyContentHandlerForTesting( 'testing' );
		$mainContent1 = $contentHandler->unserializeContent( serialize( 'first' ) );
		$update = new RevisionSlotsUpdate();
		$pcache = $this->getServiceContainer()->getParserCache();
		$pcache->deleteOptionsKey( $page );
		$rev = $this->createRevision( $page, 'first', $mainContent1 );

		// Run updates
		$update->modifyContent( SlotRecord::MAIN, $mainContent1 );
		$updater = $this->getDerivedPageDataUpdater( $page );
		$updater->prepareContent( $user, $update, false );
		$dataUpdates = $updater->getSecondaryDataUpdates();
		$updater->prepareUpdate( $rev );
		$updater->doUpdates();

		// Links updates should be triggered
		$this->assertNotEmpty( $dataUpdates );
		$linksUpdates = array_filter( $dataUpdates, static function ( $du ) {
			return $du instanceof LinksUpdate;
		} );
		$this->assertCount( 1, $linksUpdates );

		// Parser cache should not be populated.
		$cached = $pcache->get( $page, $updater->getCanonicalParserOptions() );
		$this->assertFalse( $cached );
	}

	public function testGetSecondaryDataUpdatesDeleted() {
		$user = $this->getTestUser()->getUser();
		$page = $this->getPage( __METHOD__ );
		$this->createRevision( $page, __METHOD__ );

		$mainContent1 = new WikitextContent( 'first' );

		$update = new RevisionSlotsUpdate();
		$update->modifyContent( SlotRecord::MAIN, $mainContent1 );
		$updater = $this->getDerivedPageDataUpdater( $page );
		$updater->prepareContent( $user, $update, false );

		// Test that nothing happens if the page was deleted in the meantime
		// This can happen when started by the job queue
		$this->deletePage( $page );

		$dataUpdates = $updater->getSecondaryDataUpdates();

		$this->assertSame( [], $dataUpdates );
	}

	/**
	 * @param string $name
	 *
	 * @return ContentHandler
	 */
	private function defineMockContentModelForUpdateTesting( $name ) {
		/** @var ContentHandler|MockObject $handler */
		$handler = $this->getMockBuilder( TextContentHandler::class )
			->setConstructorArgs( [ $name ] )
			->onlyMethods(
				[ 'getSecondaryDataUpdates', 'getDeletionUpdates', 'unserializeContent' ]
			)
			->getMock();

		$dataUpdate = new MWCallableUpdate( 'time', "$name data update" );

		$deletionUpdate = new MWCallableUpdate( 'time', "$name deletion update" );

		$handler->method( 'getSecondaryDataUpdates' )->willReturn( [ $dataUpdate ] );
		$handler->method( 'getDeletionUpdates' )->willReturn( [ $deletionUpdate ] );
		$handler->method( 'unserializeContent' )->willReturnCallback(
			function ( $text ) use ( $handler ) {
				return $this->createMockContent( $handler, $text );
			}
		);

		$this->mergeMwGlobalArrayValue(
			'wgContentHandlers', [
				$name => static function () use ( $handler ){
					return $handler;
				}
			]
		);

		return $handler;
	}

	/**
	 * @param ContentHandler $handler
	 * @param string $text
	 *
	 * @return Content
	 */
	private function createMockContent( ContentHandler $handler, $text ) {
		/** @var Content|MockObject $content */
		$content = $this->getMockBuilder( TextContent::class )
			->setConstructorArgs( [ $text ] )
			->onlyMethods( [ 'getModel', 'getContentHandler' ] )
			->getMock();

		$content->method( 'getModel' )->willReturn( $handler->getModelID() );
		$content->method( 'getContentHandler' )->willReturn( $handler );

		return $content;
	}

	public function testGetSecondaryDataUpdatesWithSlotRemoval() {
		$m1 = $this->defineMockContentModelForUpdateTesting( 'M1' );
		$a1 = $this->defineMockContentModelForUpdateTesting( 'A1' );
		$m2 = $this->defineMockContentModelForUpdateTesting( 'M2' );

		$role = 'dpdu-test-a1';
		$slotRoleRegistry = $this->getServiceContainer()->getSlotRoleRegistry();
		$slotRoleRegistry->defineRoleWithModel(
			$role,
			$a1->getModelID()
		);

		// pin the service instance for this test
		$this->setService( 'SlotRoleRegistry', $slotRoleRegistry );

		$mainContent1 = $this->createMockContent( $m1, 'main 1' );
		$auxContent1 = $this->createMockContent( $a1, 'aux 1' );
		$mainContent2 = $this->createMockContent( $m2, 'main 2' );

		$user = $this->getTestUser()->getUser();
		$page = $this->getPage( __METHOD__ );
		$this->createRevision(
			$page,
			__METHOD__,
			[ SlotRecord::MAIN => $mainContent1, $role => $auxContent1 ]
		);

		$update = new RevisionSlotsUpdate();
		$update->modifyContent( SlotRecord::MAIN, $mainContent2 );
		$update->removeSlot( $role );

		$page = $this->getPage( __METHOD__ );
		$updater = $this->getDerivedPageDataUpdater( $page );
		$updater->prepareContent( $user, $update, false );

		$dataUpdates = $updater->getSecondaryDataUpdates();

		$this->assertNotEmpty( $dataUpdates );

		$updateNames = array_map( static function ( $du ) {
			return $du instanceof MWCallableUpdate ? $du->getOrigin() : get_class( $du );
		}, $dataUpdates );

		$this->assertContains( LinksUpdate::class, $updateNames );
		$this->assertContains( 'A1 deletion update', $updateNames );
		$this->assertContains( 'M2 data update', $updateNames );
		$this->assertNotContains( 'M1 data update', $updateNames );
	}

	/**
	 * Creates a dummy MutableRevisionRecord without touching the database.
	 *
	 * @param PageIdentity $title
	 * @param string|Content|Content[]|RevisionSlotsUpdate $update
	 * @param UserIdentity|null $user
	 * @param string $comment
	 * @param int $id
	 * @param int $parentId
	 *
	 * @return MutableRevisionRecord
	 */
	private function makeRevision(
		PageIdentity $title,
		$update,
		?UserIdentity $user = null,
		$comment = "testing",
		$id = 0,
		$parentId = 0
	) {
		$rev = new MutableRevisionRecord( $title );

		if ( $update instanceof RevisionSlotsUpdate ) {
			$rev->applyUpdate( $update );
		} else {
			if ( is_string( $update ) ) {
				$update = new WikitextContent( $update );
			}

			if ( !is_array( $update ) ) {
				$update = [ SlotRecord::MAIN => $update ];
			}

			foreach ( $update as $role => $content ) {
				$rev->setContent( $role, $content );
			}
		}

		if ( !$user ) {
			$user = $this->getTestUser()->getUser();
		}

		$rev->setUser( $user );
		$rev->setComment( CommentStoreComment::newUnsavedComment( $comment ) );
		$rev->setPageId( $title->getId() );
		$rev->setParentId( $parentId );

		if ( $id ) {
			$rev->setId( $id );
		}

		return $rev;
	}

	public static function provideIsReusableFor() {
		$title = PageIdentityValue::localIdentity( 1234, NS_MAIN, __CLASS__ );

		$user1 = new UserIdentityValue( 111, 'Alice' );
		$user2 = new UserIdentityValue( 222, 'Bob' );

		$content1 = new WikitextContent( 'one' );
		$content2 = new WikitextContent( 'two' );

		$update1 = new RevisionSlotsUpdate();
		$update1->modifyContent( SlotRecord::MAIN, $content1 );

		$update1b = new RevisionSlotsUpdate();
		$update1b->modifyContent( 'xyz', $content1 );

		$update2 = new RevisionSlotsUpdate();
		$update2->modifyContent( SlotRecord::MAIN, $content2 );

		$rev1 = [ $title, $update1, $user1, 'rev1', 11 ];
		$rev1b = [ $title, $update1b, $user1, 'rev1', 11 ];

		$rev2 = [ $title, $update2, $user1, 'rev2', 12 ];
		$rev2x = [ $title, $update2, $user2, 'rev2', 12 ];
		$rev2y = [ $title, $update2, $user1, 'rev2', 122 ];

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
	 */
	public function testIsReusableFor(
		?UserIdentity $prepUser,
		?array $prepRevision,
		?RevisionSlotsUpdate $prepUpdate,
		?UserIdentity $forUser,
		?array $forRevision,
		?RevisionSlotsUpdate $forUpdate,
		$forParent,
		$isReusable
	) {
		$updater = $this->getDerivedPageDataUpdater( __METHOD__ );

		if ( $prepUpdate ) {
			$updater->prepareContent( $prepUser, $prepUpdate, false );
		}

		if ( $prepRevision ) {
			$updater->prepareUpdate( $this->makeRevision( ...$prepRevision ) );
		}
		if ( $forRevision ) {
			$forRevision = $this->makeRevision( ...$forRevision );
		}

		$this->assertSame(
			$isReusable,
			$updater->isReusableFor( $forUser, $forRevision, $forUpdate, $forParent )
		);
	}

	/**
	 * * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isCountable
	 */
	public function testIsCountableNotContentPage() {
		$updater = $this->getDerivedPageDataUpdater(
			Title::makeTitle( NS_TALK, 'Main_Page' )
		);
		self::assertFalse( $updater->isCountable() );
	}

	public static function provideIsCountable() {
		yield 'deleted revision' => [
			'$articleCountMethod' => 'any',
			'$wikitextContent' => 'Test',
			'$revisionVisibility' => RevisionRecord::SUPPRESSED_ALL,
			'$isCountable' => false
		];
		yield 'redirect' => [
			'$articleCountMethod' => 'any',
			'$wikitextContent' => '#REDIRECT [[Main_Page]]',
			'$revisionVisibility' => 0,
			'$isCountable' => false
		];
		yield 'no links count method any' => [
			'$articleCountMethod' => 'any',
			'$wikitextContent' => 'Test',
			'$revisionVisibility' => 0,
			'$isCountable' => true
		];
		yield 'no links count method link' => [
			'$articleCountMethod' => 'link',
			'$wikitextContent' => 'Test',
			'$revisionVisibility' => 0,
			'$isCountable' => false
		];
		yield 'with links count method link' => [
			'$articleCountMethod' => 'link',
			'$wikitextContent' => '[[Test]]',
			'$revisionVisibility' => 0,
			'$isCountable' => true
		];
	}

	/**
	 * @dataProvider provideIsCountable
	 *
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isCountable
	 */
	public function testIsCountable(
		$articleCountMethod,
		$wikitextContent,
		$revisionVisibility,
		$isCountable
	) {
		$this->overrideConfigValue( MainConfigNames::ArticleCountMethod, $articleCountMethod );
		$title = $this->getTitle( 'Main_Page' );
		$content = new WikitextContent( $wikitextContent );
		$update = new RevisionSlotsUpdate();
		$update->modifyContent( SlotRecord::MAIN, $content );
		$revision = $this->makeRevision( $title, $update, User::newFromName( 'Alice' ), 'rev1', 13 );
		$revision->setVisibility( $revisionVisibility );
		$updater = $this->getDerivedPageDataUpdater( $title );
		$updater->prepareUpdate( $revision );
		self::assertSame( $isCountable, $updater->isCountable() );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::isCountable
	 */
	public function testIsCountableNoModifiedSlots() {
		$page = $this->getPage( __METHOD__ );
		$content = [ SlotRecord::MAIN => new WikitextContent( '[[Test]]' ) ];
		$rev = $this->createRevision( $page, 'first', $content );
		$nullRevision = MutableRevisionRecord::newFromParentRevision( $rev );
		$nullRevision->setId( 14 );
		$updater = $this->getDerivedPageDataUpdater( $page, $nullRevision );
		$updater->prepareUpdate( $nullRevision );
		$this->assertTrue( $updater->isCountable() );
	}

	/**
	 * @dataProvider provideDoUpdatesParams
	 *
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doUpdates()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doSecondaryDataUpdates()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doParserCacheUpdate()
	 */
	public function testDoUpdates(
		bool $simulateNullEdit,
		bool $simulatePageCreation
	) {
		$page = $this->getPage( __METHOD__ );

		$content = [ SlotRecord::MAIN => new WikitextContent( 'current [[main]]' ) ];

		$content['aux'] = new WikitextContent( 'Aux [[Nix]]' );

		$slotRoleRegistry = $this->getServiceContainer()->getSlotRoleRegistry();
		if ( !$slotRoleRegistry->isDefinedRole( 'aux' ) ) {
			$slotRoleRegistry->defineRoleWithModel(
				'aux',
				CONTENT_MODEL_WIKITEXT
			);
		}

		// If simulating a null edit, set up a previous revision with the same content as our change.
		// Otherwise, initialize the previous revision with different content unless simulating page creation,
		// in which case no previous revision should be created at all.
		if ( !$simulatePageCreation ) {
			$oldContent = $simulateNullEdit ? $content : [ SlotRecord::MAIN => new WikitextContent( 'first [[main]]' ) ];
			$firstRev = $this->createRevision( $page, 'first', $oldContent );
		} else {
			$firstRev = null;
		}

		$slotsUpdate = RevisionSlotsUpdate::newFromContent( $content );

		$updater = $this->getServiceContainer()
			->getPageUpdaterFactory()
			->newDerivedPageDataUpdater( $page );
		$updater->prepareContent( $this->getTestUser()->getUserIdentity(), $slotsUpdate );

		// Don't create a new revision if simulating a null edit.
		if ( $simulateNullEdit ) {
			$rev = $firstRev;
		} else {
			$rev = $this->createRevision( $page, 'current', $content );
		}
		$pageId = $page->getId();

		$listenerCalled = 0;
		$this->getServiceContainer()->getDomainEventSource()->registerListener(
			PageLatestRevisionChangedEvent::TYPE,
			static function ( PageLatestRevisionChangedEvent $event ) use ( &$listenerCalled, $page ) {
				$listenerCalled++;

				Assert::assertTrue( $page->isSamePageAs( $event->getPage() ) );
			}
		);

		$oldStats = $this->getDb()->newSelectQueryBuilder()
			->select( '*' )
			->from( 'site_stats' )
			->where( '1=1' )
			->fetchRow();
		$this->getDb()->newDeleteQueryBuilder()
			->deleteFrom( 'pagelinks' )
			->where( ISQLPlatform::ALL_ROWS )
			->caller( __METHOD__ )
			->execute();

		$pcache = $this->getServiceContainer()->getParserCache();
		$pcache->deleteOptionsKey( $page );

		$updater->setArticleCountMethod( 'link' );

		$options = []; // TODO: test *all* the options...

		$updater->prepareUpdate( $rev, $options );

		$updater->doUpdates();

		// links table update
		$pageLinks = $this->getDb()->newSelectQueryBuilder()
			->select( 'lt_title' )
			->from( 'pagelinks' )
			->join( 'linktarget', null, 'pl_target_id=lt_id' )
			->where( [ 'pl_from' => $pageId ] )
			->orderBy( [ 'lt_namespace', 'lt_title' ] )
			->caller( __METHOD__ )->fetchResultSet();

		$pageLinksRow = $pageLinks->fetchObject();
		$this->assertIsObject( $pageLinksRow );
		$this->assertSame( 'Main', $pageLinksRow->lt_title );

		$pageLinksRow = $pageLinks->fetchObject();
		$this->assertIsObject( $pageLinksRow );
		$this->assertSame( 'Nix', $pageLinksRow->lt_title );

		// parser cache update
		$cached = $pcache->get( $page, $updater->getCanonicalParserOptions() );
		$this->assertIsObject( $cached );
		$expected = $updater->getCanonicalParserOutput();
		$expected->clearParseStartTime();
		$this->assertEquals( $expected, $cached );

		// site stats
		$stats = $this->getDb()->newSelectQueryBuilder()
			->select( '*' )
			->from( 'site_stats' )
			->where( '1=1' )
			->fetchRow();
		if ( $simulatePageCreation ) {
			$this->assertSame( $oldStats->ss_total_pages + 1, (int)$stats->ss_total_pages );
			$this->assertSame( $oldStats->ss_good_articles + 1, (int)$stats->ss_good_articles );
		} else {
			$this->assertSame( $oldStats->ss_total_pages, $stats->ss_total_pages );
			$this->assertSame( $oldStats->ss_good_articles, $stats->ss_good_articles );
		}

		if ( !$simulateNullEdit ) {
			$this->assertSame( $oldStats->ss_total_edits + 1, (int)$stats->ss_total_edits );
		} else {
			$this->assertSame( $oldStats->ss_total_edits, $stats->ss_total_edits );
		}

		$this->runDeferredUpdates();
		$this->assertSame( 1, $listenerCalled, 'PageLatestRevisionChangedEvent listener' );

		// TODO: MCR: test data updates for additional slots!
		// TODO: test update for edit without page creation
		// TODO: test message cache purge
		// TODO: test module cache purge
		// TODO: test CDN purge
		// TODO: test newtalk update
		// TODO: test search update
		// TODO: test site stats good_articles while turning the page into (or back from) a redir.
	}

	public static function provideDoUpdatesParams(): iterable {
		$testCases = ArrayUtils::cartesianProduct(
			// null or non-null edit
			[ true, false ],
			// page creation
			[ true, false ]
		);

		foreach ( $testCases as $params ) {
			[ $simulateNullEdit, $simulatePageCreation ] = $params;

			if ( $simulateNullEdit && $simulatePageCreation ) {
				// Page creations cannot be null edits, so don't simulate an impossible scenario
				continue;
			}

			$description = sprintf(
				'%s edit%s',
				$simulateNullEdit ? 'null' : 'non-null',
				$simulatePageCreation ? ', page creation, ' : ''
			);

			yield $description => $params;
		}
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::emitEvents()
	 */
	public function testDispatchPageLatestChangedEvent() {
		$page = $this->getPage( __METHOD__ );
		$content = [ SlotRecord::MAIN => new WikitextContent( 'first [[main]]' ) ];
		$rev = $this->createRevision( $page, 'first', $content );

		$listenerCalled = 0;
		$this->getServiceContainer()->getDomainEventSource()->registerListener(
			PageLatestRevisionChangedEvent::TYPE,
			static function ( PageLatestRevisionChangedEvent $event ) use ( &$listenerCalled, $page ) {
				$listenerCalled++;

				Assert::assertTrue( $page->isSamePageAs( $event->getPage() ) );
			}
		);

		$updater = $this->getDerivedPageDataUpdater( $page, $rev );
		$updater->prepareUpdate( $rev );

		// Dispatch PageLatestRevisionChangedEvent explicitly, then assert that doUpdates()
		// doesn't dispatch it again.
		$updater->emitEvents();

		$updater->doUpdates();

		$this->runDeferredUpdates();
		$this->assertSame( 1, $listenerCalled, 'PageLatestRevisionChangedEvent listener' );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doUpdates()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doSecondaryDataUpdates()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doParserCacheUpdate()
	 */
	public function testDoUpdatesCacheSaveDeferral_canonical() {
		$page = $this->getPage( __METHOD__ );

		// Case where user has canonical parser options
		$content = [ SlotRecord::MAIN => new WikitextContent( 'rev ID ver #1: {{REVISIONID}}' ) ];
		$rev = $this->createRevision( $page, 'first', $content );
		$pcache = $this->getServiceContainer()->getParserCache();
		$pcache->deleteOptionsKey( $page );

		$this->getDb()->startAtomic( __METHOD__ ); // let deferred updates queue up

		$updater = $this->getDerivedPageDataUpdater( $page, $rev );
		$updater->prepareUpdate( $rev, [] );
		$updater->doUpdates();

		$this->assertGreaterThan( 0, DeferredUpdates::pendingUpdatesCount(), 'Pending updates' );
		$this->assertNotFalse( $pcache->get( $page, $updater->getCanonicalParserOptions() ) );

		$this->getDb()->endAtomic( __METHOD__ ); // run deferred updates
		$this->runDeferredUpdates();

		$this->assertSame( 0, DeferredUpdates::pendingUpdatesCount(), 'No pending updates' );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doUpdates()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doSecondaryDataUpdates()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doParserCacheUpdate()
	 */
	public function testDoUpdatesCacheSaveDeferral_noncanonical() {
		$page = $this->getPage( __METHOD__ );

		// Case where user does not have canonical parser options
		$user = $this->getMutableTestUser()->getUser();
		$services = $this->getServiceContainer();
		$userOptionsManager = $services->getUserOptionsManager();
		$userOptionsManager->setOption(
			$user,
			'thumbsize',
			$userOptionsManager->getOption( $user, 'thumbsize' ) + 1
		);
		$content = [ SlotRecord::MAIN => new WikitextContent( 'rev ID ver #2: {{REVISIONID}}' ) ];
		$rev = $this->createRevision( $page, 'first', $content, $user );
		$pcache = $services->getParserCache();
		$pcache->deleteOptionsKey( $page );

		$this->getDb()->startAtomic( __METHOD__ ); // let deferred updates queue up

		$updater = $this->getDerivedPageDataUpdater( $page, $rev, $user );
		$updater->prepareUpdate( $rev, [] );
		$updater->doUpdates();

		$this->assertGreaterThan( 1, DeferredUpdates::pendingUpdatesCount(), 'Pending updates' );
		$this->assertFalse( $pcache->get( $page, $updater->getCanonicalParserOptions() ) );

		$this->getDb()->endAtomic( __METHOD__ ); // run deferred updates
		$this->runDeferredUpdates();

		$this->assertSame( 0, DeferredUpdates::pendingUpdatesCount(), 'No pending updates' );
		$this->assertNotFalse( $pcache->get( $page, $updater->getCanonicalParserOptions() ) );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doUpdates()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::maybeAddRecreateChangeTag
	 */
	public function testDoUpdatesTagsEditAsRecreatedWhenDeletionLogEntry() {
		$page = $this->getPage( __METHOD__ );
		$title = $this->getTitle( __METHOD__ );

		$content = [ SlotRecord::MAIN => new WikitextContent( 'rev ID ver #1: {{REVISIONID}}' ) ];

		// create a deletion log entry
		$deleteLogEntry = new ManualLogEntry( 'delete', 'delete' );
		$deleteLogEntry->setPerformer( $this->getTestUser()->getUser() );
		$deleteLogEntry->setTarget( $title );
		$logId = $deleteLogEntry->insert( $this->getDb() );
		$deleteLogEntry->publish( $logId );

		$rev = $this->createRevision( $page, 'first', $content );

		$this->assertSame( [ 'mw-recreated' ], $this->getServiceContainer()->getChangeTagsStore()->getTags(
			$this->getDb(), null, $rev->getId() ) );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doUpdates()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::maybeAddRecreateChangeTag
	 */
	public function testDoUpdatesDoesNotTagEditAsRecreatedWhenNotNewPageCreation() {
		$page = $this->getPage( __METHOD__ );
		$title = $this->getTitle( __METHOD__ );

		$content = [ SlotRecord::MAIN => new WikitextContent( 'rev ID ver #1: {{REVISIONID}}' ) ];
		$content2 = [ SlotRecord::MAIN => new WikitextContent( 'rev ID ver #2: {{REVISIONID}}' ) ];
		$this->createRevision( $page, 'first', $content );

		// create a deletion log entry
		$deleteLogEntry = new ManualLogEntry( 'delete', 'delete' );
		$deleteLogEntry->setPerformer( $this->getTestUser()->getUser() );
		$deleteLogEntry->setTarget( $title );
		$logId = $deleteLogEntry->insert( $this->getDb() );
		$deleteLogEntry->publish( $logId );

		$rev = $this->createRevision( $page, 'second', $content2 );

		$this->assertSame( [], $this->getServiceContainer()->getChangeTagsStore()->getTags(
			$this->getDb(), null, $rev->getId() ) );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doUpdates()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::maybeAddRecreateChangeTag
	 */
	public function testDoUpdatesDoesNotTagEditAsRecreatedWhenDeletionLogEntryAndUndelete() {
		$page = $this->getPage( __METHOD__ );
		$title = $this->getTitle( __METHOD__ );
		$user = $this->getMutableTestUser()->getUser();
		$mediaWikiServices = $this->getServiceContainer();
		$changeTagsStore = $mediaWikiServices->getChangeTagsStore();

		$content = [ SlotRecord::MAIN => new WikitextContent( 'rev ID ver #1: {{REVISIONID}}' ) ];

		// create a revision on the page
		$this->createRevision( $page, 'first', $content );
		// make sure no tags on initial revision
		$this->assertSame( [], $changeTagsStore->getTags(
			$this->getDb(), null, $page->getRevisionRecord()->getId() ) );

		// create a deletion log entry
		$deleteLogEntry = new ManualLogEntry( 'delete', 'delete' );
		$deleteLogEntry->setPerformer( $this->getTestUser()->getUser() );
		$deleteLogEntry->setTarget( $title );
		$logId = $deleteLogEntry->insert( $this->getDb() );
		$deleteLogEntry->publish( $logId );
		// undelete the page
		$mediaWikiServices
			->getUndeletePageFactory()
			->newUndeletePage( $page, $user );
		// ensure revision after undelete is not tagged with recreate
		$this->assertSame( [], $changeTagsStore->getTags(
			$this->getDb(), null, $page->getRevisionRecord()->getId() ) );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doUpdates()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::maybeAddRecreateChangeTag
	 */
	public function testDoUpdatesDoesNotTagEditAsRecreatedWhenNoDeletionLogEntry() {
		$page = $this->getPage( __METHOD__ );

		$content = [ SlotRecord::MAIN => new WikitextContent( 'rev ID ver #1: {{REVISIONID}}' ) ];
		$rev = $this->createRevision( $page, 'first', $content );

		$this->assertSame( [], $this->getServiceContainer()->getChangeTagsStore()->getTags(
			$this->getDb(), null, $rev->getId() ) );
	}

	/**
	 * See T385792
	 *
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doUpdates()
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::maybeAddRecreateChangeTag
	 */
	public function testDoUpdatesDoesNotTagEditAsRecreatedWhenDeletionLogEntryActionHidden() {
		$page = $this->getPage( __METHOD__ );
		$title = $this->getTitle( __METHOD__ );

		$content = [ SlotRecord::MAIN => new WikitextContent( 'rev ID ver #1: {{REVISIONID}}' ) ];

		// create a deletion log entry
		$deleteLogEntry = new ManualLogEntry( 'delete', 'delete' );
		$deleteLogEntry->setPerformer( $this->getTestUser()->getUser() );
		$deleteLogEntry->setTarget( $title );

		// hide the target of the deletion log entry
		$deleteLogEntry->setDeleted( LogPage::DELETED_ACTION );

		$logId = $deleteLogEntry->insert( $this->getDb() );
		$deleteLogEntry->publish( $logId );

		$rev = $this->createRevision( $page, 'first', $content );

		$this->assertSame( [], $this->getServiceContainer()->getChangeTagsStore()->getTags(
			$this->getDb(), null, $rev->getId() ) );
	}

	public static function provideEnqueueRevertedTagUpdateJob() {
		return [
			'not patrolled' => [ true, 0, 0 ],
			'patrolled' => [ true, RecentChange::PRC_AUTOPATROLLED, 1 ],
			'autopatrolled' => [ true, RecentChange::PRC_AUTOPATROLLED, 1 ],
			'patrolling disabled' => [ false, 0, 1 ]
		];
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doUpdates
	 * @covers \MediaWiki\RecentChanges\ChangeTrackingEventIngress::updateRevertTagAfterPageUpdated
	 * @dataProvider provideEnqueueRevertedTagUpdateJob
	 */
	public function testEnqueueRevertedTagUpdateJob(
		bool $useRcPatrol,
		int $rcPatrolStatus,
		int $expectQueueSize
	) {
		$this->overrideConfigValue( MainConfigNames::UseRCPatrol, $useRcPatrol );
		$page = $this->getPage( __METHOD__ );

		$content = [ SlotRecord::MAIN => new WikitextContent( '1' ) ];
		$rev = $this->createRevision( $page, '', $content );
		$editResult = new EditResult(
			false,
			10,
			EditResult::REVERT_ROLLBACK,
			11,
			12,
			true,
			false,
			[ 'mw-rollback' ]
		);

		$updater = $this->getDerivedPageDataUpdater( $page, $rev );

		$updater->prepareUpdate( $rev, [
			'editResult' => $editResult,
			'rcPatrolStatus' => $rcPatrolStatus,
		] );
		$updater->doUpdates();

		$services = $this->getServiceContainer();
		$editResultCache = new EditResultCache(
			$services->getMainObjectStash(),
			$services->getConnectionProvider(),
			new ServiceOptions(
				EditResultCache::CONSTRUCTOR_OPTIONS,
				[ MainConfigNames::RCMaxAge => BagOStuff::TTL_MONTH ]
			)
		);

		if ( $useRcPatrol ) {
			$this->assertEquals(
				$editResult,
				$editResultCache->get( $rev->getId() ),
				'EditResult should be cached if patrolling is enabled'
			);
		} else {
			$this->assertNull(
				$editResultCache->get( $rev->getId() ),
				'EditResult should not be cached if patrolling is disabled'
			);
		}

		$jobQueueGroup = $this->getServiceContainer()->getJobQueueGroup();
		$jobQueue = $jobQueueGroup->get( 'revertedTagUpdate' );
		$this->assertSame(
			$expectQueueSize,
			$jobQueue->getSize()
		);
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doParserCacheUpdate()
	 * @covers \MediaWiki\JobQueue\Jobs\ParsoidCachePrewarmJob::doParsoidCacheUpdate()
	 */
	public function testDoParserCacheUpdate() {
		$this->overrideConfigValue(
			MainConfigNames::ParsoidCacheConfig,
			[
				'CacheThresholdTime' => 0.0,
				'WarmParsoidParserCache' => true, // enable caching
			]
		);

		$slotRoleRegistry = $this->getServiceContainer()->getSlotRoleRegistry();
		if ( !$slotRoleRegistry->isDefinedRole( 'aux' ) ) {
			$slotRoleRegistry->defineRoleWithModel(
				'aux',
				CONTENT_MODEL_WIKITEXT
			);
		}

		// Create page
		$page = $this->getPage( __METHOD__ );
		ConvertibleTimestamp::setFakeTime( '2022-01-01T00:01:00Z' );
		$this->createRevision( $page, 'Dummy' );

		// Assert cache update after edit ----------
		$parserCacheFactory = $this->getServiceContainer()->getParserCacheFactory();
		$parserCache = $parserCacheFactory->getParserCache( ParserCacheFactory::DEFAULT_NAME );
		$parsoidCache = $parserCacheFactory->getParserCache( "parsoid-" . ParserCacheFactory::DEFAULT_NAME );

		$parserCache->deleteOptionsKey( $page );
		$parsoidCache->deleteOptionsKey( $page );

		$user = $this->getTestUser()->getUser();

		ConvertibleTimestamp::setFakeTime( '2022-01-01T00:02:00Z' );
		$updater = $page->newPageUpdater( $user );
		$updater->setContent( SlotRecord::MAIN, new WikitextContent( 'first [[Main]]' ) );
		$updater->setContent( 'aux', new WikitextContent( 'Aux [[Nix]]' ) );
		$rev = $updater->saveRevision( CommentStoreComment::newUnsavedComment( 'testing' ) );

		// run all the jobs
		ConvertibleTimestamp::setFakeTime( '2022-01-01T00:03:00Z' );
		$this->runJobs();

		// Parsoid cache should have an entry
		$parserOptions = ParserOptions::newFromAnon();
		$parserOptions->setUseParsoid();
		$parsoidCached = $parsoidCache->get( $page, $parserOptions, true );
		$this->assertIsObject( $parsoidCached );
		$this->assertStringContainsString( 'first', $parsoidCached->getRawText() );

		// The parsoid parser output is generated during runJobs(), after the last call to setFakeTime().
		$this->assertGreaterThan( $rev->getTimestamp(), $parsoidCached->getCacheTime() );
		$this->assertSame( $rev->getId(), $parsoidCached->getCacheRevisionId() );

		// Check that ParsoidRenderID::newFromParserOutput() doesn't throw,
		// so we know that $parsoidCached is valid.
		ParsoidRenderID::newFromParserOutput( $parsoidCached );

		// The cached ParserOutput should not use the revision timestamp
		// Create nwe ParserOptions object since we setUseParsoid() above
		$parserOptions = ParserOptions::newFromAnon();
		$cached = $parserCache->get( $page, $parserOptions, true );
		$this->assertIsObject( $cached );
		$this->assertNotSame( $parsoidCached, $cached );
		$this->assertStringContainsString( 'first', $cached->getRawText() );

		// The regular parser output is generated immediately during saveRevision(),
		// so it uses the same timestamp as the revision.
		$this->assertSame( $rev->getTimestamp(), $cached->getCacheTime() );
		$this->assertSame( $rev->getId(), $cached->getCacheRevisionId() );

		// Emulate forced update of an old revision ----------
		ConvertibleTimestamp::setFakeTime( '2022-01-01T00:04:00Z' );
		$parserCache->deleteOptionsKey( $page );

		$updater = $this->getDerivedPageDataUpdater( $page );
		$updater->prepareUpdate( $rev );

		// Force the page timestamp, so we notice whether ParserOutput::getTimestamp
		// or ParserOutput::getCacheTime are used.
		$page->setTimestamp( $rev->getTimestamp() );
		$updater->doParserCacheUpdate();

		// The cached ParserOutput should not use the revision timestamp
		$cached = $parserCache->get( $page, $updater->getCanonicalParserOptions(), true );
		$this->assertIsObject( $cached );
		$expected = $updater->getCanonicalParserOutput();
		$expected->clearParseStartTime();
		$this->assertEquals( $expected, $cached );

		$this->assertGreaterThan( $rev->getTimestamp(), $cached->getCacheTime() );
		$this->assertSame( $rev->getId(), $cached->getCacheRevisionId() );
	}

	/**
	 * @covers \MediaWiki\Storage\DerivedPageDataUpdater::doParserCacheUpdate()
	 * @covers \MediaWiki\JobQueue\Jobs\ParsoidCachePrewarmJob::doParsoidCacheUpdate()
	 */
	public function testDoParserCacheUpdateForJavaScriptContent() {
		$this->overrideConfigValue(
			MainConfigNames::ParsoidCacheConfig,
			[
				'CacheThresholdTime' => 0.0,
				'WarmParsoidParserCache' => true, // enable caching
			]
		);

		$page = $this->getPage( __METHOD__ );
		$this->createRevision( $page, 'Dummy' );

		$user = $this->getTestUser()->getUser();

		$update = new RevisionSlotsUpdate();
		$update->modifyContent( SlotRecord::MAIN, new JavaScriptContent( '{ first: "main"; }' ) );

		// Emulate update after edit ----------
		$parserCacheFactory = $this->getServiceContainer()->getParserCacheFactory();
		$parserCache = $parserCacheFactory->getParserCache( ParserCacheFactory::DEFAULT_NAME );
		$parsoidCache = $parserCacheFactory->getParserCache( ParserOutputAccess::PARSOID_PCACHE_NAME );

		$parserCache->deleteOptionsKey( $page );
		$parsoidCache->deleteOptionsKey( $page );

		$rev = $this->makeRevision( $page->getTitle(), $update, $user, 'rev', null );
		$rev->setTimestamp( '20100101000000' );
		$rev->setParentId( $page->getLatest() );

		$updater = $this->getDerivedPageDataUpdater( $page );
		$updater->prepareContent( $user, $update, false );

		$rev->setId( 1107 );
		$updater->prepareUpdate( $rev );

		// Force the page timestamp, so we notice whether ParserOutput::getTimestamp
		// or ParserOutput::getCacheTime are used.
		// Also ensure $page->getLatest() returns the correct revision ID, so the parser
		// cache doesn't get confused.
		TestingAccessWrapper::newFromObject( $page )->setLastEdit( $rev );
		$updater->doParserCacheUpdate();

		// The cached ParserOutput should not use the revision timestamp
		$cached = $parserCache->get( $page, $updater->getCanonicalParserOptions(), true );
		$this->assertIsObject( $cached );
	}

	/**
	 * Helper for testTemplateUpdate
	 *
	 * @param WikiPage $page
	 * @param string $content
	 */
	private function editAndUpdate( $page, $content ) {
		$this->createRevision( $page, $content );
		$this->getServiceContainer()->resetServiceForTesting( 'BacklinkCacheFactory' );
		DeferredUpdates::doUpdates();
		$this->runJobs( [ 'minJobs' => 0 ] );
	}

	/**
	 * Regression test for T368006
	 */
	public function testTemplateUpdate() {
		$clock = MWTimestamp::convert( TS_UNIX, '20100101000000' );
		MWTimestamp::setFakeTime( static function () use ( &$clock ) {
			return $clock++;
		} );

		$template = $this->getPage( 'Template:TestTemplateUpdate' );
		$page = $this->getPage( 'TestTemplateUpdate' );
		$this->editAndUpdate( $template, '1' );
		$this->editAndUpdate( $page, '{{TestTemplateUpdate}}' );
		$oldTouched = $page->getTouched();
		$page->clear();

		$this->editAndUpdate( $template, '2' );
		$newTouched = $page->getTouched();
		$this->assertGreaterThan( $oldTouched, $newTouched );
	}

	public static function provideNewTalk() {
		yield 'Talk page edit' => [
			'NewTalk TestAuthor',
			'User_talk:NewTalk_TestUser',
			'NewTalk TestUser',
			true,
		];
		yield 'Own talk page' => [
			'NewTalk TestUser',
			'User_talk:NewTalk_TestUser',
			'NewTalk TestUser',
			false,
		];
		yield 'IP user page' => [
			'NewTalk TestAuthor',
			'User_talk:192.168.0.1',
			'192.168.0.1',
			true,
		];
		yield 'User talk subpage' => [
			'NewTalk TestAuthor',
			'User_talk:NewTalk_TestUser/sandbox',
			'NewTalk TestUser',
			false,
		];
		yield 'Not talk page' => [
			'NewTalk TestAuthor',
			'User:NewTalk_TestUser',
			'NewTalk TestUser',
			false,
		];
	}

	private function createUser( string $name ) {
		$userFactory = $this->getServiceContainer()->getUserFactory();

		$user = $userFactory->newFromName( $name );
		if ( !$user ) {
			$user = $userFactory->newAnonymous( $name );
		} elseif ( !$user->getId() ) {
			$user->addToDatabase();
		}

		return $user;
	}

	/**
	 * @dataProvider provideNewTalk
	 */
	public function testNewTalk( string $authorName, string $pageName, string $recipientName, bool $expected ) {
		$author = $this->createUser( $authorName );
		$recipient = $this->createUser( $recipientName );

		$notificationManager = $this->getServiceContainer()->getTalkPageNotificationManager();
		$notificationManager->clearForPageView( $recipient );

		$page = $this->getPage( $pageName );

		$content = new WikitextContent( 'Hi there!' );
		$this->createRevision( $page, 'Testing', $content, $author );
		DeferredUpdates::doUpdates();

		$hasNewMessage = $notificationManager->userHasNewMessages( $recipient );
		$this->assertSame( $expected, $hasNewMessage );
	}

}
