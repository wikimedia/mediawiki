#!/usr/bin/env php
<?php
/**
 * Bootstrapping for MediaWiki PHPUnit tests
 *
 * @file
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Registration\ExtensionRegistry;
use PHPUnit\TextUI\Command;

class PHPUnitMaintClass {
	public function setup() {
		// Set a flag which can be used to detect when other scripts have been entered
		// through this entry point or not.
		define( 'MW_PHPUNIT_TEST', true );

		// Send PHP warnings and errors to stderr instead of stdout.
		// This aids in diagnosing problems, while keeping messages
		// out of redirected output.
		if ( ini_get( 'display_errors' ) ) {
			ini_set( 'display_errors', 'stderr' );
		}

		$this->prepareEnvironment();
		require_once __DIR__ . '/../common/TestSetup.php';
		TestSetup::snapshotGlobals();
	}

	public function prepareEnvironment() {
		# Disable the memory limit as it's not needed for tests.
		ini_set( 'memory_limit', -1 );

		# Set max execution time to 0 (no limit). PHP.net says that
		# "When running PHP from the command line the default setting is 0."
		# But sometimes this doesn't seem to be the case.
		ini_set( 'max_execution_time', 0 );

		# Turn off output buffering if it's on
		while ( ob_get_level() > 0 ) {
			ob_end_flush();
		}
	}

	public function finalSetup() {
		global $wgDBadminuser, $wgDBadminpassword;
		global $wgDBuser, $wgDBpassword, $wgDBservers, $wgLBFactoryConf;

		# Prepare environment again, things might have changed in the settings files
		$this->prepareEnvironment();

		if ( isset( $wgDBadminuser ) ) {
			$wgDBuser = $wgDBadminuser;
			$wgDBpassword = $wgDBadminpassword;

			if ( $wgDBservers ) {
				/**
				 * @var array $wgDBservers
				 */
				foreach ( $wgDBservers as $i => $server ) {
					$wgDBservers[$i]['user'] = $wgDBuser;
					$wgDBservers[$i]['password'] = $wgDBpassword;
				}
			}
			if ( isset( $wgLBFactoryConf['serverTemplate'] ) ) {
				$wgLBFactoryConf['serverTemplate']['user'] = $wgDBuser;
				$wgLBFactoryConf['serverTemplate']['password'] = $wgDBpassword;
			}
			$service = MediaWikiServices::getInstance()->peekService( 'DBLoadBalancerFactory' );
			if ( $service ) {
				$service->destroy();
			}
		}

		require_once __DIR__ . '/../common/TestsAutoLoader.php';

		TestSetup::applyInitialConfig();

		ExtensionRegistry::getInstance()->setLoadTestClassesAndNamespaces( true );
	}

	public function execute() {
		// Start an output buffer to avoid headers being sent by constructors,
		// data providers, etc. (T206476)
		ob_start();

		fwrite( STDERR, 'Using PHP ' . PHP_VERSION . "\n" );

		$command = new Command();
		$args = $_SERVER['argv'];
		$knownOpts = getopt( 'c:', [ 'configuration:', 'bootstrap:' ] ) ?: [];
		if ( !isset( $knownOpts['c'] ) && !isset( $knownOpts['configuration'] ) ) {
			// XXX HAX: Use our default file. This is a temporary hack, to be removed when this file goes away
			// or when T227900 is resolved.
			$args[] = '--configuration=' . __DIR__ . '/suite.xml';
		}
		if ( !isset( $knownOpts['bootstrap'] ) ) {
			$args[] = '--bootstrap=' . __DIR__ . '/bootstrap.maintenance.php';
		}
		$command->run( $args, true );
	}
}

$deprecationMsg = <<<EOT
*******************************************************************************
DEPRECATED: The tests/phpunit/phpunit.php entry point has been deprecated. Use
            `composer phpunit` instead.
*******************************************************************************

EOT;

fwrite( STDERR, $deprecationMsg );

if ( defined( 'MEDIAWIKI' ) ) {
	exit( 'Wrong entry point?' );
}

if ( PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg' ) {
	exit( 'This script must be run from the command line' );
}

define( 'MW_ENTRY_POINT', 'cli' );

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
define( 'MEDIAWIKI', true );

define( 'PHPUNIT_LEGACY_ENTRYPOINT', true );

$IP = getenv( 'MW_INSTALL_PATH' );

$wrapper = new PHPUnitMaintClass();
$wrapper->setup();

require_once "$IP/includes/BootstrapHelperFunctions.php";

$IP = wfDetectInstallPath(); // ensure MW_INSTALL_PATH is defined
wfDetectLocalSettingsFile( $IP );

function wfPHPUnitSetup() {
	// phpcs:ignore MediaWiki.NamingConventions.ValidGlobalName.allowedPrefix
	global $wrapper;
	$wrapper->finalSetup();
}

define( 'MW_SETUP_CALLBACK', 'wfPHPUnitSetup' );

require_once "$IP/includes/Setup.php";
// Deregister handler from MWExceptionHandler::installHandle so that PHPUnit's own handler
// stays in tact. Needs to happen after including Setup.php, which calls MWExceptionHandler::installHandle().
restore_error_handler();

// Check that composer dependencies are up-to-date
if ( !getenv( 'MW_SKIP_EXTERNAL_DEPENDENCIES' ) ) {
	$composerLockUpToDate = new CheckComposerLockUpToDate();
	$composerLockUpToDate->loadParamsAndArgs( 'phpunit', [ 'quiet' => true ] );
	$composerLockUpToDate->execute();
}

$wrapper->execute();
