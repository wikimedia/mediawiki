<?php
namespace MediaWiki\Tests\Storage;

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\RevisionRecord;
use Revision;
use WikiPage;

/**
 * Tests RevisionStore against the post-migration MCR DB schema.
 *
 * @covers MediaWiki\Storage\RevisionStore
 *
 * @group RevisionStore
 * @group Storage
 * @group Database
 * @group medium
 */
class McrRevisionStoreDbTest extends RevisionStoreDbTestBase {

	use McrSchemaOverride;

	public function setUp() {
		parent::setUp();

		// FIXME! Remove this before merging!
		$this->markTestSkipped( 'MIGRATION_NEW mode is work in progress!' );
	}

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

	protected function revisionToRow( Revision $rev ) {
		$page = WikiPage::factory( $rev->getTitle() );
		$rec = $rev->getRevisionRecord();

		return (object)[
			'rev_id' => (string)$rec->getId(),
			'rev_page' => (string)$rec->getPageId(),
			'rev_timestamp' => (string)$rec->getTimestamp(),
			'rev_user_text' => (string)$rec->getUser()->getName(),
			'rev_user' => (string)$rec->getUser()->getId(),
			'rev_minor_edit' => $rec->isMinor() ? '1' : '0',
			'rev_deleted' => (string)$rec->getVisibility(),
			'rev_len' => (string)$rec->getSize(),
			'rev_parent_id' => (string)$rec->getParentId(),
			'rev_sha1' => (string)$rec->getSha1(),
			'rev_comment_text' => $rec->getComment()->text,
			'rev_comment_data' => null,
			'rev_comment_cid' => null,
			'page_namespace' => (string)$page->getTitle()->getNamespace(),
			'page_title' => $page->getTitle()->getDBkey(),
			'page_id' => (string)$page->getId(),
			'page_latest' => (string)$page->getLatest(),
			'page_is_redirect' => $page->isRedirect() ? '1' : '0',
			'page_len' => (string)$page->getContent()->getSize(),
			'user_name' => (string)$rec->getUser()->getName(),
		];
	}

	/**
	 * @covers \MediaWiki\Storage\RevisionStore::insertRevisionOn
	 */
	public function testInsertRevisionOn_multi() {
		$this->markTestIncomplete( 'Writing multiple slots, inheriting some' );
	}

	public function testGetQueryInfo_NoSlotDataJoin() {
		$store = MediaWikiServices::getInstance()->getRevisionStore();
		$queryInfo = $store->getQueryInfo();

		// with the new schema enabled, query info should not join the main slot info
		$this->assertFalse( array_key_exists( 'a_slot_data', $queryInfo['tables'] ) );
		$this->assertFalse( array_key_exists( 'a_slot_data', $queryInfo['joins'] ) );
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
						'ar_content_format' => 'NULL',
						'content_id' => 'a_content.content_id',
						'ar_content_model' => 'a_content_models.model_name',
						'ar_text' => 'NULL',
						'ar_text_id' => 'CAST( SUBSTRING(a_content.content_address FROM 4) AS INTEGER )',
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
		// TODO: more option variations
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
						'rev_content_format' => 'NULL',
						'rev_content_model' => 'a_content_models.model_name',
						'rev_text_id' => 'CAST( SUBSTRING(a_content.content_address FROM 4) AS INTEGER )',
						'content_id' => 'a_content.content_id',
					]
				),
				'joins' => [
					'page' => [ 'INNER JOIN', [ 'page_id = rev_page' ] ],
					'user' => [ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ],
					'text' => [ 'LEFT JOIN', [ 'rev_text_id=old_id' ] ],
					'a_content' => [ 'JOIN', 'a_slots.slot_content_id = a_content.content_id' ],
					'a_content_models' => [ 'JOIN', 'a_content.content_model = a_content_models.model_id' ],
					'a_slot_data' => [ 'JOIN', 'rev_id = a_slots.slot_revision_id' ],
				],
			]
		];
	}

}