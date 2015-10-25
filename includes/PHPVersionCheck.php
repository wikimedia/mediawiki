<?php
/**
 * Check PHP Version, as well as for composer dependencies in entry points,
 * and display something vaguely comprehensible in the event of a totally
 * unrecoverable error.
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

/**
 * Since we can't rely on anything, the minimum PHP versions and MW current
 * version are hardcoded here.
 */
define( 'MEDIAWIKI_VERSION', '1.27' );
define( 'MEDIAWIKI_MINIMUM_PHP_VERSION', '5.3.3' );


/**
 * Display something vaguely comprehensible in the event of a totally unrecoverable error.
 * Does not assume access to *anything*; no globals, no autoloader, no database, no localisation.
 * Safe for PHP4 (and putting this here means that WebStart.php and GlobalSettings.php
 * no longer need to be).
 *
 * Calling this function kills execution immediately.
 *
 * @param string $title HTML code to be put within an <h2> tag
 * @param string $shortText
 * @param string $longText
 * @param string $longHtml
 */
function wfGenericError( $title, $shortText, $longText, $longHtml ) {

	if ( PHP_SAPI === 'cli' ) {
		echo "$longText\n";
		die( 1 );
	}

	$protocol = isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
	header( "$protocol 500 MediaWiki configuration Error" );
	header( 'Cache-control: none' );
	header( 'Pragma: no-cache' );

	if ( basename( $_SERVER['PHP_SELF'] ) === 'load.php' ) {
		// So nothing thinks this is JS or CSS
		echo "/* $shortText */\n";
		die( 1 );
	}

	$pathinfo = pathinfo( $_SERVER['SCRIPT_NAME'] );
	$dirname = defined( 'MEDIAWIKI_INSTALL' )
		? dirname( $pathinfo['dirname'] ) : $pathinfo['dirname'];
	$encLogo = htmlspecialchars(
		str_replace( '//', '/', $dirname . '/' ) .
		'resources/assets/mediawiki.png'
	);
	$shortHtml = htmlspecialchars( $shortText );
	header( 'Content-type: text/html; charset=UTF-8' );
	$mwVersion = MEDIAWIKI_VERSION;
	echo <<<HTML
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="UTF-8" />
		<title>MediaWiki {$mwVersion}</title>
		<style media='screen'>
			body {
				color: #000;
				background-color: #fff;
				font-family: sans-serif;
				padding: 2em;
				text-align: center;
			}
			p, img, h1, h2 {
				text-align: left;
				margin: 0.5em 0 1em;
			}
			h1 {
				font-size: 120%;
			}
			h2 {
				font-size: 110%;
			}
		</style>
	</head>
	<body>
		<img src="{$encLogo}" alt='The MediaWiki logo' />
		<h1>MediaWiki {$mwVersion} internal error</h1>
		<div class='error'>
		<p>
			{$shortHtml}
		</p>
		<h2>{$title}</h2>
		<p>
			{$longHtml}
		</p>
		</div>
	</body>
</html>

HTML;
	die( 1 );
}

/**
 * Display an error for the minimum PHP version requirement not being satisfied.
 *
 * @param string $type See wfGenericError
 */
function wfPHPVersionError() {
	$mwVersion = MEDIAWIKI_VERSION;
	$minimumVersionPHP = MEDIAWIKI_MINIMUM_PHP_VERSION;
	$phpVersion = PHP_VERSION;
	$shortText = "MediaWiki $mwVersion requires at least "
		. "PHP version $minimumVersionPHP, you are using PHP $phpVersion.";

	$longText = "Error: You might be using on older PHP version. \n"
		. "MediaWiki $mwVersion needs PHP $minimumVersionPHP or higher.\n\n"
		. "Check if you have a newer php executable with a different name, such as php5.\n\n";

	$longHtml = <<<HTML
			Please consider <a href="http://www.php.net/downloads.php">upgrading your copy of PHP</a>.
			PHP versions less than 5.3.0 are no longer supported by the PHP Group and will not receive
			security or bugfix updates.
		</p>
		<p>
			If for some reason you are unable to upgrade your PHP version, you will need to
			<a href="https://www.mediawiki.org/wiki/Download">download</a> an older version
			of MediaWiki from our website.  See our
			<a href="https://www.mediawiki.org/wiki/Compatibility#PHP">compatibility page</a>
			for details of which versions are compatible with prior versions of PHP.
HTML;
	wfGenericError( 'Supported PHP versions', $shortText, $longText, $longHtml );
}

/**
 * Display an error for the vendor/autoload.php file not being found.
 */
function wfMissingVendorError() {
	$shortText = "Installing some external dependencies (e.g. via composer) is also required.";

	$longText = "Error: You are missing some external dependencies. \n"
		. "MediaWiki now also has some external dependencies that need to be installed\n"
		. "via composer or from a separate git repo. Please see\n"
		. "https://www.mediawiki.org/wiki/Download_from_Git#Fetch_external_libraries\n"
		. "for help on installing the required components.";

	// @codingStandardsIgnoreStart Generic.Files.LineLength
	$longHtml = <<<HTML
		MediaWiki now also has some external dependencies that need to be installed via
		composer or from a separate git repo. Please see
		<a href="https://www.mediawiki.org/wiki/Download_from_Git#Fetch_external_libraries">mediawiki.org</a>
		for help on installing the required components.
HTML;
	// @codingStandardsIgnoreEnd

	wfGenericError( 'External dependencies', $shortText, $longText, $longHtml );
}

/**
 * Check php version and that external dependencies are installed, and
 * display an informative error if either condition is not satisfied.
 *
 * @note Since we can't rely on anything, the minimum PHP versions and MW current
 * version are hardcoded here
 */
if ( !function_exists( 'version_compare' )
	|| version_compare( PHP_VERSION, MEDIAWIKI_MINIMUM_PHP_VERSION ) < 0
) {
	wfPHPVersionError();
}

if ( !file_exists( dirname( __FILE__ ) . '/../vendor/autoload.php' ) ) {
	wfMissingVendorError();
}
