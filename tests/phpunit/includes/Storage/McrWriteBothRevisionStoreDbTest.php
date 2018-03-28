<?php
/**
 * Created by PhpStorm.
 * User: daki
 * Date: 28.03.18
 * Time: 17:33
 */

namespace MediaWiki\Tests\Storage;

use MediaWiki\Storage\RevisionRecord;
use Revision;
use Wikimedia\Rdbms\IMaintainableDatabase;
use WikiPage;

class McrWriteBothRevisionStoreDbTest extends RevisionStoreDbTestBase {

	public function __construct( $name = '', $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );
	}

	protected function getMcrMigrationStage() {
		return MIGRATION_WRITE_BOTH;
	}

	protected function getSchemaOverrides( IMaintainableDatabase $db ) {
		$patchDir = $GLOBALS['IP'] . '/maintenance/archives';
		$overrides = [
			'scripts' => [],
			'drop' => [],
			'create' => [],
			'alter' => [],
		];

		if ( !$this->hasMcrTables() ) {
			$overrides['create'] = [ 'slots', 'content', 'slot_roles', 'content_models', ];
			$overrides['scripts'][] = $patchDir . '/slots.sql';
		}

		if ( !$this->hasPreMcrFields() ) {
			$overrides['alter'][] = 'revision';
			$overrides['scripts'][] = __DIR__ . '/create-pre-mcr-fields.sql';
		}

		return $overrides;
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

	public function setUp() {
		parent::setUp();
		$this->tablesUsed[] = 'content';
		$this->tablesUsed[] = 'content_models';
		$this->tablesUsed[] = 'slots';
		$this->tablesUsed[] = 'slot_roles';
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
}
