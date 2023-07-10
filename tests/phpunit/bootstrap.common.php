<?php

if ( ( PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg' ) || defined( 'MEDIAWIKI' ) ) {
	exit( 'This file is only meant to be executed indirectly by PHPUnit\'s bootstrap process' );
}

fwrite( STDERR, 'Using PHP ' . PHP_VERSION . "\n" );

define( 'MEDIAWIKI', true );
define( 'MW_ENTRY_POINT', 'cli' );
// Set a flag which can be used to detect when other scripts have been entered
// through this entry point or not.
define( 'MW_PHPUNIT_TEST', true );

if ( getenv( 'PHPUNIT_WIKI' ) ) {
	$wikiName = getenv( 'PHPUNIT_WIKI' );
	$bits = explode( '-', $wikiName, 2 );
	define( 'MW_DB', $bits[0] );
	define( 'MW_PREFIX', $bits[1] ?? '' );
	define( 'MW_WIKI_NAME', $wikiName );
}

require_once __DIR__ . '/../common/TestSetup.php';
require_once __DIR__ . "/../../includes/BootstrapHelperFunctions.php";

// phpcs:ignore MediaWiki.NamingConventions.ValidGlobalName.allowedPrefix
global $IP;
$IP = wfDetectInstallPath();
// Note: unit tests don't use a settings file but some code still assumes that one exists
wfDetectLocalSettingsFile( $IP );

TestSetup::snapshotGlobals();

$GLOBALS['wgCommandLineMode'] = true;

// Start an output buffer to avoid headers being sent by constructors,
// data providers, etc. (T206476)
ob_start();
