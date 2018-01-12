<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\RevisionRecord;
use MediaWikiTestCase;
use Revision;
use Title;
use WikiPage;
use WikitextContent;

class SingleContentRevisionLookupTest extends MediaWikiTestCase {

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionLookup::getRevisionById
	 */
	public function testGetRevisionById() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$lookup = MediaWikiServices::getInstance()->getRevisionLookup();
		$revRecord = $lookup->getRevisionById( $rev->getId() );

		$this->assertSame( $rev->getId(), $revRecord->getId() );
		$this->assertTrue( $revRecord->getSlot( 'main' )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $revRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionLookup::getRevisionByTitle
	 */
	public function testGetRevisionByTitle() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$lookup = MediaWikiServices::getInstance()->getRevisionLookup();
		$revRecord = $lookup->getRevisionByTitle( $page->getTitle() );

		$this->assertSame( $rev->getId(), $revRecord->getId() );
		$this->assertTrue( $revRecord->getSlot( 'main' )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $revRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionLookup::getRevisionByPageId
	 */
	public function testGetRevisionByPageId() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$lookup = MediaWikiServices::getInstance()->getRevisionLookup();
		$revRecord = $lookup->getRevisionByPageId( $page->getId() );

		$this->assertSame( $rev->getId(), $revRecord->getId() );
		$this->assertTrue( $revRecord->getSlot( 'main' )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $revRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionLookup::getRevisionByTimestamp
	 */
	public function testGetRevisionByTimestamp() {
		// Make sure there is 1 second between the last revision and the rev we create...
		// Otherwise we might not get the correct revision and the test may fail...
		// :(
		sleep( 1 );
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		$content = new WikitextContent( __METHOD__ );
		$status = $page->doEditContent( $content, __METHOD__ );
		/** @var Revision $rev */
		$rev = $status->value['revision'];

		$lookup = MediaWikiServices::getInstance()->getRevisionLookup();
		$revRecord = $lookup->getRevisionByTimestamp(
			$page->getTitle(),
			$rev->getTimestamp()
		);

		$this->assertSame( $rev->getId(), $revRecord->getId() );
		$this->assertTrue( $revRecord->getSlot( 'main' )->getContent()->equals( $content ) );
		$this->assertSame( __METHOD__, $revRecord->getComment()->text );
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionLookup::getPreviousRevision
	 */
	public function testGetPreviousRevision() {
		$page = WikiPage::factory( Title::newFromText( __METHOD__ ) );
		/** @var Revision $revOne */
		$revOne = $page->doEditContent(
			new WikitextContent( __METHOD__ ), __METHOD__
		)->value['revision'];
		/** @var Revision $revTwo */
		$revTwo = $page->doEditContent(
			new WikitextContent( __METHOD__ . '2' ), __METHOD__
		)->value['revision'];

		$lookup = MediaWikiServices::getInstance()->getRevisionLookup();
		$this->assertNull(
			$lookup->getPreviousRevision( $lookup->getRevisionById( $revOne->getId() ) )
		);
		$this->assertSame(
			$revOne->getId(),
			$lookup->getPreviousRevision( $lookup->getRevisionById( $revTwo->getId() ) )->getId()
		);
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionLookup::getNextRevision
	 */
	public function testGetNextRevision() {
		$page = WikiPage::factory( Title::newFromText( __METHOD__ ) );
		/** @var Revision $revOne */
		$revOne = $page->doEditContent(
			new WikitextContent( __METHOD__ ), __METHOD__
		)->value['revision'];
		/** @var Revision $revTwo */
		$revTwo = $page->doEditContent(
			new WikitextContent( __METHOD__ . '2' ), __METHOD__
		)->value['revision'];

		$lookup = MediaWikiServices::getInstance()->getRevisionLookup();
		$this->assertSame(
			$revTwo->getId(),
			$lookup->getNextRevision( $lookup->getRevisionById( $revOne->getId() ) )->getId()
		);
		$this->assertNull(
			$lookup->getNextRevision( $lookup->getRevisionById( $revTwo->getId() ) )
		);
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionLookup::getKnownCurrentRevision
	 */
	public function testGetKnownCurrentRevision() {
		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
		/** @var Revision $rev */
		$rev = $page->doEditContent(
			new WikitextContent( __METHOD__. 'b' ),
			__METHOD__ . 'b',
			0,
			false,
			$this->getTestUser()->getUser()
		)->value['revision'];

		$lookup = MediaWikiServices::getInstance()->getRevisionLookup();
		$record = $lookup->getKnownCurrentRevision(
			$page->getTitle(),
			$rev->getId()
		);

		$this->assertRevisionRecordMatchesRevision( $rev, $record );
	}

	private function assertRevisionRecordMatchesRevision(
		Revision $rev,
		RevisionRecord $record
	) {
		$this->assertSame( $rev->getId(), $record->getId() );
		$this->assertSame( $rev->getPage(), $record->getPageId() );
		$this->assertSame( $rev->getTimestamp(), $record->getTimestamp() );
		$this->assertSame( $rev->getUserText(), $record->getUser()->getName() );
		$this->assertSame( $rev->getUser(), $record->getUser()->getId() );
		$this->assertSame( $rev->isMinor(), $record->isMinor() );
		$this->assertSame( $rev->getVisibility(), $record->getVisibility() );
		$this->assertSame( $rev->getSize(), $record->getSize() );
		/**
		 * @note As of MW 1.31, the database schema allows the parent ID to be
		 * NULL to indicate that it is unknown.
		 */
		$expectedParent = $rev->getParentId();
		if ( $expectedParent === null ) {
			$expectedParent = 0;
		}
		$this->assertSame( $expectedParent, $record->getParentId() );
		$this->assertSame( $rev->getSha1(), $record->getSha1() );
		$this->assertSame( $rev->getComment(), $record->getComment()->text );
		$this->assertSame( $rev->getContentFormat(), $record->getContent( 'main' )->getDefaultFormat() );
		$this->assertSame( $rev->getContentModel(), $record->getContent( 'main' )->getModel() );
		$this->assertLinkTargetsEqual( $rev->getTitle(), $record->getPageAsLinkTarget() );
	}

	private function assertLinkTargetsEqual( LinkTarget $l1, LinkTarget $l2 ) {
		$this->assertEquals( $l1->getDBkey(), $l2->getDBkey() );
		$this->assertEquals( $l1->getNamespace(), $l2->getNamespace() );
		$this->assertEquals( $l1->getFragment(), $l2->getFragment() );
		$this->assertEquals( $l1->getInterwiki(), $l2->getInterwiki() );
	}

}
