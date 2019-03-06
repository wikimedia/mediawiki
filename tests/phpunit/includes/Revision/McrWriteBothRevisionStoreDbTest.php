<?php
namespace MediaWiki\Tests\Revision;

use InvalidArgumentException;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use Revision;
use WikitextContent;

/**
 * Tests RevisionStore against the intermediate MCR DB schema for use during schema migration.
 *
 * @covers \MediaWiki\Revision\RevisionStore
 *
 * @group RevisionStore
 * @group Storage
 * @group Database
 * @group medium
 */
class McrWriteBothRevisionStoreDbTest extends RevisionStoreDbTestBase {

	use McrWriteBothSchemaOverride;

	protected function revisionToRow( Revision $rev, $options = [ 'page', 'user', 'comment' ] ) {
		$row = parent::revisionToRow( $rev, $options );

		$row->rev_text_id = (string)$rev->getTextId();
		$row->rev_content_format = (string)$rev->getContentFormat();
		$row->rev_content_model = (string)$rev->getContentModel();

		return $row;
	}

	protected function assertRevisionExistsInDatabase( RevisionRecord $rev ) {
		// New schema is being written
		$this->assertSelect(
			'slots',
			[ 'count(*)' ],
			[ 'slot_revision_id' => $rev->getId() ],
			[ [ '1' ] ]
		);

		$this->assertSelect(
			'content',
			[ 'count(*)' ],
			[ 'content_address' => $rev->getSlot( 'main' )->getAddress() ],
			[ [ '1' ] ]
		);

		// Legacy schema is still being written
		$this->assertSelect(
			[ 'revision', 'text' ],
			[ 'count(*)' ],
			[ 'rev_id' => $rev->getId(), 'rev_text_id > 0' ],
			[ [ 1 ] ],
			[],
			[ 'text' => [ 'JOIN', [ 'rev_text_id = old_id' ] ] ]
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
		if ( $a->hasContentId() && $b->hasContentId() ) {
			$this->assertSame( $a->getContentId(), $b->getContentId() );
		}
	}

	public function provideInsertRevisionOn_failures() {
		foreach ( parent::provideInsertRevisionOn_failures() as $case ) {
			yield $case;
		}

		yield 'slot that is not main slot' => [
			[
				'content' => [
					'main' => new WikitextContent( 'Chicken' ),
					'lalala' => new WikitextContent( 'Duck' ),
				],
				'comment' => $this->getRandomCommentStoreComment(),
				'timestamp' => '20171117010101',
				'user' => true,
			],
			new InvalidArgumentException( 'Only the main slot is supported' )
		];
	}

	public function provideNewMutableRevisionFromArray() {
		foreach ( parent::provideNewMutableRevisionFromArray() as $case ) {
			yield $case;
		}

		yield 'Basic array, with page & id' => [
			[
				'id' => 2,
				'page' => 1,
				'text_id' => 2,
				'timestamp' => '20171017114835',
				'user_text' => '111.0.1.2',
				'user' => 0,
				'minor_edit' => false,
				'deleted' => 0,
				'len' => 46,
				'parent_id' => 1,
				'sha1' => 'rdqbbzs3pkhihgbs8qf2q9jsvheag5z',
				'comment' => 'Goat Comment!',
				'content_format' => 'text/x-wiki',
				'content_model' => 'wikitext',
			]
		];
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionStore::newRevisionFromArchiveRow
	 * @covers \MediaWiki\Revision\RevisionStore::insertRevisionOn
	 */
	public function testInsertRevisionFromArchiveRow_unmigratedArchiveRow() {
		// The main purpose of this test is to assert that after reading an archive
		// row using the old schema it can be inserted into the revision table,
		// and a slot row is created based on slot emulated from the old-style archive row,
		// when none such slot row exists yet.

		$title = $this->getTestPage()->getTitle();

		$this->db->insert(
			'text',
			[ 'old_text' => 'Just a test', 'old_flags' => 'utf-8' ],
			__METHOD__
		);

		$textId = $this->db->insertId();

		$row = (object)[
			'ar_minor_edit' => '0',
			'ar_user' => '0',
			'ar_user_text' => '127.0.0.1',
			'ar_actor' => null,
			'ar_len' => '11',
			'ar_deleted' => '0',
			'ar_rev_id' => 112277,
			'ar_timestamp' => $this->db->timestamp( '20180101000000' ),
			'ar_sha1' => 'deadbeef',
			'ar_page_id' => $title->getArticleID(),
			'ar_comment_text' => 'just a test',
			'ar_comment_data' => null,
			'ar_comment_cid' => null,
			'ar_content_format' => null,
			'ar_content_model' => null,
			'ts_tags' => null,
			'ar_id' => 17,
			'ar_namespace' => $title->getNamespace(),
			'ar_title' => $title->getDBkey(),
			'ar_text_id' => $textId,
			'ar_parent_id' => 112211,
		];

		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$rev = $store->newRevisionFromArchiveRow( $row );

		// re-insert archived revision
		$return = $store->insertRevisionOn( $rev, $this->db );

		// is the new revision correct?
		$this->assertRevisionCompleteness( $return );
		$this->assertRevisionRecordsEqual( $rev, $return );

		// can we load it from the store?
		$loaded = $store->getRevisionById( $return->getId() );
		$this->assertNotNull( $loaded );
		$this->assertRevisionCompleteness( $loaded );
		$this->assertRevisionRecordsEqual( $return, $loaded );

		// can we find it directly in the database?
		$this->assertRevisionExistsInDatabase( $return );
	}

}
