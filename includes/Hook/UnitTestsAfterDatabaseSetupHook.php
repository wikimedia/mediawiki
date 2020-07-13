<?php

namespace MediaWiki\Hook;

use Wikimedia\Rdbms\IMaintainableDatabase;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface UnitTestsAfterDatabaseSetupHook {
	/**
	 * This hook is called right after MediaWiki's test
	 * infrastructure has finished creating/duplicating core tables for unit tests.
	 *
	 * @since 1.35
	 *
	 * @param IMaintainableDatabase $database Database in question
	 * @param string $prefix Table prefix to be used in unit tests
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUnitTestsAfterDatabaseSetup( $database, $prefix );
}
