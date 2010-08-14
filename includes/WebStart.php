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
			header( "HTTP/1.x 500 Internal Server Error" );
			echo "register_globals security paranoia: trying to overwrite superglobals, aborting.";
			die( -1 );
		}
		unset( $GLOBALS[$name] );
	}
}

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


# Start profiler
if( file_exists("$IP/StartProfiler.php") ) {
	require_once( "$IP/StartProfiler.php" );
} else {
	require_once( "$IP/includes/ProfilerStub.php" );
}
wfProfileIn( 'WebStart.php-conf' );

# Load up some global defines.
require_once( "$IP/includes/Defines.php" );

# Check for PHP 5
if ( !function_exists( 'version_compare' ) 
	|| version_compare( phpversion(), '5.0.0' ) < 0
) {
	define( 'MW_PHP4', '1' );
	require( "$IP/includes/DefaultSettings.php" );
	require( "$IP/includes/templates/PHP4.php" );
	exit;
}

# Test for PHP bug which breaks PHP 5.0.x on 64-bit...
# As of 1.8 this breaks lots of common operations instead
# of just some rare ones like export.
$borked = str_replace( 'a', 'b', array( -1 => -1 ) );
if( !isset( $borked[-1] ) ) {
	echo "PHP 5.0.x is buggy on your 64-bit system; you must upgrade to PHP 5.1.x\n" .
	     "or higher. ABORTING. (http://bugs.php.net/bug.php?id=34879 for details)\n";
	exit;
}

# Start the autoloader, so that extensions can derive classes from core files
require_once( "$IP/includes/AutoLoader.php" );

if ( defined( 'MW_CONFIG_CALLBACK' ) ) {
	# Use a callback function to configure MediaWiki
	require_once( "$IP/includes/DefaultSettings.php" );

	$callback = MW_CONFIG_CALLBACK;
	if ( strpos( $callback, '::' ) !== false ) {
		$callback = explode( '::', $callback, 2);
	}
	call_user_func( $callback );
} else {
	# LocalSettings.php is the per site customization file. If it does not exit
	# the wiki installer need to be launched or the generated file moved from
	# ./config/ to ./
	if( !file_exists( "$IP/LocalSettings.php" ) ) {
		require_once( "$IP/includes/DefaultSettings.php" ); # used for printing the version
		require_once( "$IP/includes/templates/NoLocalSettings.php" );
		die();
	}

	# Include site settings. $IP may be changed (hopefully before the AutoLoader is invoked)
	require_once( "$IP/LocalSettings.php" );
}
wfProfileOut( 'WebStart.php-conf' );

wfProfileIn( 'WebStart.php-ob_start' );
# Initialise output buffering
if ( ob_get_level() ) {
	# Someone's been mixing configuration data with code!
	# How annoying.
} elseif ( !defined( 'MW_NO_OUTPUT_BUFFER' ) ) {
	require_once( "$IP/includes/OutputHandler.php" );
	ob_start( 'wfOutputHandler' );
}
wfProfileOut( 'WebStart.php-ob_start' );

if ( !defined( 'MW_NO_SETUP' ) ) {
	require_once( "$IP/includes/Setup.php" );
}
