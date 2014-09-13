<?php
/**
 * This does the initial set up for a web request.
 * It does some security checks, starts the profiler and loads the
 * configuration, and optionally loads Setup.php depending on whether
 * MW_NO_SETUP is defined.
 *
 * Setup.php (if loaded) then sets up GlobalFunctions, the AutoLoader,
 * and the configuration globals (though not $wgTitle).
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

# Protect against register_globals
# This must be done before any globals are set by the code
if ( ini_get( 'register_globals' ) ) {
	if ( isset( $_REQUEST['GLOBALS'] ) || isset( $_FILES['GLOBALS'] ) ) {
		die( '<a href="http://www.hardened-php.net/globals-problem">$GLOBALS overwrite vulnerability</a>' );
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
		if ( in_array( $name, $verboten ) ) {
			header( "HTTP/1.1 500 Internal Server Error" );
			echo "register_globals security paranoia: trying to overwrite superglobals, aborting.";
			die( -1 );
		}
		unset( $GLOBALS[$name] );
	}
}

# bug 15461: Make IE8 turn off content sniffing. Everybody else should ignore this
# We're adding it here so that it's *always* set, even for alternate entry
# points and when $wgOut gets disabled or overridden.
header( 'X-Content-Type-Options: nosniff' );

$wgRequestTime = microtime( true );
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
# __DIR__ breaks symlinked includes, but realpath() returns false
# if we don't have permissions on parent directories.
$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	if ( realpath( '.' ) ) {
		$IP = realpath( '.' );
	} else {
		$IP = dirname( __DIR__ );
	}
}

# Start the autoloader, so that extensions can derive classes from core files
require_once "$IP/includes/AutoLoader.php";

# Load the profiler
require_once "$IP/includes/profiler/Profiler.php";

# Load up some global defines.
require_once "$IP/includes/Defines.php";

# Start the profiler
$wgProfiler = array();
if ( file_exists( "$IP/StartProfiler.php" ) ) {
	require "$IP/StartProfiler.php";
}

wfProfileIn( 'WebStart.php-conf' );

# Load default settings
require_once "$IP/includes/DefaultSettings.php";

# Load composer's autoloader if present
if ( is_readable( "$IP/vendor/autoload.php" ) ) {
	require_once "$IP/vendor/autoload.php";
}

if ( defined( 'MW_CONFIG_CALLBACK' ) ) {
	# Use a callback function to configure MediaWiki
	call_user_func( MW_CONFIG_CALLBACK );
} else {
	if ( !defined( 'MW_CONFIG_FILE' ) ) {
		define( 'MW_CONFIG_FILE', "$IP/LocalSettings.php" );
	}

	# LocalSettings.php is the per site customization file. If it does not exist
	# the wiki installer needs to be launched or the generated file uploaded to
	# the root wiki directory. Give a hint, if it is not readable by the server.
	if ( !is_readable( MW_CONFIG_FILE ) ) {
		require_once "$IP/includes/templates/NoLocalSettings.php";
		die();
	}

	# Include site settings. $IP may be changed (hopefully before the AutoLoader is invoked)
	require_once MW_CONFIG_FILE;
}

wfProfileOut( 'WebStart.php-conf' );

wfProfileIn( 'WebStart.php-ob_start' );
# Initialise output buffering
# Check that there is no previous output or previously set up buffers, because
# that would cause us to potentially mix gzip and non-gzip output, creating a
# big mess.
if ( !defined( 'MW_NO_OUTPUT_BUFFER' ) && ob_get_level() == 0 ) {
	require_once "$IP/includes/OutputHandler.php";
	ob_start( 'wfOutputHandler' );
}
wfProfileOut( 'WebStart.php-ob_start' );

if ( !defined( 'MW_NO_SETUP' ) ) {
	require_once "$IP/includes/Setup.php";
}
