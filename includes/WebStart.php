<?php

# This does the initial setup for a web request. It does some security checks, 
# starts the profiler and loads the configuration, and optionally loads 
# Setup.php depending on whether MW_NO_SETUP is defined.

# Protect against register_globals
# This must be done before any globals are set by the code
if ( ini_get( 'register_globals' ) ) {
	if ( isset( $_REQUEST['GLOBALS'] ) ) {
		die( '<a href="http://www.hardened-php.net/index.76.html">$GLOBALS overwrite vulnerability</a>');
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
@ini_set( 'allow_url_fopen', 0 ); # For security

# Valid web server entry point, enable includes.
# Please don't move this line to includes/Defines.php. This line essentially
# defines a valid entry point. If you put it in includes/Defines.php, then
# any script that includes it becomes an entry point, thereby defeating
# its purpose.
define( 'MEDIAWIKI', true );

# Start profiler
require_once( './StartProfiler.php' );
wfProfileIn( 'WebStart.php-conf' );

# Load up some global defines.
require_once( './includes/Defines.php' );

# LocalSettings.php is the per site customization file. If it does not exit
# the wiki installer need to be launched or the generated file moved from
# ./config/ to ./
if( !file_exists( './LocalSettings.php' ) ) {
	$IP = '.';
	require_once( './includes/DefaultSettings.php' ); # used for printing the version
	require_once( './includes/templates/NoLocalSettings.php' );
	die();
}

# Include this site setttings
require_once( './LocalSettings.php' );
wfProfileOut( 'WebStart.php-conf' );

if ( !defined( 'MW_NO_SETUP' ) ) {
	require_once( './includes/Setup.php' );
}
?>
