<?php
/**
 * This does the initial setup for a web request.
 * It does some security checks, starts the profiler and loads the
 * configuration, and optionally loads Setup.php depending on whether
 * MW_NO_SETUP is defined.
 *
 * @file
 */

# Protect against register_globals
# This must be done before any globals are set by the code
if ( ini_get( 'register_globals' ) ) {
	if ( isset( $_REQUEST['GLOBALS'] ) ) {
		die( '<a href="http://www.hardened-php.net/globals-problem">$GLOBALS overwrite vulnerability</a>');
	}
	$verboten = array(
		'GLOBALS',
		'_SERVER',
		'HTTP_SERVER_VARS',
		'_GET',
		'HTTP_GET_VARS',
		'_POST',
		'HTTP_POST_VARS',
		'_COOKIE',
		'HTTP_COOKIE_VARS',
		'_FILES',
		'HTTP_POST_FILES',
		'_ENV',
		'HTTP_ENV_VARS',
		'_REQUEST',
		'_SESSION',
		'HTTP_SESSION_VARS'
	);
	foreach ( $_REQUEST as $name => $value ) {
		if( in_array( $name, $verboten ) ) {
			header( "HTTP/1.1 500 Internal Server Error" );
			echo "register_globals security paranoia: trying to overwrite superglobals, aborting.";
			die( -1 );
		}
		unset( $GLOBALS[$name] );
	}
}

# bug 15461: Make IE8 turn off content sniffing. Everbody else should ignore this
# We're adding it here so that it's *always* set, even for alternate entry
# points and when $wgOut gets disabled or overridden.
header( 'X-Content-Type-Options: nosniff' );

$wgRequestTime = microtime(true);
# getrusage() does not exist on the Microsoft Windows platforms, catching this
if ( function_exists ( 'getrusage' ) ) {
	$wgRUstart = getrusage();
} else {
	$wgRUstart = array();
}
unset( $IP );

# Valid web server entry point, enable includes.
# Please don't move this line to includes/Defines.php. This line essentially
# defines a valid entry point. If you put it in includes/Defines.php, then
# any script that includes it becomes an entry point, thereby defeating
# its purpose.
define( 'MEDIAWIKI', true );

# Full path to working directory.
# Makes it possible to for example to have effective exclude path in apc.
# Also doesn't break installations using symlinked includes, like
# dirname( __FILE__ ) would do.
$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = realpath( '.' );
}

if ( isset( $_SERVER['MW_COMPILED'] ) ) {
	define( 'MW_COMPILED', 1 );
} else {
	# Get MWInit class
	require_once( "$IP/includes/Init.php" );

	# Start the autoloader, so that extensions can derive classes from core files
	require_once( "$IP/includes/AutoLoader.php" );

	# Load the profiler
	require_once( "$IP/includes/profiler/Profiler.php" );

	# Load up some global defines.
	require_once( "$IP/includes/Defines.php" );
}

# Start the profiler
$wgProfiler = array();
if ( file_exists( "$IP/StartProfiler.php" ) ) {
	require( "$IP/StartProfiler.php" );
}

wfProfileIn( 'WebStart.php-conf' );

# Load default settings
require_once( MWInit::compiledPath( "includes/DefaultSettings.php" ) );

if ( defined( 'MW_CONFIG_CALLBACK' ) ) {
	# Use a callback function to configure MediaWiki
	MWFunction::call( MW_CONFIG_CALLBACK );
} else {
	if ( !defined( 'MW_CONFIG_FILE' ) ) {
		define('MW_CONFIG_FILE', MWInit::interpretedPath( 'LocalSettings.php' ) );
	}

	# LocalSettings.php is the per site customization file. If it does not exist
	# the wiki installer needs to be launched or the generated file uploaded to
	# the root wiki directory
	if( !file_exists( MW_CONFIG_FILE ) ) {
		require_once( "$IP/includes/templates/NoLocalSettings.php" );
		die();
	}

	# Include site settings. $IP may be changed (hopefully before the AutoLoader is invoked)
	require_once( MW_CONFIG_FILE );
}

if ( $wgEnableSelenium ) {
	require_once( MWInit::compiledPath( "includes/SeleniumWebSettings.php" ) );
}

wfProfileOut( 'WebStart.php-conf' );

wfProfileIn( 'WebStart.php-ob_start' );
# Initialise output buffering
# Check that there is no previous output or previously set up buffers, because
# that would cause us to potentially mix gzip and non-gzip output, creating a
# big mess.
if ( !defined( 'MW_NO_OUTPUT_BUFFER' ) && ob_get_level() == 0 ) {
	if ( !defined( 'MW_COMPILED' ) ) {
		require_once( "$IP/includes/OutputHandler.php" );
	}
	ob_start( 'wfOutputHandler' );
}
wfProfileOut( 'WebStart.php-ob_start' );

if ( !defined( 'MW_NO_SETUP' ) ) {
	require_once( MWInit::compiledPath( "includes/Setup.php" ) );
}

