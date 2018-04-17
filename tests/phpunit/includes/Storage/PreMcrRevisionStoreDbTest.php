<?php
namespace MediaWiki\Tests\Storage;

/**
 * Tests RevisionStore against the pre-MCR DB schema.
 *
 * @covers MediaWiki\Storage\RevisionStore
 *
 * @group RevisionStore
 * @group Storage
 * @group Database
 * @group medium
 */
class PreMcrRevisionStoreDbTest extends RevisionStoreDbTestBase {

	use PreMcrSchemaOverride;

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
			[ 'page', 'user', 'text' ],
			[
				'tables' => [ 'revision', 'page', 'user', 'text' ],
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
						'old_text',
						'old_flags'
					]
				),
				'joins' => [
					'page' => [ 'INNER JOIN', [ 'page_id = rev_page' ] ],
					'user' => [ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ],
					'text' => [ 'INNER JOIN', [ 'rev_text_id=old_id' ] ],
				],
			]
		];
	}

	public function provideGetSlotsQueryInfo() {
		yield [
			[],
			[
				'tables' => [
					'slots' => 'revision',
				],
				'fields' => array_merge(
					[
						'slot_revision_id' => 'revision_id',
						'slot_content_id' => 'NULL',
						'slot_origin' => 'revision_id',
						'role_name' => '"main"',
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
						'slot_revision_id' => 'revision_id',
						'slot_content_id' => 'NULL',
						'slot_origin' => 'revision_id',
						'role_name' => '"main"',
						'content_size' => 'rev_len',
						'content_sha1' => 'rev_sha1',
						'content_address' => 'CONCAT( "tt:", rev_text_id )',
						'model_name' => 'rev_content_model',
					]
				),
				'joins' => [],
			]
		];
	}

}
