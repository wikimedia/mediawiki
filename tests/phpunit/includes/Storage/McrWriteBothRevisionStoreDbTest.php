<?php
namespace MediaWiki\Tests\Storage;

use InvalidArgumentException;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\SlotRecord;
use Revision;
use WikitextContent;

/**
 * Tests RevisionStore against the intermediate MCR DB schema for use during schema migration.
 *
 * @covers \MediaWiki\Storage\RevisionStore
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
			[ 'text' => [ 'INNER JOIN', [ 'rev_text_id = old_id' ] ] ]
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

	public function provideGetArchiveQueryInfo() {
		yield [
			[
				'tables' => [ 'archive' ],
				'fields' => array_merge(
					$this->getDefaultArchiveFields(),
					[
						'ar_comment_text' => 'ar_comment',
						'ar_comment_data' => 'NULL',
						'ar_comment_cid' => 'NULL',
						'ar_user_text' => 'ar_user_text',
						'ar_user' => 'ar_user',
						'ar_actor' => 'NULL',
						'ar_content_format',
						'ar_content_model',
					]
				),
				'joins' => [],
			]
		];
	}

	public function provideGetQueryInfo() {
		yield [
			[],
			[
				'tables' => [ 'revision' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					$this->getActorQueryFields(),
					$this->getContentHandlerQueryFields()
				),
				'joins' => [],
			]
		];
		yield [
			[ 'page', 'user' ],
			[
				'tables' => [ 'revision', 'page', 'user' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					$this->getActorQueryFields(),
					$this->getContentHandlerQueryFields(),
					[
						'page_namespace',
						'page_title',
						'page_id',
						'page_latest',
						'page_is_redirect',
						'page_len',
						'user_name',
					]
				),
				'joins' => [
					'page' => [ 'INNER JOIN', [ 'page_id = rev_page' ] ],
					'user' => [ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ],
				],
			]
		];
	}

	public function provideGetSlotsQueryInfo() {
		$db = wfGetDB( DB_REPLICA );

		yield [
			[],
			[
				'tables' => [
					'slots' => 'revision',
				],
				'fields' => array_merge(
					[
						'slot_revision_id' => 'slots.rev_id',
						'slot_content_id' => 'NULL',
						'slot_origin' => 'slots.rev_id',
						'role_name' => $db->addQuotes( 'main' ),
					]
				),
				'joins' => [],
			]
		];
		yield [
			[ 'content' ],
			[
				'tables' => [
					'slots' => 'revision',
				],
				'fields' => array_merge(
					[
						'slot_revision_id' => 'slots.rev_id',
						'slot_content_id' => 'NULL',
						'slot_origin' => 'slots.rev_id',
						'role_name' => $db->addQuotes( 'main' ),
						'content_size' => 'slots.rev_len',
						'content_sha1' => 'slots.rev_sha1',
						'content_address' => $db->buildConcat( [
							$db->addQuotes( 'tt:' ), 'slots.rev_text_id' ] ),
						'model_name' => 'slots.rev_content_model',
					]
				),
				'joins' => [],
			]
		];
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

}
