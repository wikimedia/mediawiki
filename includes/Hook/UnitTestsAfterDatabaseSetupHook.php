<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface UnitTestsAfterDatabaseSetupHook {
	/**
	 * Called right after MediaWiki's test
	 * infrastructure has finished creating/duplicating core tables for unit tests.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $database Database in question
	 * @param ?mixed $prefix Table prefix to be used in unit tests
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onUnitTestsAfterDatabaseSetup( $database, $prefix );
}
