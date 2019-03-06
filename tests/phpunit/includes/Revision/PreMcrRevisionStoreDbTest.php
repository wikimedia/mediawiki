<?php
namespace MediaWiki\Tests\Revision;

use InvalidArgumentException;
use MediaWiki\Revision\RevisionRecord;
use Revision;
use WikitextContent;

/**
 * Tests RevisionStore against the pre-MCR DB schema.
 *
 * @covers \MediaWiki\Revision\RevisionStore
 *
 * @group RevisionStore
 * @group Storage
 * @group Database
 * @group medium
 */
class PreMcrRevisionStoreDbTest extends RevisionStoreDbTestBase {

	use PreMcrSchemaOverride;

	protected function revisionToRow( Revision $rev, $options = [ 'page', 'user', 'comment' ] ) {
		$row = parent::revisionToRow( $rev, $options );

		$row->rev_text_id = (string)$rev->getTextId();
		$row->rev_content_format = (string)$rev->getContentFormat();
		$row->rev_content_model = (string)$rev->getContentModel();

		return $row;
	}

	protected function assertRevisionExistsInDatabase( RevisionRecord $rev ) {
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
