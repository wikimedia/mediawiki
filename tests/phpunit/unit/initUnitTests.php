<?php

/**
 * Allows to include a file that assumes to be included in the file scope.
 * It makes all globals available in the inclusion scope before including the file,
 * then exports all new globals.
 *
 * @param string $fileName the file to include
 */
function wfRequireOnceInGlobalScope( $fileName ) {
	// phpcs:disable MediaWiki.Usage.ForbiddenFunctions.extract
	extract( $GLOBALS, EXTR_REFS | EXTR_SKIP );
	// phpcs:enable

	require_once $fileName;

	foreach ( get_defined_vars() as $varName => $value ) {
		$GLOBALS[$varName] = $value;
	}
}

define( 'MEDIAWIKI', true );
define( 'MW_PHPUNIT_TEST', true );

// Inject test configuration via callback, bypassing LocalSettings.php
define( 'MW_CONFIG_CALLBACK', '\TestSetup::applyInitialConfig' );
// We don't use a settings file here but some code still assumes that one exists
define( 'MW_CONFIG_FILE', 'LocalSettings.php' );

$IP = realpath( __DIR__ . '/../../..' );

// these variables must be defined before setup runs
$GLOBALS['IP'] = $IP;
$GLOBALS['wgCommandLineMode'] = true;

// Bypass Setup.php's scope test
$GLOBALS['wgScopeTest'] = 'MediaWiki Setup.php scope test';
// Avoid PHP Notice in Setup.php
$GLOBALS['self'] = 'Unit tests';

require_once "$IP/tests/common/TestSetup.php";

wfRequireOnceInGlobalScope( "$IP/includes/AutoLoader.php" );
wfRequireOnceInGlobalScope( "$IP/includes/Defines.php" );
wfRequireOnceInGlobalScope( "$IP/includes/DefaultSettings.php" );
wfRequireOnceInGlobalScope( "$IP/includes/Setup.php" );

require_once "$IP/tests/common/TestsAutoLoader.php";

// Remove MWExceptionHandler's handling of PHP errors to allow PHPUnit to replace them
restore_error_handler();

unset( $GLOBALS['wgScopeTest'] );

// Disable all database connections
\MediaWiki\MediaWikiServices::disableStorageBackend();
