<?php

namespace MediaWiki\Tests\Revision;

use CommentStoreComment;
use ContentHandler;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\SqlBlobStore;
use Revision;
use StatusValue;
use TextContent;
use Title;
use Wikimedia\TestingAccessWrapper;
use WikitextContent;

/**
 * Tests RevisionStore against the post-migration MCR DB schema.
 *
 * @covers \MediaWiki\Revision\RevisionStore
 *
 * @group RevisionStore
 * @group Storage
 * @group Database
 * @group medium
 */
class McrRevisionStoreDbTest extends RevisionStoreDbTestBase {

	use McrSchemaOverride;

	protected function assertRevisionExistsInDatabase( RevisionRecord $rev ) {
		$numberOfSlots = count( $rev->getSlotRoles() );

		// new schema is written
		$this->assertSelect(
			'slots',
			[ 'count(*)' ],
			[ 'slot_revision_id' => $rev->getId() ],
			[ [ (string)$numberOfSlots ] ]
		);

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$revQuery = $store->getSlotsQueryInfo( [ 'content' ] );

		$this->assertSelect(
			$revQuery['tables'],
			[ 'count(*)' ],
			[
				'slot_revision_id' => $rev->getId(),
			],
			[ [ (string)$numberOfSlots ] ],
			[],
			$revQuery['joins']
		);

		parent::assertRevisionExistsInDatabase( $rev );
	}

	/**
	 * @param SlotRecord $a
	 * @param SlotRecord $b
	 */
	protected function assertSameSlotContent( SlotRecord $a, SlotRecord $b ) {
		parent::assertSameSlotContent( $a, $b );

		// Assert that the same content ID has been used
		$this->assertSame( $a->getContentId(), $b->getContentId() );
	}

	public function provideInsertRevisionOn_successes() {
		foreach ( parent::provideInsertRevisionOn_successes() as $case ) {
			yield $case;
		}

		yield 'Multi-slot revision insertion' => [
			[
				'content' => [
					'main' => new WikitextContent( 'Chicken' ),
					'aux' => new TextContent( 'Egg' ),
				],
				'page' => true,
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
			],
		];
	}

	public function provideNewNullRevision() {
		foreach ( parent::provideNewNullRevision() as $case ) {
			yield $case;
		}

		yield [
			Title::newFromText( 'UTPage_notAutoCreated' ),
			[
				'content' => [
					'main' => new WikitextContent( 'Chicken' ),
					'aux' => new WikitextContent( 'Omelet' ),
				],
			],
			CommentStoreComment::newUnsavedComment( __METHOD__ . ' comment multi' ),
		];
	}

	public function provideNewMutableRevisionFromArray() {
		foreach ( parent::provideNewMutableRevisionFromArray() as $case ) {
			yield $case;
		}

		yield 'Basic array, multiple roles' => [
			[
				'id' => 2,
				'page' => 1,
				'timestamp' => '20171017114835',
				'user_text' => '111.0.1.2',
				'user' => 0,
				'minor_edit' => false,
				'deleted' => 0,
				'len' => 29,
				'parent_id' => 1,
				'sha1' => '89qs83keq9c9ccw9olvvm4oc9oq50ii',
				'comment' => 'Goat Comment!',
				'content' => [
					'main' => new WikitextContent( 'Söme Cöntent' ),
					'aux' => new TextContent( 'Öther Cöntent' ),
				]
			]
		];
	}

	public function testGetQueryInfo_NoSlotDataJoin() {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$queryInfo = $store->getQueryInfo();

		// with the new schema enabled, query info should not join the main slot info
		$this->assertFalse( array_key_exists( 'a_slot_data', $queryInfo['tables'] ) );
		$this->assertFalse( array_key_exists( 'a_slot_data', $queryInfo['joins'] ) );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::insertRevisionOn
	 * @covers \MediaWiki\Revision\RevisionStore::insertSlotRowOn
	 * @covers \MediaWiki\Revision\RevisionStore::insertContentRowOn
	 */
	public function testInsertRevisionOn_T202032() {
		// This test only makes sense for MySQL
		if ( $this->db->getType() !== 'mysql' ) {
			$this->assertTrue( true );
			return;
		}

		// NOTE: must be done before checking MAX(rev_id)
		$page = $this->getTestPage();

		$maxRevId = $this->db->selectField( 'revision', 'MAX(rev_id)' );

		// Construct a slot row that will conflict with the insertion of the next revision ID,
		// to emulate the failure mode described in T202032. Nothing will ever read this row,
		// we just need it to trigger a primary key conflict.
		$this->db->insert( 'slots', [
			'slot_revision_id' => $maxRevId + 1,
			'slot_role_id' => 1,
			'slot_content_id' => 0,
			'slot_origin' => 0
		], __METHOD__ );

		$rev = new MutableRevisionRecord( $page->getTitle() );
		$rev->setTimestamp( '20180101000000' );
		$rev->setComment( CommentStoreComment::newUnsavedComment( 'test' ) );
		$rev->setUser( $this->getTestUser()->getUser() );
		$rev->setContent( 'main', new WikitextContent( 'Text' ) );
		$rev->setPageId( $page->getId() );

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$return = $store->insertRevisionOn( $rev, $this->db );

		$this->assertSame( $maxRevId + 2, $return->getId() );

		// is the new revision correct?
		$this->assertRevisionCompleteness( $return );
		$this->assertRevisionRecordsEqual( $rev, $return );

		// can we find it directly in the database?
		$this->assertRevisionExistsInDatabase( $return );

		// can we load it from the store?
		$loaded = $store->getRevisionById( $return->getId() );
		$this->assertRevisionCompleteness( $loaded );
		$this->assertRevisionRecordsEqual( $return, $loaded );
	}

	/**
	 * Conditions to use together with getSlotsQueryInfo() when selecting slot rows for a given
	 * revision.
	 *
	 * @return array
	 */
	protected function getSlotRevisionConditions( $revId ) {
		return [ 'slot_revision_id' => $revId ];
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getContentBlobsForBatch
	 * @throws \MWException
	 */
	public function testGetContentBlobsForBatch_error() {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1->getTitle()->getPrefixedDBkey(), $text . '1' );
		$this->assertTrue( $editStatus->isGood(), 'Sanity: must create revision 1' );
		/** @var Revision $rev1 */
		$rev1 = $editStatus->getValue()['revision'];

		$contentAddress = $rev1->getRevisionRecord()->getSlot( SlotRecord::MAIN )->getAddress();
		$blobStatus = StatusValue::newGood( [] );
		$blobStatus->warning( 'internalerror', 'oops!' );

		$mockBlobStore = $this->getMock( BlobStore::class );
		$mockBlobStore->method( 'getBlobBatch' )
			->willReturn( $blobStatus );

		$revStore = MediaWikiServices::getInstance()
			->getRevisionStoreFactory()
			->getRevisionStore();
		$wrappedRevStore = TestingAccessWrapper::newFromObject( $revStore );
		$wrappedRevStore->blobStore = $mockBlobStore;

		$result = $revStore->getContentBlobsForBatch( [ $rev1->getId() ] );
		$this->assertTrue( $result->isOK() );
		$this->assertFalse( $result->isGood() );
		$this->assertNotEmpty( $result->getErrors() );

		$records = $result->getValue();
		$this->assertArrayHasKey( $rev1->getId(), $records );

		$mainRow = $records[$rev1->getId()][SlotRecord::MAIN];
		$this->assertNull( $mainRow->blob_data );
		$this->assertSame( [
			[
				'type' => 'warning',
				'message' => 'internalerror',
				'params' => [
					"oops!"
				]
			],
			[
				'type' => 'warning',
				'message' => 'internalerror',
				'params' => [
					"Couldn't find blob data for rev " . $rev1->getId()
				]
			]
		], $result->getErrors() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::getContentBlobsForBatch
	 */
	public function testGetContentBlobsForBatchUsesGetBlobBatch() {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1->getTitle()->getPrefixedDBkey(), $text . '1' );
		$this->assertTrue( $editStatus->isGood(), 'Sanity: must create revision 1' );
		/** @var Revision $rev1 */
		$rev1 = $editStatus->getValue()['revision'];

		$contentAddress = $rev1->getRevisionRecord()->getSlot( SlotRecord::MAIN )->getAddress();
		$mockBlobStore = $this->getMockBuilder( SqlBlobStore::class )
			->disableOriginalConstructor()
			->getMock();
		$mockBlobStore
			->expects( $this->once() )
			->method( 'getBlobBatch' )
			->with( [ $contentAddress ], $this->anything() )
			->willReturn( StatusValue::newGood( [
				$contentAddress => 'Content_From_Mock'
			] ) );
		$mockBlobStore
			->expects( $this->never() )
			->method( 'getBlob' );

		$revStore = MediaWikiServices::getInstance()
			->getRevisionStoreFactory()
			->getRevisionStore();
		$wrappedRevStore = TestingAccessWrapper::newFromObject( $revStore );
		$wrappedRevStore->blobStore = $mockBlobStore;

		$result = $revStore->getContentBlobsForBatch(
			[ $rev1->getId() ],
			[ SlotRecord::MAIN ]
		);
		$this->assertTrue( $result->isGood() );
		$this->assertSame( 'Content_From_Mock',
			$result->getValue()[$rev1->getId()][SlotRecord::MAIN]->blob_data );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionsFromBatch
	 * @throws \MWException
	 */
	public function testNewRevisionsFromBatch_error() {
		$page = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		/** @var Revision $rev1 */
		$rev1 = $page->doEditContent(
			new WikitextContent( $text . '1' ),
			__METHOD__ . 'b',
			0,
			false,
			$this->getTestUser()->getUser()
		)->value['revision'];
		$invalidRow = $this->revisionToRow( $rev1 );
		$invalidRow->rev_id = 100500;
		$result = MediaWikiServices::getInstance()->getRevisionStore()
			->newRevisionsFromBatch(
				[ $this->revisionToRow( $rev1 ), $invalidRow ],
				[
					'slots' => [ SlotRecord::MAIN ],
					'content' => true
				]
			);
		$this->assertFalse( $result->isGood() );
		$this->assertNotEmpty( $result->getErrors() );
		$records = $result->getValue();
		$this->assertRevisionRecordMatchesRevision( $rev1, $records[$rev1->getId()] );
		$this->assertSame( $text . '1',
			$records[$rev1->getId()]->getContent( SlotRecord::MAIN )->serialize() );
		$this->assertEquals( $page->getTitle()->getDBkey(),
			$records[$rev1->getId()]->getPageAsLinkTarget()->getDBkey() );
		$this->assertNull( $records[$invalidRow->rev_id] );
		$this->assertSame( [ [
			'type' => 'warning',
			'message' => 'internalerror',
			'params' => [
				"Couldn't find slots for rev 100500"
			]
		] ], $result->getErrors() );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionsFromBatch
	 */
	public function testNewRevisionFromBatchUsesGetBlobBatch() {
		$page1 = $this->getTestPage();
		$text = __METHOD__ . 'b-ä';
		$editStatus = $this->editPage( $page1->getTitle()->getPrefixedDBkey(), $text . '1' );
		$this->assertTrue( $editStatus->isGood(), 'Sanity: must create revision 1' );
		/** @var Revision $rev1 */
		$rev1 = $editStatus->getValue()['revision'];

		$contentAddress = $rev1->getRevisionRecord()->getSlot( SlotRecord::MAIN )->getAddress();
		$mockBlobStore = $this->getMockBuilder( SqlBlobStore::class )
			->disableOriginalConstructor()
			->getMock();
		$mockBlobStore
			->expects( $this->once() )
			->method( 'getBlobBatch' )
			->with( [ $contentAddress ], $this->anything() )
			->willReturn( StatusValue::newGood( [
				$contentAddress => 'Content_From_Mock'
			] ) );
		$mockBlobStore
			->expects( $this->never() )
			->method( 'getBlob' );

		$revStore = MediaWikiServices::getInstance()
			->getRevisionStoreFactory()
			->getRevisionStore();
		$wrappedRevStore = TestingAccessWrapper::newFromObject( $revStore );
		$wrappedRevStore->blobStore = $mockBlobStore;

		$result = $revStore->newRevisionsFromBatch(
			[ $this->revisionToRow( $rev1 ) ],
			[
				'slots' => [ SlotRecord::MAIN ],
				'content' => true
			]
		);
		$this->assertTrue( $result->isGood() );
		$this->assertSame( 'Content_From_Mock',
			ContentHandler::getContentText( $result->getValue()[$rev1->getId()]
				->getContent( SlotRecord::MAIN ) ) );
	}
}
