<?php
namespace MediaWiki\Tests\Revision;

use Wikimedia\Rdbms\IMaintainableDatabase;
use MediaWiki\DB\PatchFileLocation;

/**
 * Trait providing schema overrides that allow tests to run against the post-migration
 * MCR database schema.
 */
trait McrSchemaOverride {

	use PatchFileLocation;
	use McrSchemaDetection;

	/**
	 * @return int
	 */
	protected function getMcrMigrationStage() {
		return MIGRATION_NEW;
	}

	/**
	 * @return string[]
	 */
	protected function getMcrTablesToReset() {
		return [
			'content',
			'content_models',
			'slots',
			'slot_roles',
		];
	}

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
			$overrides['scripts'][] = $this->getSqlPatchPath( $db, 'patch-slots.sql' );
		}

		if ( !$this->hasPreMcrFields( $db ) ) {
			$overrides['alter'][] = 'revision';
			$overrides['scripts'][] = $this->getSqlPatchPath( $db, 'drop-pre-mcr-fields', __DIR__ );
		}

		return $overrides;
	}

}
