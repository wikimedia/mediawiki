<?php
/**
 * This does the initial set up for a web request.
 *
 * It does some security checks, loads autoloaders, constants, and
 * global functions, starts the profiler, loads the configuration,
 * and loads Setup.php, which loads extensions using the extension
 * registration system and initializes the application's global state.
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

# T17461: Make IE8 turn off content sniffing. Everybody else should ignore this
# We're adding it here so that it's *always* set, even for alternate entry
# points and when $wgOut gets disabled or overridden.
header( 'X-Content-Type-Options: nosniff' );

/**
 * @var float Request start time as fractional seconds since epoch
 * @deprecated since 1.25; use $_SERVER['REQUEST_TIME_FLOAT'] or
 *   WebRequest::getElapsedTime() instead.
 */
$wgRequestTime = $_SERVER['REQUEST_TIME_FLOAT'];

# Valid web server entry point, enable includes.
# Please don't move this line to includes/Defines.php. This line essentially
# defines a valid entry point. If you put it in includes/Defines.php, then
# any script that includes it becomes an entry point, thereby defeating
# its purpose.
define( 'MEDIAWIKI', true );

# Full path to the installation directory.
$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = dirname( __DIR__ );
}

// If no LocalSettings file exists, try to display an error page
// (use a callback because it depends on TemplateParser)
if ( !defined( 'MW_CONFIG_CALLBACK' ) ) {
	if ( !defined( 'MW_CONFIG_FILE' ) ) {
		define( 'MW_CONFIG_FILE', "$IP/LocalSettings.php" );
	}
	if ( !is_readable( MW_CONFIG_FILE ) ) {
		function wfWebStartNoLocalSettings() {
			# LocalSettings.php is the per-site customization file. If it does not exist
			# the wiki installer needs to be launched or the generated file uploaded to
			# the root wiki directory. Give a hint, if it is not readable by the server.
			global $IP;
			require_once "$IP/includes/NoLocalSettings.php";
			die();
		}
		define( 'MW_CONFIG_CALLBACK', 'wfWebStartNoLocalSettings' );
	}
}

// Custom setup for WebStart entry point
if ( !defined( 'MW_SETUP_CALLBACK' ) ) {
	function wfWebStartSetup() {
		// Initialise output buffering
		// Check for previously set up buffers, to avoid a mix of gzip and non-gzip output.
		if ( ob_get_level() == 0 ) {
			ob_start( 'MediaWiki\\OutputHandler::handle' );
		}
	}
	define( 'MW_SETUP_CALLBACK', 'wfWebStartSetup' );
}

require_once "$IP/includes/Setup.php";

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
