<?php
/**
 * Created by PhpStorm.
 * User: daki
 * Date: 28.03.18
 * Time: 17:33
 */

namespace MediaWiki\Tests\Storage;

use MediaWiki\Storage\RevisionRecord;

class McrWriteBothRevisionStoreDbTest extends RevisionStoreDbTestBase {

	public function __construct( $name = '', $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );
	}

	protected function getMcrMigrationStage() {
		return MIGRATION_WRITE_BOTH;
	}

	protected function getSchemaOverrides() {
		$tables = [];
		$scripts = [];
		$patchDir = $GLOBALS['IP'] . '/maintenance/archives';

		if ( !$this->hasMcrTables() ) {
			$tables = [ 'slots', 'content', 'slot_roles', 'content_models', ];
			$scripts[] = $patchDir . '/slots.sql';
		}

		if ( !$this->hasPreMcrFields() ) {
			$tables[] = 'revision';
			$scripts[] = __DIR__ . '/create-pre-mcr-fields.sql';
		}

		return [ $tables, $scripts ];
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
}