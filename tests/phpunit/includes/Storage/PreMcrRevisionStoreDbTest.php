<?php
/**
 * Created by PhpStorm.
 * User: daki
 * Date: 28.03.18
 * Time: 17:33
 */

namespace MediaWiki\Tests\Storage;

class PreMcrRevisionStoreDbTest extends RevisionStoreDbTestBase {

	public function __construct( $name = '', $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );
	}

	protected function getMcrMigrationStage() {
		return MIGRATION_OLD;
	}

	protected function getSchemaOverrides() {
		$tables = [];
		$scripts = [];

		if ( $this->hasMcrTables() ) {
			$tables = [ 'slots', 'content', 'slot_roles', 'content_models', ];
			$scripts[] = __DIR__ . '/drop-mcr-tables.sql';
		}

		if ( !$this->hasPreMcrFields() ) {
			$tables[] = 'revision';
			$scripts[] = __DIR__ . '/create-pre-mcr-fields.sql';
		}

		return [ $tables, $scripts ];
	}

}