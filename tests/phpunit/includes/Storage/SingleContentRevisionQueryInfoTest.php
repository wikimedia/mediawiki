<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Storage\SingleContentRevisionQueryInfo;
use MediaWikiTestCase;

class SingleContentRevisionQueryInfoTest extends MediaWikiTestCase {

	use SingleContentRevisionQueryInfo;

	private function getDefaultQueryFields() {
		return [
			'rev_id',
			'rev_page',
			'rev_text_id',
			'rev_timestamp',
			'rev_user_text',
			'rev_user',
			'rev_minor_edit',
			'rev_deleted',
			'rev_len',
			'rev_parent_id',
			'rev_sha1',
		];
	}

	private function getCommentQueryFields() {
		return [
			'rev_comment_text' => 'rev_comment',
			'rev_comment_data' => 'NULL',
			'rev_comment_cid' => 'NULL',
		];
	}

	private function getContentHandlerQueryFields() {
		return [
			'rev_content_format',
			'rev_content_model',
		];
	}

	public function provideGetQueryInfo() {
		yield [
			[ 'useContentHandler' ],
			[
				'tables' => [ 'revision' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
					$this->getContentHandlerQueryFields()
				),
				'joins' => [],
			]
		];
		yield [
			[],
			[
				'tables' => [ 'revision' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields()
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
					[
						'page_namespace',
						'page_title',
						'page_id',
						'page_latest',
						'page_is_redirect',
						'page_len',
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
					[
						'user_name',
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
					[
						'old_text',
						'old_flags',
					]
				),
				'joins' => [
					'text' => [ 'INNER JOIN', [ 'rev_text_id=old_id' ] ],
				],
			]
		];
		yield [
			[ 'page', 'user', 'text', 'useContentHandler' ],
			[
				'tables' => [ 'revision', 'page', 'user', 'text' ],
				'fields' => array_merge(
					$this->getDefaultQueryFields(),
					$this->getCommentQueryFields(),
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
						'old_flags',
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

	/**
	 * @dataProvider provideGetQueryInfo
	 * @covers \MediaWiki\Storage\SingleContentRevisionQueryInfo::getQueryInfo
	 */
	public function testGetQueryInfo( $options, $expected ) {
		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', MIGRATION_OLD );
		$this->assertEquals( $expected, $this->getQueryInfo( $options ) );
	}

	private function getDefaultArchiveFields() {
		return [
			'ar_id',
			'ar_page_id',
			'ar_namespace',
			'ar_title',
			'ar_rev_id',
			'ar_text',
			'ar_text_id',
			'ar_timestamp',
			'ar_user_text',
			'ar_user',
			'ar_minor_edit',
			'ar_deleted',
			'ar_len',
			'ar_parent_id',
			'ar_sha1',
		];
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionQueryInfo::getArchiveQueryInfo
	 */
	public function testGetArchiveQueryInfo_contentHandlerDb() {
		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', MIGRATION_OLD );
		$this->assertEquals(
			[
				'tables' => [
					'archive'
				],
				'fields' => array_merge(
					$this->getDefaultArchiveFields(),
					[
						'ar_comment_text' => 'ar_comment',
						'ar_comment_data' => 'NULL',
						'ar_comment_cid' => 'NULL',
						'ar_content_format',
						'ar_content_model',
					]
				),
				'joins' => [],
			],
			$this->getArchiveQueryInfo( [ 'useContentHandler' ] )
		);
	}

	/**
	 * @covers \MediaWiki\Storage\SingleContentRevisionStore::getArchiveQueryInfo
	 */
	public function testGetArchiveQueryInfo_noContentHandlerDb() {
		$this->setMwGlobals( 'wgCommentTableSchemaMigrationStage', MIGRATION_OLD );
		$this->assertEquals(
			[
				'tables' => [
					'archive'
				],
				'fields' => array_merge(
					$this->getDefaultArchiveFields(),
					[
						'ar_comment_text' => 'ar_comment',
						'ar_comment_data' => 'NULL',
						'ar_comment_cid' => 'NULL',
					]
				),
				'joins' => [],
			],
			$this->getArchiveQueryInfo()
		);
	}

}
