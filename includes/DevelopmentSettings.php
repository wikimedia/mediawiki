<?php
/**
 * Extra settings useful for MediaWiki development.
 *
 * To enable built-in debug and development settings, add the
 * following to your LocalSettings.php file.
 *
 *     require "$IP/includes/DevelopmentSettings.php";
 *
 * @file
 */

/**
 * Ad-hoc debugging
 *
 * To keep your Git copy clean and easier to work with, it is recommended
 * to copy this to your LocalSettings.php and enable them as-needed.
 * These are not enabled by default as they make the wiki considerably
 * slower and/or significantly alter how things work or look.
 *
 * See https://www.mediawiki.org/wiki/How_to_debug
 */

// $wgDebugDumpSql = true;
// $wgDebugRawPage = true;
// $wgDebugToolbar = true;

/**
 * Debugging for PHP
 */

// Enable logging of all errors
error_reporting( -1 );

// Enable showing of errors, but avoid breaking non-HTML responses
if ( MW_ENTRY_POINT === 'index' ) {
	ini_set( 'display_errors', '1' );
}

/**
 * Debugging for MediaWiki
 */

global $wgDevelopmentWarnings, $wgShowExceptionDetails, $wgShowHostnames,
	$wgDebugLogFile,
	$wgDBerrorLog, $wgDebugLogGroups;

// Use of wfWarn() should cause tests to fail
$wgDevelopmentWarnings = true;

// Enable showing of errors
$wgShowExceptionDetails = true;
$wgShowHostnames = true;

// Enable log files
$logDir = getenv( 'MW_LOG_DIR' );
if ( $logDir ) {
	if ( !file_exists( $logDir ) ) {
		mkdir( $logDir );
	}
	if ( MW_ENTRY_POINT === 'cli' ) {
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
	$wgPasswordAttemptThrottle, $wgForceDeferredUpdatesPreSend,
	$wgParsoidSettings, $wgMaxArticleSize;

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
$wgRestAPIAdditionalRouteFiles[] = 'includes/Rest/coreDevelopmentRoutes.json';

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

// Set size limits for parsing small enough so we can test them,
// but not so small that they interfere with other tests.
$wgMaxArticleSize = 20; // in Kilobyte
$wgParsoidSettings['wt2htmlLimits']['wikitextSize'] = 20 * 1024; // $wgMaxArticleSize, in byte
$wgParsoidSettings['html2wtLimits']['htmlSize'] = 100 * 1024; // in characters!

// Enable Vue dev mode by default, so that Vue devtools are functional.
$wgVueDevelopmentMode = true;

// Disable rate limiting of temp account creation and temp account name
// acquisition, to facilitate local development and testing
$wgTempAccountCreationThrottle = [];
$wgTempAccountNameAcquisitionThrottle = [];

/**
 * Experimental changes that may later become the default.
 * (Must reference a Phabricator ticket)
 */

global $wgSQLMode, $wgDBStrictWarnings, $wgLocalisationCacheConf, $wgCiteBookReferencing,
	$wgCacheDirectory, $wgEnableUploads, $wgUsePigLatinVariant,
	$wgVisualEditorEnableWikitext, $wgDefaultUserOptions, $wgAutoCreateTempUser;

// Enable MariaDB/MySQL strict mode (T108255)
$wgSQLMode = 'STRICT_ALL_TABLES,ONLY_FULL_GROUP_BY';
$wgDBStrictWarnings = true;

// Localisation Cache to StaticArray (T218207)
$wgLocalisationCacheConf['store'] = 'array';

// Experimental Book Referencing feature (T236255)
$wgCiteBookReferencing = true;

// The default value is false, but for development it is useful to set this to the system temp
// directory by default (T218207)
$wgCacheDirectory = TempFSFile::getUsableTempDirectory() .
	DIRECTORY_SEPARATOR .
	rawurlencode( MediaWiki\WikiMap\WikiMap::getCurrentWikiId() );

// Enable uploads for FileImporter browser tests (T190829)
$wgEnableUploads = true;

// Enable en-x-piglatin variant conversion for testing
$wgUsePigLatinVariant = true;
// Enable x-xss language code for testing correct message escaping
$wgUseXssLanguage = true;

// Enable the new wikitext mode for browser testing (T270240)
$wgVisualEditorEnableWikitext = true;
// Currently the default, but repeated here for safety since it would break many source editor tests.
$wgDefaultUserOptions['visualeditor-newwikitext'] = 0;

// Enable creation of temp user accounts on edit (T355880, T359043)
$wgAutoCreateTempUser['enabled'] = true;
