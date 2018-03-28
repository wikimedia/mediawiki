<?php
namespace MediaWiki\Tests\Storage;

use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * Trait providing schema overrides that allow tests to run against the pre-MCR database schema.
 */
trait PreMcrSchemaOverride {

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

		if ( $this->hasMcrTables( $db ) ) {
			$overrides['drop'] = [ 'slots', 'content', 'slot_roles', 'content_models', ];
			$overrides['scripts'][] = __DIR__ . '/drop-mcr-tables.sql';
		}

		if ( !$this->hasPreMcrFields( $db ) ) {
			$overrides['alter'][] = 'revision';
			$overrides['scripts'][] = __DIR__ . '/create-pre-mcr-fields.sql';
		}

		return $overrides;
	}

}
