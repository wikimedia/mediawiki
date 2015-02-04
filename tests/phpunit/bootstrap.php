<?php
/**
 * Bootstrapping for MediaWiki PHPUnit tests
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

class MediaWikiPHPUnitBootstrap {
	public function __destruct() {
		// Return to real wiki db, so profiling data is preserved
		MediaWikiTestCase::teardownTestDB();

		// Log profiling data, e.g. in the database or UDP
		wfLogProfilingData();
	}

}

// This will be destructed after all tests have been run
$mediawikiPHPUnitBootstrap = new MediaWikiPHPUnitBootstrap();
