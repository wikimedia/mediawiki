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
		$main = $rec->getSlot( 'main' );

		return (object)[
			'rev_id' => (string)$rev->getId(),
			'rev_page' => (string)$rev->getPage(),
			'rev_text_id' => (string)$rev->getTextId(),
			'rev_timestamp' => (string)$rev->getTimestamp(),
			'rev_user_text' => (string)$rev->getUserText(),
			'rev_user' => (string)$rev->getUser(),
			'rev_minor_edit' => $rev->isMinor() ? '1' : '0',
			'rev_deleted' => (string)$rev->getVisibility(),
			'rev_len' => (string)$rev->getSize(),
			'rev_parent_id' => (string)$rev->getParentId(),
			'rev_sha1' => (string)$rev->getSha1(),
			'rev_comment_text' => $rev->getComment(),
			'rev_comment_data' => null,
			'rev_comment_cid' => null,
			'rev_content_format' => $rev->getContentFormat(),
			'rev_content_model' => $rev->getContentModel(),
			'page_namespace' => (string)$page->getTitle()->getNamespace(),
			'page_title' => $page->getTitle()->getDBkey(),
			'page_id' => (string)$page->getId(),
			'page_latest' => (string)$page->getLatest(),
			'page_is_redirect' => $page->isRedirect() ? '1' : '0',
			'page_len' => (string)$page->getContent()->getSize(),
			'user_name' => (string)$rev->getUserText(),
			'content_id' => $main->getContentId(),
			'content_address' => $main->getAddress(),
			'content_size' => $main->getSize(),
			'content_sha1' => $main->getSha1(),
			'slot_revision_id' => $main->getRevision(),
			'slot_origin' => $main->getOrigin(),
			'slot_content_id' => $main->getContentId(),
			'model_name' => $main->getModel(),
			'role_name' => $main->getRole(),
		];
	}
}
