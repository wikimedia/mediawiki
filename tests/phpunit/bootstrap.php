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
	require_once ( __DIR__ . "/phpunit.php" );
}

// Output a notice when running with older versions of PHPUnit
if ( version_compare( PHPUnit_Runner_Version::id(), "3.6.7", "<" ) ) {
  echo <<<EOF
********************************************************************************

These tests run best with version PHPUnit 3.6.7 or better. Earlier versions may
show failures because earlier versions of PHPUnit do not properly implement
dependencies.

********************************************************************************

EOF;
}

/** @todo Check if this is really needed */
MessageCache::destroyInstance();
