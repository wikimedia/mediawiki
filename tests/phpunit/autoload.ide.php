<?php

/**
 * This file is PHPUnit autoload file for PhpStorm IDE and other JetBrains IDEs.
 *
 * This file should be set in `Languages and frameworks > PHP > PhpUnit`
 * select `Use Composer autoloader` and set `Path to script` to `<path to this file>`.
 * After that, tests can be run in PhpStorm using Right-click > Run or `Ctrl + Shift + F10`.
 * Also, tests can be run with debugger very easily.
 *
 * This file basically does almost the same thing as `tests/phpunit/phpunit.php`, except that all
 * code is going to be executed inside some function, so some hacks needed to make old code to be
 * executed as if it was executed on top of the execution stack.
 *
 * PS: Mostly it is copy-paste from `phpunit.php` and `doMaintenance.php`.
 *
 * @file
 */

// Set a flag which can be used to detect when other scripts have been entered
// through this entry point or not.
use MediaWiki\MediaWikiServices;

global $argv;
$argv[1] = '--wiki';
$argv[2] = getenv( 'WIKI_NAME' ) ?: 'wiki';

require_once __DIR__ . "/phpunit.php";

// Get an object to start us off
/** @var Maintenance $maintenance */
$maintenance = new PHPUnitMaintClass();

// Basic sanity checks and such
$maintenance->setup();

// We used to call this variable $self, but it was moved
// to $maintenance->mSelf. Keep that here for b/c
$self = $maintenance->getName();
global $IP;
# Get profiler configuraton
$wgProfiler = [];
if ( file_exists( "$IP/StartProfiler.php" ) ) {
	require "$IP/StartProfiler.php";
}
# Start the autoloader, so that extensions can derive classes from core files
require_once "$IP/includes/AutoLoader.php";

$requireOnceGlobalsScope = function ( $file ) use ( $self ) {
	foreach ( array_keys( $GLOBALS ) as $varName ) {
		eval( sprintf( 'global $%s;', $varName ) );
	}

	require_once $file;

	unset( $file );
	$definedVars = get_defined_vars();
	foreach ( $definedVars as $varName => $value ) {
		eval( sprintf( 'global $%s; $%s = $value;', $varName, $varName ) );
	}
};

// Some other requires
$requireOnceGlobalsScope( "$IP/includes/Defines.php" );
$requireOnceGlobalsScope( "$IP/includes/DefaultSettings.php" );
$requireOnceGlobalsScope( "$IP/includes/GlobalFunctions.php" );

foreach ( array_keys( $GLOBALS ) as $varName ) {
	eval( sprintf( 'global $%s;', $varName ) );
}

# Load composer's autoloader if present
if ( is_readable( "$IP/vendor/autoload.php" ) ) {
	require_once "$IP/vendor/autoload.php";
}

if ( defined( 'MW_CONFIG_CALLBACK' ) ) {
	# Use a callback function to configure MediaWiki
	call_user_func( MW_CONFIG_CALLBACK );
} else {
	// Require the configuration (probably LocalSettings.php)
	require $maintenance->loadSettings();
}

if ( $maintenance->getDbType() === Maintenance::DB_NONE ) {
	if (
		$wgLocalisationCacheConf['storeClass'] === false
		&& (
			$wgLocalisationCacheConf['store'] == 'db'
			|| ( $wgLocalisationCacheConf['store'] == 'detect' && !$wgCacheDirectory )
		)
	) {
		$wgLocalisationCacheConf['storeClass'] = LCStoreNull::class;
	}
}

$maintenance->finalSetup();
// Some last includes
$requireOnceGlobalsScope( "$IP/includes/Setup.php" );

// Initialize main config instance
$maintenance->setConfig( MediaWikiServices::getInstance()->getMainConfig() );

// Sanity-check required extensions are installed
$maintenance->checkRequiredExtensions();

// A good time when no DBs have writes pending is around lag checks.
// This avoids having long running scripts just OOM and lose all the updates.
$maintenance->setAgentAndTriggers();
