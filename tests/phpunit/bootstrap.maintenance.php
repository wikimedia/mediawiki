<?php
/**
 * Bootstrapping for MediaWiki PHPUnit tests when called via the maintenance class tests runner.
 * This file is included by phpunit and is NOT in the global scope.
 *
 * @file
 */

if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
	echo <<<EOF
You are running these tests directly from phpunit. You may not have all globals correctly set.
Running phpunit.php instead is recommended.
EOF;
	require_once __DIR__ . "/phpunit.php";
}

// The TestRunner class will run each test suite and may call
// exit() with an exit status code. As such, we cannot run code "after the last test"
// by adding statements to PHPUnitMaintClass::execute or MediaWikiPHPUnitCommand::run.
// Instead, we work around it by registering a shutdown callback from the bootstrap
// file, which runs before PHPUnit starts.
// @todo Once we use PHPUnit 8 or higher, use the 'AfterLastTestHook' feature.
// https://phpunit.readthedocs.io/en/8.0/extending-phpunit.html#available-hook-interfaces
register_shutdown_function( function () {
	// This will:
	// - clear the temporary job queue.
	// - allow extensions to delete any temporary tables they created.
	// - restore ability to connect to the real database,
	//   (for logging profiling data).
	MediaWikiIntegrationTestCase::teardownTestDB();

	// Log profiling data, e.g. in the database or UDP
	wfLogProfilingData();
} );
