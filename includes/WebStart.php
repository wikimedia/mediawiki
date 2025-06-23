<?php
/**
 * The set up for all MediaWiki web requests.
 *
 * It does:
 * - web-related security checks,
 * - decide how and from where to load site configuration (LocalSettings.php),
 * - load Setup.php.
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

use MediaWiki\Context\RequestContext;
use MediaWiki\Settings\SettingsBuilder;
use Wikimedia\Http\HttpStatus;

# T17461: Make IE8 turn off content sniffing. Everybody else should ignore this
# We're adding it here so that it's *always* set, even for alternate entry
# points and when $wgOut gets disabled or overridden.
header( 'X-Content-Type-Options: nosniff' );

# Valid web server entry point, enable includes.
# Please don't move this line to includes/Defines.php. This line essentially
# defines a valid entry point. If you put it in includes/Defines.php, then
# any script that includes it becomes an entry point, thereby defeating
# its purpose.
define( 'MEDIAWIKI', true );

/**
 * @param SettingsBuilder $settings
 * @return never
 */
function wfWebStartNoLocalSettings( SettingsBuilder $settings ): never {
	# LocalSettings.php is the per-site customization file. If it does not exist
	# the wiki installer needs to be launched or the generated file uploaded to
	# the root wiki directory. Give a hint, if it is not readable by the server.
	require_once __DIR__ . '/Output/NoLocalSettings.php';
	die();
}

require_once __DIR__ . '/BootstrapHelperFunctions.php';

// If no LocalSettings file exists, try to display an error page
// (use a callback because it depends on TemplateParser)
if ( !defined( 'MW_CONFIG_CALLBACK' ) ) {
	wfDetectLocalSettingsFile();
	if ( !is_readable( MW_CONFIG_FILE ) ) {
		define( 'MW_CONFIG_CALLBACK', 'wfWebStartNoLocalSettings' );
	}
}

function wfWebStartSetup( SettingsBuilder $settings ) {
	// Initialize the default MediaWiki output buffering if no buffer is already active.
	// This avoids clashes with existing buffers in order to avoid problems,
	// like mixing gzip and non-gzip output.
	if ( ob_get_level() == 0 ) {
		// During HTTP requests, MediaWiki normally buffers the response body in a string
		// within OutputPage and prints it when ready. PHP buffers provide protection against
		// premature sending of HTTP headers due to output from PHP warnings and notices.
		// They also can be used to implement gzip support in PHP without the webserver knowing
		// which requests yield HTML and which yield large files that can be streamed.
		ob_start( [ MediaWiki\Output\OutputHandler::class, 'handle' ] );
	}
}

// Custom setup for WebStart entry point
if ( !defined( 'MW_SETUP_CALLBACK' ) ) {
	define( 'MW_SETUP_CALLBACK', 'wfWebStartSetup' );
}

require_once __DIR__ . '/Setup.php';

# Multiple DBs or commits might be used; keep the request as transactional as possible
if ( isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	ignore_user_abort( true );
}

if ( !defined( 'MW_API' ) && !defined( 'MW_REST_API' ) &&
	RequestContext::getMain()->getRequest()->getHeader( 'Promise-Non-Write-API-Action' )
) {
	header( 'Cache-Control: no-cache' );
	header( 'Content-Type: text/html; charset=utf-8' );
	HttpStatus::header( 400 );
	$errorHtml = wfMessage( 'nonwrite-api-promise-error' )
		->useDatabase( false )
		->inContentLanguage()
		->escaped();
	$content = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8" /><meta name="color-scheme" content="light dark" /></head>
<body>
$errorHtml
</body>
</html>

HTML;
	header( 'Content-Length: ' . strlen( $content ) );
	echo $content;
	die();
}
