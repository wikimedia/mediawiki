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
 * Debugging: PHP
 */

// Enable showing of errors
error_reporting( -1 );
ini_set( 'display_errors', 1 );

/**
 * Debugging: MediaWiki
 */
global $wgDevelopmentWarnings, $wgShowDBErrorBacktrace, $wgShowExceptionDetails,
	$wgShowSQLErrors, $wgDebugRawPage,
	$wgDebugComments, $wgDebugDumpSql, $wgDebugTimestamps,
	$wgCommandLineMode, $wgDebugLogFile, $wgDBerrorLog, $wgDebugLogGroups;

// Use of wfWarn() should cause tests to fail
$wgDevelopmentWarnings = true;

// Enable showing of errors
$wgShowDBErrorBacktrace = true;
$wgShowExceptionDetails = true;
$wgShowSQLErrors = true;
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
	$wgDebugLogGroups['exception'] = "$logDir/mw-exception.log";
	$wgDebugLogGroups['error'] = "$logDir/mw-error.log";
}
unset( $logDir );
