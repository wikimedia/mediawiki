#!/usr/bin/env php
<?php
/**
 * Bootstrapping for MediaWiki PHPUnit tests
 *
 * @file
 */

use MediaWiki\MediaWikiServices;

class PHPUnitMaintClass {
	public function setup() {
		global $wgCommandLineMode;

		// Set a flag which can be used to detect when other scripts have been entered
		// through this entry point or not.
		define( 'MW_PHPUNIT_TEST', true );

		# Abort if called from a web server
		# wfIsCLI() is not available yet
		if ( PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg' ) {
			$this->fatalError( 'This script must be run from the command line' );
		}

		# Make sure we can handle script parameters
		if ( !ini_get( 'register_argc_argv' ) ) {
			$this->fatalError( 'Cannot get command line arguments, register_argc_argv is set to false' );
		}

		// Send PHP warnings and errors to stderr instead of stdout.
		// This aids in diagnosing problems, while keeping messages
		// out of redirected output.
		if ( ini_get( 'display_errors' ) ) {
			ini_set( 'display_errors', 'stderr' );
		}

		# Disable the memory limit as it's not needed for tests.
		# Note we need to set it again later in cache LocalSettings changed it
		ini_set( 'memory_limit', -1 );

		# Set max execution time to 0 (no limit). PHP.net says that
		# "When running PHP from the command line the default setting is 0."
		# But sometimes this doesn't seem to be the case.
		ini_set( 'max_execution_time', 0 );

		$wgCommandLineMode = true;

		# Turn off output buffering if it's on
		while ( ob_get_level() > 0 ) {
			ob_end_flush();
		}

		require_once __DIR__ . '/../common/TestSetup.php';
		TestSetup::snapshotGlobals();
	}

	/**
	 * Output a message and terminate the current script.
	 *
	 * @param string $msg Error message
	 * @param int $exitCode PHP exit status. Should be in range 1-254.
	 * @return never
	 */
	private function fatalError( $msg, $exitCode = 1 ) {
		echo $msg;
		exit( $exitCode );
	}

	public function finalSetup() {
		global $wgCommandLineMode, $wgShowExceptionDetails, $wgShowHostnames;
		global $wgDBadminuser, $wgDBadminpassword;
		global $wgDBuser, $wgDBpassword, $wgDBservers, $wgLBFactoryConf;

		# Turn off output buffering again, it might have been turned on in the settings files
		if ( ob_get_level() ) {
			ob_end_flush();
		}
		# Same with these
		$wgCommandLineMode = true;

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

		$wgShowExceptionDetails = true;
		$wgShowHostnames = true;

		@set_time_limit( 0 );

		ini_set( 'memory_limit', -1 );

		require_once __DIR__ . '/../common/TestsAutoLoader.php';

		TestSetup::applyInitialConfig();

		ExtensionRegistry::getInstance()->setLoadTestClassesAndNamespaces( true );
	}

	public function execute() {
		// Deregister handler from MWExceptionHandler::installHandle so that PHPUnit's own handler
		// stays in tact.
		// Has to in execute() instead of finalSetup(), because finalSetup() runs before
		// Setup.php is included, which calls MWExceptionHandler::installHandle().
		restore_error_handler();

		if ( !class_exists( PHPUnit\Framework\TestCase::class ) ) {
			echo "PHPUnit not found. Please install it and other dev dependencies by
		running `composer install` in MediaWiki root directory.\n";
			exit( 1 );
		}

		// Start an output buffer to avoid headers being sent by constructors,
		// data providers, etc. (T206476)
		ob_start();

		fwrite( STDERR, 'Using PHP ' . PHP_VERSION . "\n" );

		MediaWikiCliOptions::initialize();

		$command = new MediaWikiPHPUnitCommand();
		$command->run( $_SERVER['argv'], true );
	}

	/**
	 * Generic setup for most installs. Returns the location of LocalSettings
	 * @return string
	 */
	public function loadSettings() {
		global $wgCommandLineMode, $IP;

		$settingsFile = wfDetectLocalSettingsFile( $IP );
		if ( getenv( 'PHPUNIT_WIKI' ) ) {
			$wikiName = getenv( 'PHPUNIT_WIKI' );
			$bits = explode( '-', $wikiName, 2 );
			define( 'MW_DB', $bits[0] );
			define( 'MW_PREFIX', $bits[1] ?? '' );
			define( 'MW_WIKI_NAME', $wikiName );
		}

		if ( !is_readable( $settingsFile ) ) {
			$this->fatalError( "The file $settingsFile must exist and be readable.\n" );
		}
		$wgCommandLineMode = true;

		return $settingsFile;
	}
}

if ( defined( 'MEDIAWIKI' ) ) {
	exit( 'Wrong entry point?' );
}

define( 'MW_ENTRY_POINT', 'cli' );

if ( strval( getenv( 'MW_INSTALL_PATH' ) ) === '' ) {
	putenv( 'MW_INSTALL_PATH=' . realpath( __DIR__ . '/../..' ) );
}

// Define the MediaWiki entrypoint
define( 'MEDIAWIKI', true );

$IP = getenv( 'MW_INSTALL_PATH' );
require_once "$IP/includes/BootstrapHelperFunctions.php";

$wrapper = new PHPUnitMaintClass();
$wrapper->setup();
$wrapper->loadSettings();

function wfPHPUnitSetup() {
	// phpcs:ignore MediaWiki.NamingConventions.ValidGlobalName.allowedPrefix
	global $wrapper;
	$wrapper->finalSetup();
}

define( 'MW_SETUP_CALLBACK', 'wfPHPUnitSetup' );

require_once "$IP/includes/Setup.php";

if ( in_array( '--help', $argv, true ) ) {
	$command = new MediaWikiPHPUnitCommand();
	$command->publicShowHelp();
	die( 1 );
}
$wrapper->execute();
