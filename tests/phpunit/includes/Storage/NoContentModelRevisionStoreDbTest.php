<?php
namespace MediaWiki\Tests\Storage;

/**
 * Tests RevisionStore against the pre-MCR, pre-ContentHandler DB schema.
 *
 * @covers MediaWiki\Storage\RevisionStore
 *
 * @group RevisionStore
 * @group Storage
 * @group Database
 * @group medium
 */
class NoContentModelRevisionStoreDbTest extends RevisionStoreDbTestBase {

	use PreMcrSchemaOverride;

	protected function getContentHandlerUseDB() {
		return false;
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
					[ 'content_id' => 'NULL' ]
				),
				'joins' => [],
			]
		];
		yield [
			[ 'page' ],
			[
				'tables' => [ 'revision', 'page' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					$this->getActorQueryFields(),
					[
						'page_namespace',
						'page_title',
						'page_id',
						'page_latest',
						'page_is_redirect',
						'page_len',
						'content_id' => 'NULL',
					]
				),
				'joins' => [
					'page' => [ 'INNER JOIN', [ 'page_id = rev_page' ] ],
				],
			]
		];
		yield [
			[ 'user' ],
			[
				'tables' => [ 'revision', 'user' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					$this->getActorQueryFields(),
					[
						'user_name',
						'content_id' => 'NULL',
					]
				),
				'joins' => [
					'user' => [ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ],
				],
			]
		];
		yield [
			[ 'text' ],
			[
				'tables' => [ 'revision', 'text' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					$this->getActorQueryFields(),
					[
						'old_text',
						'old_flags',
						'content_id' => 'NULL',
					]
				),
				'joins' => [
					'text' => [ 'LEFT JOIN', [ 'rev_text_id=old_id' ] ],
				],
			]
		];
	}

}
