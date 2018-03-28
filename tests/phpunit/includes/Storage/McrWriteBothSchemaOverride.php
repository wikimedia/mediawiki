<?php
namespace MediaWiki\Tests\Storage;

use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * Trait providing schema overrides that allow tests to run against the intermediate MCR database
 * schema for use during schema migration.
 */
trait McrWriteBothSchemaOverride {

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
		$patchDir = $GLOBALS['IP'] . '/maintenance/archives';
		$overrides = [
			'scripts' => [],
			'drop' => [],
			'create' => [],
			'alter' => [],
		];

		if ( !$this->hasMcrTables( $db ) ) {
			$overrides['create'] = [ 'slots', 'content', 'slot_roles', 'content_models', ];
			$overrides['scripts'][] = $patchDir . '/slots.sql';
		}

		if ( !$this->hasPreMcrFields( $db ) ) {
			$overrides['alter'][] = 'revision';
			$overrides['scripts'][] = __DIR__ . '/create-pre-mcr-fields.sql';
		}

		return $overrides;
	}

}
