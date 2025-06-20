<?php

/**
 * Entry point for running maintenance scripts.
 *
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\MaintenanceRunner;
use MediaWiki\Settings\SettingsBuilder;

// No AutoLoader yet
require_once __DIR__ . '/Maintenance.php';
require_once __DIR__ . '/includes/MaintenanceRunner.php';
require_once __DIR__ . '/includes/MaintenanceParameters.php';

// Not in file scope, abort!
if ( !MaintenanceRunner::shouldExecute() ) {
	return;
}

// Define the MediaWiki entrypoint
define( 'MEDIAWIKI', true );

$IP = wfDetectInstallPath();
require_once "$IP/includes/AutoLoader.php";

// phpcs:disable: MediaWiki.NamingConventions.ValidGlobalName.allowedPrefix
$runner = new MaintenanceRunner();
$runner->initFromWrapper( $argv );

$runner->defineSettings();

// Custom setup for Maintenance entry point
if ( !defined( 'MW_FINAL_SETUP_CALLBACK' ) ) {

	/**
	 * Define a function, since we can't put a closure or object
	 * reference into MW_FINAL_SETUP_CALLBACK.
	 */
	function wfMaintenanceRunSetup( SettingsBuilder $settingsBuilder ) {
		global $runner;
		$runner->setup( $settingsBuilder );
	}

	define( 'MW_FINAL_SETUP_CALLBACK', 'wfMaintenanceRunSetup' );
}

// Initialize MediaWiki (load settings, extensions, etc).
require_once "$IP/includes/Setup.php";

$success = $runner->run();

// Exit with an error status if execute() returned false
if ( !$success ) {
	exit( 1 );
}
