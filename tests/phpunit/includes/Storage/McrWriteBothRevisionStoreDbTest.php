<?php
namespace MediaWiki\Tests\Storage;

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\RevisionRecord;

/**
 * Tests RevisionStore against the intermediate MCR DB schema for use during schema migration.
 *
 * @covers MediaWiki\Storage\RevisionStore
 *
 * @group RevisionStore
 * @group Storage
 * @group Database
 * @group medium
 */
class McrWriteBothRevisionStoreDbTest extends RevisionStoreDbTestBase {

	use McrWriteBothSchemaOverride;

	protected function assertRevisionExistsInDatabase( RevisionRecord $rev ) {
		parent::assertRevisionExistsInDatabase( $rev );

		$this->assertSelect(
			'slots', [ 'count(*)' ], [ 'slot_revision_id' => $rev->getId() ], [ [ '1' ] ]
		);
		$this->assertSelect(
			'content',
			[ 'count(*)' ],
			[ 'content_address' => $rev->getSlot( 'main' )->getAddress() ],
			[ [ '1' ] ]
		);
	}

	public function provideBooleans() {
		return [
			[ true ],
			[ false ],
		];
	}

	/**
	 * @dataProvider provideBooleans
	 * @param bool $dbAlreadyHasMainSlotId
	 */
	public function testGetQueryInfo_SlotDataJoin( $dbAlreadyHasMainSlotId ) {
		if ( $dbAlreadyHasMainSlotId ) {
			$slotRoleStore = MediaWikiServices::getInstance()->getSlotRoleStore();
			$dbAlreadyHasMainSlotId = $slotRoleStore->acquireId( 'main' );
		}

		$store = MediaWikiServices::getInstance()->getRevisionStore();

		$queryInfo = $store->getQueryInfo();

		$this->assertTrue( array_key_exists( 'a_slot_data', $queryInfo['tables'] ) );
		$this->assertTrue( array_key_exists( 'a_slot_data', $queryInfo['joins'] ) );

		$joinClause = $queryInfo['joins']['a_slot_data'][1];
		if ( $dbAlreadyHasMainSlotId ) {
			$this->assertContains( 'slot_role_id =', $joinClause );
			$this->assertContains( 'AND', $joinClause );
		} else {
			$this->assertNotContains( 'slot_role_id =', $joinClause );
			$this->assertNotContains( 'AND', $joinClause );
		}
	}

	public function provideGetArchiveQueryInfo() {
		yield [
			[
				'tables' => [
					'archive',
					'a_slot_data' => [
						'a_slots' => 'slots',
						'a_content' => 'content',
						'a_content_models' => 'content_models'
					],
				],
				'fields' => array_merge(
					$this->getDefaultArchiveFields( false ),
					[
						'ar_comment_text' => 'ar_comment',
						'ar_comment_data' => 'NULL',
						'ar_comment_cid' => 'NULL',
						'ar_user_text' => 'ar_user_text',
						'ar_user' => 'ar_user',
						'ar_actor' => 'NULL',
						// phpcs:ignore Generic.Files.LineLength.TooLong
						'ar_content_format' => ' (CASE WHEN a_content_models.model_name IS NULL THEN ar_content_format ELSE NULL END) ',
						'content_id' => 'a_content.content_id',
						'ar_content_model' => 'COALESCE( a_content_models.model_name, ar_content_model )',
						'ar_text' => 'NULL',
						// phpcs:ignore Generic.Files.LineLength.TooLong
						'ar_text_id' => ' (CASE WHEN a_content.content_address IS NULL THEN ar_text_id ELSE CAST( SUBSTRING(a_content.content_address FROM 4) AS INTEGER ) END) ',
					]
				),
				'joins' => [
					'a_content' => [ 'JOIN', 'a_slots.slot_content_id = a_content.content_id' ],
					'a_content_models' => [ 'JOIN', 'a_content.content_model = a_content_models.model_id' ],
					'a_slot_data' => [ 'JOIN', 'ar_rev_id = a_slots.slot_revision_id' ],
				],
			]
		];
	}

	public function provideGetQueryInfo() {
		// TODO: more option variations!
		yield [
			[ 'page', 'user', 'text' ],
			[
				'tables' => [
					'revision',
					'page',
					'user',
					'text',
					'a_slot_data' => [
						'a_slots' => 'slots',
						'a_content' => 'content',
						'a_content_models' => 'content_models'
					],
				],
				'fields' => array_merge(
					$this->getDefaultQueryFields( false ),
					$this->getCommentQueryFields(),
					$this->getActorQueryFields(),
					[
						'page_namespace',
						'page_title',
						'page_id',
						'page_latest',
						'page_is_redirect',
						'page_len',
						'user_name',
						'old_text',
						'old_flags',
						// phpcs:ignore Generic.Files.LineLength.TooLong
						'rev_content_format' => ' (CASE WHEN a_content_models.model_name IS NULL THEN rev_content_format ELSE NULL END) ',
						'rev_content_model' => 'COALESCE( a_content_models.model_name, rev_content_model )',
						// phpcs:ignore Generic.Files.LineLength.TooLong
						'rev_text_id' => ' (CASE WHEN a_content.content_address IS NULL THEN rev_text_id ELSE CAST( SUBSTRING(a_content.content_address FROM 4) AS INTEGER ) END) ',
						'content_id' => 'a_content.content_id',
					]
				),
				'joins' => [
					'page' => [ 'INNER JOIN', [ 'page_id = rev_page' ] ],
					'user' => [ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ],
					'text' => [ 'LEFT JOIN', [ 'rev_text_id=old_id' ] ],
					'a_content' => [ 'JOIN', 'a_slots.slot_content_id = a_content.content_id' ],
					'a_content_models' => [ 'JOIN', 'a_content.content_model = a_content_models.model_id' ],
					'a_slot_data' => [ 'LEFT JOIN', 'rev_id = a_slots.slot_revision_id' ],
				],
			]
		];
	}

}
