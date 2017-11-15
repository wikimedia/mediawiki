<?php

namespace MediaWiki\Tests\Storage;

use CommentStoreComment;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\MutableRevisionRecord;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionStore;
use MediaWiki\Storage\RevisionStoreRecord;
use MediaWiki\Storage\SlotRecord;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiTestCase;
use TestUserRegistry;
use Title;
use User;
use WikiPage;
use WikitextContent;

/**
 * @group Database
 */
class RevisionStoreDbTest extends MediaWikiTestCase {

	/**
	 * @param mixed[] $details
	 *
	 * @return RevisionRecord
	 */
	private function getRevisionRecord( $details = [] ) {
		$rev = new MutableRevisionRecord( Title::newFromText( __METHOD__ ) );
		isset( $details['slot'] )
			? $rev->setSlot( SlotRecord::newUnsaved(
			$details['slot']['role'],
			$details['slot']['content']
		) )
			: null;
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

	public function testInsertRevisionOn() {
		$this->markTestSkipped( 'Not yet written.' );
//		$page = WikiPage::factory( Title::newFromText( 'UTPage' ) );
//		$rev = $this->getRevisionRecord(
//			[
//				'slot' => [
//					'role' => 'main',
//					'content' => new WikitextContent( 'Chicken' ),
//				],
//				'page' => $page->getId(),
//				'parent' => $page->getLatest()
//			]
//		);
//		$store = MediaWikiServices::getInstance()->getRevisionStore();
//		$return = $store->insertRevisionOn( $rev, wfGetDB( DB_MASTER ) );
	}

	public function provideNewNullRevision() {
		yield [
			Title::newFromText( __METHOD__ . '.a' ),
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment', [ 'a' => 1 ] ),
			true,
			'aaa'
		];
	}

	/**
	 * @dataProvider  provideNewNullRevision
	 * @covers RevisionStore::newNullRevision
	 */
	public function testNewNullRevision( Title $title, $comment, $minor, $userSuffix ) {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$username = __METHOD__ . ".$userSuffix";
		$record = $store->newNullRevision(
			wfGetDB( DB_MASTER ),
			$title,
			$comment,
			$minor,
			TestUserRegistry::getMutableTestUser( $username )->getUser()
		);

		$this->assertEquals( $title->getNamespace(), $record->getPageAsLinkTarget()->getNamespace() );
		$this->assertEquals( $title->getDBkey(), $record->getPageAsLinkTarget()->getDBkey() );
		$this->assertEquals( $comment, $record->getComment() );
		$this->assertEquals( $minor, $record->isMinor() );
		$this->assertEquals( $username, $record->getUser()->getName() );
	}

	public function testIsUnpatrolled() {
		// TODO write me
		$this->markTestSkipped( 'Not yet written.' );
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
