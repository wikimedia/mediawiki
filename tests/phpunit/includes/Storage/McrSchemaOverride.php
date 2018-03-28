<?php
namespace MediaWiki\Tests\Storage;

use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * Trait providing schema overrides that allow tests to run against the post-migration
 * MCR database schema.
 */
trait McrSchemaOverride {

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
		$patchDir = $GLOBALS['IP'] . '/maintenance/archives';
		$overrides = [
			'scripts' => [],
			'drop' => [],
			'create' => [],
			'alter' => [],
		];

		if ( !$this->hasMcrTables( $db ) ) {
			$overrides['create'] = [ 'slots', 'content', 'slot_roles', 'content_models', ];
			$overrides['scripts'][] = $patchDir . '/patch-slot_roles.sql';
			$overrides['scripts'][] = $patchDir . '/patch-content_models.sql';
			$overrides['scripts'][] = $patchDir . '/patch-content.sql';
			$overrides['scripts'][] = $patchDir . '/patch-slots.sql';
		}

		if ( !$this->hasPreMcrFields( $db ) ) {
			$overrides['alter'][] = 'revision';
			$overrides['scripts'][] = __DIR__ . '/drop-pre-mcr-fields.sql';
		}

		return $overrides;
	}

}
