<?php
/**
 * This does the initial set up for a web request.
 * It does some security checks, starts the profiler and loads the
 * configuration, and optionally loads Setup.php depending on whether
 * MW_NO_SETUP is defined.
 *
 * Setup.php (if loaded) then sets up GlobalFunctions, the AutoLoader,
 * and the configuration globals.
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

if ( ini_get( 'mbstring.func_overload' ) ) {
	die( 'MediaWiki does not support installations where mbstring.func_overload is non-zero.' );
}

# bug 15461: Make IE8 turn off content sniffing. Everybody else should ignore this
# We're adding it here so that it's *always* set, even for alternate entry
# points and when $wgOut gets disabled or overridden.
header( 'X-Content-Type-Options: nosniff' );

/**
 * @var float Request start time as fractional seconds since epoch
 * @deprecated since 1.25; use $_SERVER['REQUEST_TIME_FLOAT'] or
 *   WebRequest::getElapsedTime() instead.
 */
$wgRequestTime = $_SERVER['REQUEST_TIME_FLOAT'];

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
	$IP = realpath( '.' ) ?: dirname( __DIR__ );
}

# Grab profiling functions
require_once "$IP/includes/profiler/ProfilerFunctions.php";

# Start the autoloader, so that extensions can derive classes from core files
require_once "$IP/includes/AutoLoader.php";

# Load up some global defines.
require_once "$IP/includes/Defines.php";

# Start the profiler
$wgProfiler = [];
if ( file_exists( "$IP/StartProfiler.php" ) ) {
	require "$IP/StartProfiler.php";
}

# Load default settings
require_once "$IP/includes/DefaultSettings.php";

# Load global functions
require_once "$IP/includes/GlobalFunctions.php";

# Load composer's autoloader if present
if ( is_readable( "$IP/vendor/autoload.php" ) ) {
	require_once "$IP/vendor/autoload.php";
}

# Assert that composer dependencies were successfully loaded
# Purposely no leading \ due to it breaking HHVM RepoAuthorative mode
# PHP works fine with both versions
# See https://github.com/facebook/hhvm/issues/5833
if ( !interface_exists( 'Psr\Log\LoggerInterface' ) ) {
	$message = (
		'MediaWiki requires the <a href="https://github.com/php-fig/log">PSR-3 logging ' .
		"library</a> to be present. This library is not embedded directly in MediaWiki's " .
		"git repository and must be installed separately by the end user.\n\n" .
		'Please see <a href="https://www.mediawiki.org/wiki/Download_from_Git' .
		'#Fetch_external_libraries">mediawiki.org</a> for help on installing ' .
		'the required components.'
	);
	echo $message;
	trigger_error( $message, E_USER_ERROR );
	die( 1 );
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
		require_once "$IP/includes/NoLocalSettings.php";
		die();
	}

	# Include site settings. $IP may be changed (hopefully before the AutoLoader is invoked)
	require_once MW_CONFIG_FILE;
}

# Initialise output buffering
# Check that there is no previous output or previously set up buffers, because
# that would cause us to potentially mix gzip and non-gzip output, creating a
# big mess.
if ( ob_get_level() == 0 ) {
	require_once "$IP/includes/OutputHandler.php";
	ob_start( 'wfOutputHandler' );
}

if ( !defined( 'MW_NO_SETUP' ) ) {
	require_once "$IP/includes/Setup.php";
}

# Multiple DBs or commits might be used; keep the request as transactional as possible
if ( isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	ignore_user_abort( true );
}

if ( !defined( 'MW_API' ) &&
	RequestContext::getMain()->getRequest()->getHeader( 'Promise-Non-Write-API-Action' )
) {
	header( 'Cache-Control: no-cache' );
	header( 'Content-Type: text/html; charset=utf-8' );
	HttpStatus::header( 400 );
	$error = wfMessage( 'nonwrite-api-promise-error' )->escaped();
	$content = <<<EOT
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8" /></head>
<body>
$error
</body>
</html>

EOT;
	header( 'Content-Length: ' . strlen( $content ) );
	echo $content;
	die();
}
