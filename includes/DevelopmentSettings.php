<?php
/**
 * Extra settings useful for MediaWiki development.
 *
 * To enable built-in debug and development settings, add the
 * following to your LocalSettings.php file.
 *
 *     require "$IP/includes/DevelopmentSettings.php";
 *
 * Alternatively, if running phpunit.php (or another Maintenance script),
 * you can use the --mwdebug option to automatically load these settings.
 *
 * @file
 */

/**
 * Debugging for PHP
 */

// Enable showing of errors
error_reporting( -1 );
ini_set( 'display_errors', 1 );

/**
 * Debugging for MediaWiki
 */

global $wgDevelopmentWarnings, $wgShowExceptionDetails, $wgShowHostnames,
	$wgDebugRawPage, $wgCommandLineMode, $wgDebugLogFile,
	$wgDBerrorLog, $wgDebugLogGroups, $wgLocalisationCacheConf;

// Use of wfWarn() should cause tests to fail
$wgDevelopmentWarnings = true;

// Enable showing of errors
$wgShowExceptionDetails = true;
$wgShowHostnames = true;
$wgDebugRawPage = true; // T49960

// Enable log files
$logDir = getenv( 'MW_LOG_DIR' );
if ( $logDir ) {
	if ( $wgCommandLineMode ) {
		$wgDebugLogFile = "$logDir/mw-debug-cli.log";
	} else {
		$wgDebugLogFile = "$logDir/mw-debug-www.log";
	}
	$wgDBerrorLog = "$logDir/mw-dberror.log";
	$wgDebugLogGroups['ratelimit'] = "$logDir/mw-ratelimit.log";
	$wgDebugLogGroups['error'] = "$logDir/mw-error.log";
	$wgDebugLogGroups['exception'] = "$logDir/mw-error.log";
}
unset( $logDir );

/**
 * Make testing possible (or easier)
 */

global $wgRateLimits;

// Disable rate-limiting to allow integration tests to run unthrottled
// in CI and for devs locally (T225796)
$wgRateLimits = [];

// Enable Special:JavaScriptTest and allow `npm run qunit` to work
// https://www.mediawiki.org/wiki/Manual:JavaScript_unit_testing
$wgEnableJavaScriptTest = true;

// Enable development/experimental endpoints
$wgRestAPIAdditionalRouteFiles = [ 'includes/Rest/coreDevelopmentRoutes.json' ];

/**
 * Experimental changes that may later become the default.
 * (Must reference a Phabricator ticket)
 */

global $wgSQLMode, $wgLegacyJavaScriptGlobals;

// Enable MariaDB/MySQL strict mode (T108255)
$wgSQLMode = 'TRADITIONAL';

// Disable legacy javascript globals in CI and for devs (T72470)
$wgLegacyJavaScriptGlobals = false;

// Localisation Cache to StaticArray (T218207)
$wgLocalisationCacheConf['store'] = 'array';

// Experimental Book Referencing feature (T236255)
global $wgCiteBookReferencing;
$wgCiteBookReferencing = true;

// The default value is false, but for development it is useful to set this to the system temp
// directory by default (T218207)
$wgCacheDirectory = TempFSFile::getUsableTempDirectory() .
	DIRECTORY_SEPARATOR .
	rawurlencode( WikiMap::getCurrentWikiId() );
