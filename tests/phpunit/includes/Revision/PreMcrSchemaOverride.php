<?php
namespace MediaWiki\Tests\Revision;

use Wikimedia\Rdbms\IMaintainableDatabase;
use MediaWiki\DB\PatchFileLocation;

/**
 * Trait providing schema overrides that allow tests to run against the pre-MCR database schema.
 */
trait PreMcrSchemaOverride {

	use PatchFileLocation;
	use McrSchemaDetection;

	/**
	 * @return int
	 */
	protected function getMcrMigrationStage() {
		return MIGRATION_OLD;
	}

	/**
	 * @return string[]
	 */
	protected function getMcrTablesToReset() {
		return [];
	}

	/**
	 * @return array[]
	 */
	protected function getSchemaOverrides( IMaintainableDatabase $db ) {
		$overrides = [
			'scripts' => [],
			'drop' => [],
			'create' => [],
			'alter' => [],
		];

		if ( $this->hasMcrTables( $db ) ) {
			$overrides['drop'] = [ 'slots', 'content', 'slot_roles', 'content_models', ];
			$overrides['scripts'][] = $this->getSqlPatchPath( $db, '/drop-mcr-tables', __DIR__ );
		}

		if ( !$this->hasPreMcrFields( $db ) ) {
			$overrides['alter'][] = 'revision';
			$overrides['scripts'][] = $this->getSqlPatchPath( $db, '/create-pre-mcr-fields', __DIR__ );
		}

		return $overrides;
	}

}
