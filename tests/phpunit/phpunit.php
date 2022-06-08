#!/usr/bin/env php
<?php
/**
 * Bootstrapping for MediaWiki PHPUnit tests
 *
 * @file
 */

use PHPUnit\TextUI\Command;

require_once __DIR__ . '/bootstrap.integration.php';
require_once __DIR__ . '/IntegrationBootstrapWrapper.php';

/**
 * Temporary class overriding the IntegrationBootstrapWrapper.
 */
class PHPUnitMaintClass extends IntegrationBootstrapWrapper {

	public function execute() {
		// Start an output buffer to avoid headers being sent by constructors,
		// data providers, etc. (T206476)
		ob_start();
		wfDeprecatedMsg( 'tests/phpunit/phpunit.php is deprecated and will be removed. ' .
			'Please use `composer phpunit` or `vendor/bin/phpunit`',
			'1.39'
		);

		$command = new Command();
		$args = $_SERVER['argv'];
		$knownOpts = getopt( 'c:', [ 'configuration:', 'bootstrap:' ] ) ?: [];
		if ( !isset( $knownOpts['c'] ) && !isset( $knownOpts['configuration'] ) ) {
			// XXX HAX: Use our default file. This is a temporary hack, to be removed when this file goes away
			// or when T227900 is resolved.
			$args[] = '--configuration=' . dirname( __DIR__, 2 ) . '/phpunit.xml.dist';
		}
		$command->run( $args, true );
	}
}

if ( PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg' ) {
	exit( 'This script must be run from the command line' );
}

if ( strval( getenv( 'MW_INSTALL_PATH' ) ) === '' ) {
	putenv( 'MW_INSTALL_PATH=' . realpath( __DIR__ . '/../..' ) );
}

if ( getenv( 'PHPUNIT_WIKI' ) ) {
	$wikiName = getenv( 'PHPUNIT_WIKI' );
	$bits = explode( '-', $wikiName, 2 );
	define( 'MW_DB', $bits[0] );
	define( 'MW_PREFIX', $bits[1] ?? '' );
	define( 'MW_WIKI_NAME', $wikiName );
}

// Define the MediaWiki entrypoint

$IP = getenv( 'MW_INSTALL_PATH' );

global $wgIntegrationBootstrapWrapper;
$wgIntegrationBootstrapWrapper = new PHPUnitMaintClass();
$wgIntegrationBootstrapWrapper->setup();

require_once "$IP/includes/BootstrapHelperFunctions.php";

// ensure MW_INSTALL_PATH is defined
$IP = wfDetectInstallPath();
wfDetectLocalSettingsFile( $IP );

require_once "$IP/includes/Setup.php";
// Deregister handler from MWExceptionHandler::installHandle so that PHPUnit's own handler
// stays in tact. Needs to happen after including Setup.php, which calls MWExceptionHandler::installHandle().
restore_error_handler();

$wgIntegrationBootstrapWrapper->execute();
