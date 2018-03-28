<?php
namespace MediaWiki\Tests\Storage;

use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\SlotRecord;
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

	/**
	 * @param SlotRecord $a
	 * @param SlotRecord $b
	 */
	protected function assertSameSlotContent( SlotRecord $a, SlotRecord $b ) {
		parent::assertSameSlotContent( $a, $b );

		// Assert that the same content ID has been used
		$this->assertSame( $a->getContentId(), $b->getContentId() );
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
					]
				),
				'joins' => [
				],
			]
		];
	}

	public function provideGetQueryInfo() {
		// TODO: more option variations
		yield [
			[ 'page', 'user' ],
			[
				'tables' => [
					'revision',
					'page',
					'user',
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
					]
				),
				'joins' => [
					'page' => [ 'INNER JOIN', [ 'page_id = rev_page' ] ],
					'user' => [ 'LEFT JOIN', [ 'rev_user != 0', 'user_id = rev_user' ] ],
				],
			]
		];
	}

}
