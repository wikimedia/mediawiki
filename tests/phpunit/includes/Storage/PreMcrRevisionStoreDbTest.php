<?php
/**
 * Created by PhpStorm.
 * User: daki
 * Date: 28.03.18
 * Time: 17:33
 */

namespace MediaWiki\Tests\Storage;

use Wikimedia\Rdbms\IMaintainableDatabase;

class PreMcrRevisionStoreDbTest extends RevisionStoreDbTestBase {

	public function __construct( $name = '', $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );
	}

	protected function getMcrMigrationStage() {
		return MIGRATION_OLD;
	}

	protected function getSchemaOverrides( IMaintainableDatabase $db ) {
		$overrides = [
			'scripts' => [],
			'drop' => [],
			'create' => [],
			'alter' => [],
		];

		if ( $this->hasMcrTables() ) {
			$overrides['drop'] = [ 'slots', 'content', 'slot_roles', 'content_models', ];
			$overrides['scripts'][] = __DIR__ . '/drop-mcr-tables.sql';
		}

		if ( !$this->hasPreMcrFields() ) {
			$overrides['alter'][] = 'revision';
			$overrides['scripts'][] = __DIR__ . '/create-pre-mcr-fields.sql';
		}

		return $overrides;
	}

}
