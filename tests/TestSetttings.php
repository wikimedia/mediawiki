<?php

/**
 * Debugging: PHP
 */

// Enable showing of errors
error_reporting( -1 );
ini_set( 'display_errors', 1 );

/**
 * Debugging: MediaWiki
 */

// Use of wfWarn() should cause tests to fail
$wgDevelopmentWarnings = true;

// Enable showing of errors
$wgShowDBErrorBacktrace = true;
$wgShowExceptionDetails = true;
$wgShowSQLErrors = true;
$wgDebugRawPage = true; // T49960

// Prefix log lines with relative timestamp and memory usage
$wgDebugTimestamps = true;

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
