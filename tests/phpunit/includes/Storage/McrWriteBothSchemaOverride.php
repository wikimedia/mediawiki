<?php
namespace MediaWiki\Tests\Storage;

use Wikimedia\Rdbms\IMaintainableDatabase;
use MediaWiki\DB\PatchFileLocation;

/**
 * Trait providing schema overrides that allow tests to run against the intermediate MCR database
 * schema for use during schema migration.
 */
trait McrWriteBothSchemaOverride {

	use PatchFileLocation;
	use McrSchemaDetection;

	/**
	 * @return int
	 */
	protected function getMcrMigrationStage() {
		return MIGRATION_WRITE_BOTH;
	}

	/**
	 * @return string[]
	 */
	protected function getMcrTablesToReset() {
		return [ 'content', 'content_models', 'slots', 'slot_roles' ];
	}

	/**
	 * @override MediaWikiTestCase::getSchemaOverrides
	 * @return array[]
	 */
	protected function getSchemaOverrides( IMaintainableDatabase $db ) {
		$overrides = [
			'scripts' => [],
			'drop' => [],
			'create' => [],
			'alter' => [],
		];

		if ( !$this->hasMcrTables( $db ) ) {
			$overrides['create'] = [ 'slots', 'content', 'slot_roles', 'content_models', ];
			$overrides['scripts'][] = $this->getSqlPatchPath( $db, 'patch-slot_roles' );
			$overrides['scripts'][] = $this->getSqlPatchPath( $db, 'patch-content_models' );
			$overrides['scripts'][] = $this->getSqlPatchPath( $db, 'patch-content' );
			$overrides['scripts'][] = $this->getSqlPatchPath( $db, 'patch-slots' );
		}

		if ( !$this->hasPreMcrFields( $db ) ) {
			$overrides['alter'][] = 'revision';
			$overrides['scripts'][] = $this->getSqlPatchPath( $db, 'create-pre-mcr-fields', __DIR__ );
		}

		return $overrides;
	}

}
