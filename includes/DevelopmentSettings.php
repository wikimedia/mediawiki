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
	$wgDBerrorLog, $wgDebugLogGroups;

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

global $wgRateLimits, $wgEnableJavaScriptTest, $wgRestAPIAdditionalRouteFiles,
	$wgDeferredUpdateStrategy;

// Set almost infinite rate limits. This allows integration tests to run unthrottled
// in CI and for devs locally (T225796), but doesn't turn a large chunk of production
// code completely off during testing (T284804)
foreach ( $wgRateLimits as $right => &$limit ) {
	foreach ( $limit as $group => &$groupLimit ) {
		$groupLimit[0] = PHP_INT_MAX;
	}
}

// Enable Special:JavaScriptTest and allow `npm run qunit` to work
// https://www.mediawiki.org/wiki/Manual:JavaScript_unit_testing
$wgEnableJavaScriptTest = true;

// Enable development/experimental endpoints
$wgRestAPIAdditionalRouteFiles = [ 'includes/Rest/coreDevelopmentRoutes.json' ];

// Greatly raise the limits on short/long term login attempts,
// so that automated tests run in parallel don't error.
$wgPasswordAttemptThrottle = [
	[ 'count' => 1000, 'seconds' => 300 ],
	[ 'count' => 100000, 'seconds' => 60 * 60 * 48 ],
];

// Run deferred updates before sending a response to the client.
// This ensures that in end-to-end tests, a GET request will see the
// effect of all previous POST requests (T230211).
// Caveat: this does not wait for jobs to be executed, and it does
// not wait for database replication to complete.
$wgForceDeferredUpdatesPreSend = true;

/**
 * Experimental changes that may later become the default.
 * (Must reference a Phabricator ticket)
 */

global $wgSQLMode, $wgLocalisationCacheConf,
	$wgCacheDirectory, $wgEnableUploads, $wgCiteBookReferencing;

// Enable MariaDB/MySQL strict mode (T108255)
$wgSQLMode = 'TRADITIONAL';

// Localisation Cache to StaticArray (T218207)
$wgLocalisationCacheConf['store'] = 'array';

// Experimental Book Referencing feature (T236255)
$wgCiteBookReferencing = true;

// The default value is false, but for development it is useful to set this to the system temp
// directory by default (T218207)
$wgCacheDirectory = TempFSFile::getUsableTempDirectory() .
	DIRECTORY_SEPARATOR .
	rawurlencode( WikiMap::getCurrentWikiId() );

// Enable uploads for FileImporter browser tests (T190829)
$wgEnableUploads = true;

// Enable the new wikitext mode for browser testing (T270240)
$wgVisualEditorEnableWikitext = true;
// Currently the default, but repeated here for safety since it would break many source editor tests.
$wgDefaultUserOptions['visualeditor-newwikitext'] = 0;
