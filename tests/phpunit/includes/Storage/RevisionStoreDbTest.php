<?php

namespace MediaWiki\Tests\Storage;

use CommentStoreComment;
use Exception;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\IncompleteRevisionException;
use MediaWiki\Storage\MutableRevisionRecord;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\SlotRecord;
use MediaWikiTestCase;
use MWException;
use TestUserRegistry;
use Title;
use WikiPage;
use WikitextContent;

/**
 * @group Database
 */
class RevisionStoreDbTest extends MediaWikiTestCase {

	private function assertLinkTargetsEqual( LinkTarget $l1, LinkTarget $l2 ) {
		$this->assertEquals( $l1->getDBkey(), $l2->getDBkey() );
		$this->assertEquals( $l1->getNamespace(), $l2->getNamespace() );
		$this->assertEquals( $l1->getFragment(), $l2->getFragment() );
		$this->assertEquals( $l1->getInterwiki(), $l2->getInterwiki() );
	}

	private function assertRevisionRecordsEqual( RevisionRecord $r1, RevisionRecord $r2 ) {
		$this->assertEquals( $r1->getUser()->getName(), $r2->getUser()->getName() );
		$this->assertEquals( $r1->getUser()->getId(), $r2->getUser()->getId() );
		$this->assertEquals( $r1->getComment(), $r2->getComment() );
		$this->assertEquals( $r1->getPageAsLinkTarget(), $r2->getPageAsLinkTarget() );
		$this->assertEquals( $r1->getTimestamp(), $r2->getTimestamp() );
		$this->assertEquals( $r1->getVisibility(), $r2->getVisibility() );
		$this->assertEquals( $r1->getSha1(), $r2->getSha1() );
		$this->assertEquals( $r1->getParentId(), $r2->getParentId() );
		$this->assertEquals( $r1->getSize(), $r2->getSize() );
		$this->assertEquals( $r1->getPageId(), $r2->getPageId() );
		$this->assertEquals( $r1->getSlotRoles(), $r2->getSlotRoles() );
		$this->assertEquals( $r1->getWikiId(), $r2->getWikiId() );
		$this->assertEquals( $r1->isMinor(), $r2->isMinor() );
		foreach ( $r1->getSlotRoles() as $role ) {
			$this->assertEquals( $r1->getSlot( $role ), $r2->getSlot( $role ) );
			$this->assertEquals( $r1->getContent( $role ), $r2->getContent( $role ) );
		}
		foreach ( [
			RevisionRecord::DELETED_TEXT,
			RevisionRecord::DELETED_COMMENT,
			RevisionRecord::DELETED_USER,
			RevisionRecord::DELETED_RESTRICTED,
		] as $field ) {
			$this->assertEquals( $r1->isDeleted( $field ), $r2->isDeleted( $field ) );
		}
	}

	/**
	 * @param mixed[] $details
	 *
	 * @return RevisionRecord
	 */
	private function getRevisionRecord( $title, $details = [] ) {
		// Convert some values that can't be provided by dataProviders
		$page = WikiPage::factory( $title );
		if ( isset( $details['user'] ) && $details['user'] === true ) {
			$details['user'] = $this->getTestUser()->getUser();
		}
		if ( isset( $details['page'] ) && $details['page'] === true ) {
			$details['page'] = $page->getId();
		}
		if ( isset( $details['parent'] ) && $details['parent'] === true ) {
			$details['parent'] = $page->getLatest();
		}

		// Create the RevisionRecord with any available data
		$rev = new MutableRevisionRecord( $title );
		isset( $details['slot'] ) ? $rev->setSlot( $details['slot'] ) : null;
		isset( $details['parent'] ) ? $rev->setParentId( $details['parent'] ) : null;
		isset( $details['page'] ) ? $rev->setPageId( $details['page'] ) : null;
		isset( $details['size'] ) ? $rev->setSize( $details['size'] ) : null;
		isset( $details['sha1'] ) ? $rev->setSha1( $details['sha1'] ) : null;
		isset( $details['comment'] ) ? $rev->setComment( $details['comment'] ) : null;
		isset( $details['timestamp'] ) ? $rev->setTimestamp( $details['timestamp'] ) : null;
		isset( $details['minor'] ) ? $rev->setMinorEdit( $details['minor'] ) : null;
		isset( $details['user'] ) ? $rev->setUser( $details['user'] ) : null;
		isset( $details['visibility'] ) ? $rev->setVisibility( $details['visibility'] ) : null;
		isset( $details['id'] ) ? $rev->setId( $details['id'] ) : null;

		return $rev;
	}

	private function getRandomCommentStoreComment() {
		return CommentStoreComment::newUnsavedComment( __METHOD__ . '.' . rand( 0, 1000 ) );
	}

	public function provideInsertRevisionOn_successes() {
		yield 'Bare minimum revision insertion' => [
			Title::newFromText( 'UTPage' ),
			[
				'slot' => SlotRecord::newUnsaved( 'main', new WikitextContent( 'Chicken' ) ),
				'parent' => true,
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
			],
		];
		yield 'Detailed revision insertion' => [
			Title::newFromText( 'UTPage' ),
			[
				'slot' => SlotRecord::newUnsaved( 'main', new WikitextContent( 'Chicken' ) ),
				'parent' => true,
				'page' => true,
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
				'minor' => true,
				'visibility' => RevisionRecord::DELETED_RESTRICTED,
			],
		];
	}

	/**
	 * @dataProvider provideInsertRevisionOn_successes
	 * @covers RevisionStore::insertRevisionOn
	 */
	public function testInsertRevisionOn_successes( Title $title, array $revDetails = [] ) {
		$rev = $this->getRevisionRecord( $title, $revDetails );

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$return = $store->insertRevisionOn( $rev, wfGetDB( DB_MASTER ) );

		$this->assertLinkTargetsEqual( $title, $return->getPageAsLinkTarget() );
		$this->assertRevisionRecordsEqual( $rev, $return );
	}

	/**
	 * @covers RevisionStore::insertRevisionOn
	 */
	public function testInsertRevisionOn_blobAddressExists() {
		$title = Title::newFromText( 'UTPage' );
		$revDetails = [
			'slot' => SlotRecord::newUnsaved( 'main', new WikitextContent( 'Chicken' ) ),
			'parent' => true,
			'comment' => $this->getRandomCommentStoreComment(),
			'timestamp' => '20171117010101',
			'user' => true,
		];

		$store = MediaWikiServices::getInstance()->getRevisionStore();

		// Insert the first revision
		$revOne = $this->getRevisionRecord( $title, $revDetails );
		$firstReturn = $store->insertRevisionOn( $revOne, wfGetDB( DB_MASTER ) );
		$this->assertLinkTargetsEqual( $title, $firstReturn->getPageAsLinkTarget() );
		$this->assertRevisionRecordsEqual( $revOne, $firstReturn );

		// Insert a second revision inheriting the same blob address
		$revDetails['slot'] = SlotRecord::newInherited( $firstReturn->getSlot( 'main' ) );
		$revTwo = $this->getRevisionRecord( $title, $revDetails );
		$secondReturn = $store->insertRevisionOn( $revTwo, wfGetDB( DB_MASTER ) );
		$this->assertLinkTargetsEqual( $title, $secondReturn->getPageAsLinkTarget() );
		$this->assertRevisionRecordsEqual( $revTwo, $secondReturn );

		// Assert that the same blob address has been used.
		$this->assertEquals(
			$firstReturn->getSlot( 'main' )->getAddress(),
			$secondReturn->getSlot( 'main' )->getAddress()
		);
		// And that different revisions have been created.
		$this->assertNotSame(
			$firstReturn->getId(),
			$secondReturn->getId()
		);
	}

	public function provideInsertRevisionOn_failures() {
		yield 'no slot' => [
			Title::newFromText( 'UTPage' ),
			[
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
			],
			new MWException( 'At least one slot needs to be defined!' )
		];
		yield 'slot that is not main slot' => [
			Title::newFromText( 'UTPage' ),
			[
				'slot' => SlotRecord::newUnsaved( 'lalala', new WikitextContent( 'Chicken' ) ),
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
			],
			new MWException( 'Only the main slot is supported for now!' )
		];
		yield 'no timestamp' => [
			Title::newFromText( 'UTPage' ),
			[
				'slot' => SlotRecord::newUnsaved( 'main', new WikitextContent( 'Chicken' ) ),
				'comment' => $this->getRandomCommentStoreComment(),
				'user' => true,
			],
			new IncompleteRevisionException( 'timestamp field must not be NULL!' )
		];
		yield 'no comment' => [
			Title::newFromText( 'UTPage' ),
			[
				'slot' => SlotRecord::newUnsaved( 'main', new WikitextContent( 'Chicken' ) ),
				'timestamp' => '20171117010101',
				'user' => true,
			],
			new IncompleteRevisionException( 'comment must not be null!' )
		];
// TODO uncomment me when comment on https://gerrit.wikimedia.org/r/#/c/374077/98 is addressed
//		yield 'no user' => [
//			Title::newFromText( 'UTPage' ),
//			[
//				'slot' => SlotRecord::newUnsaved( 'main', new WikitextContent( 'Chicken' ) ),
//				'comment' => $this->getRandomCommentStoreComment(),
//				'timestamp' => '20171117010101',
//			],
//			new IncompleteRevisionException( 'user must not be null!' )
//		];
	}

	/**
	 * @dataProvider provideInsertRevisionOn_failures
	 * @covers RevisionStore::insertRevisionOn
	 */
	public function testInsertRevisionOn_failures(
		Title $title,
		array $revDetails = [],
		Exception $exception ) {
		$rev = $this->getRevisionRecord( $title, $revDetails );

		$store = MediaWikiServices::getInstance()->getRevisionStore();

		$this->setExpectedException(
			get_class( $exception ),
			$exception->getMessage(),
			$exception->getCode()
		);
		$store->insertRevisionOn( $rev, wfGetDB( DB_MASTER ) );
	}

	public function provideNewNullRevision() {
		yield [
			Title::newFromText( 'UTPage' ),
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment1' ),
			true,
		];
		yield [
			Title::newFromText( 'UTPage' ),
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment2', [ 'a' => 1 ] ),
			false,
		];
	}

	/**
	 * @dataProvider provideNewNullRevision
	 * @covers RevisionStore::newNullRevision
	 */
	public function testNewNullRevision( Title $title, $comment, $minor ) {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$user = TestUserRegistry::getMutableTestUser( __METHOD__ )->getUser();
		$record = $store->newNullRevision(
			wfGetDB( DB_MASTER ),
			$title,
			$comment,
			$minor,
			$user
		);

		$this->assertEquals( $title->getNamespace(), $record->getPageAsLinkTarget()->getNamespace() );
		$this->assertEquals( $title->getDBkey(), $record->getPageAsLinkTarget()->getDBkey() );
		$this->assertEquals( $comment, $record->getComment() );
		$this->assertEquals( $minor, $record->isMinor() );
		$this->assertEquals( $user->getName(), $record->getUser()->getName() );
	}

	/**
	 * @covers RevisionStore::newNullRevision
	 */
	public function testNewNullRevision_nonExistingTitle() {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$record = $store->newNullRevision(
			wfGetDB( DB_MASTER ),
			Title::newFromText( __METHOD__ . '.iDontExist!' ),
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment' ),
			false,
			TestUserRegistry::getMutableTestUser( __METHOD__ )->getUser()
		);
		$this->assertNull( $record );
	}

	/**
	 * @covers RevisionStore::isUnpatrolled
	 */
	public function testIsUnpatrolled_returnsRecentChangesId() {
// TODO test getRevisionById first
//		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
//		$page->doEditContent( new WikitextContent( __METHOD__ ), __METHOD__ );
//
//		$store = MediaWikiServices::getInstance()->getRevisionStore();
//		$revisionRecord = $store->getRevisionById( $page->getLatest() );
//		$result = $store->isUnpatrolled( $revisionRecord );
//
//		$this->assertGreaterThan( 0, $result );
//		$this->assertSame(
//			$page->getRevision()->getRecentChange()->getAttribute( 'rc_id' ),
//			$result
//		);
	}

	public function testGetRecentChange() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testGetRevisionById() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testGetRevisionByTitle() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testGetRevisionByPageId() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testGetRevisionFromTimestamp() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testNewRevisionFromArchiveRow() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testNewRevisionFromRow() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testNewMutableRevisionFromArray() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testLoadRevisionFromId() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testLoadRevisionFromPageId() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testLoadRevisionFromTitle() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testLoadRevisionFromTimestamp() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testGetParentLengths() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testGetPreviousRevision() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testGetNextRevision() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testGetTimestampFromId() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testCountRevisionsByPageId() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testCountRevisionsByTitle() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testUserWasLastToEdit() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

	public function testGetKnownCurrentRevision() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
	}

}
