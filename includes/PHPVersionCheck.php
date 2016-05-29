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
 * Check php version and that external dependencies are installed, and
 * display an informative error if either condition is not satisfied.
 *
 * @note Since we can't rely on anything, the minimum PHP versions and MW current
 * version are hardcoded here
 */
function wfEntryPointCheck( $entryPoint ) {
	$mwVersion = '1.28';
	$minimumVersionPHP = '5.6.9';
	$phpVersion = PHP_VERSION;
	$minimumVersionHHVM = '3.16.5';

	if ( !function_exists( 'version_compare' )
		|| version_compare( $phpVersion, $minimumVersionPHP ) < 0
	) {
		$phpORhhvmText = 'PHP';
		$php = true;
		wfPHPVersionError(
			$entryPoint, $mwVersion, $minimumVersionPHP, $phpVersion, $phpORhhvmText, $php
		);
	}

	if ( !function_exists( 'version_compare' )
		|| defined( 'HHVM_VERSION' ) && version_compare( HHVM_VERSION, $minimumVersionHHVM ) < 0
	) {
		$phpORhhvmText = 'HHVM';
		$php = false;
		wfPHPVersionError(
			$entryPoint, $mwVersion, $minimumVersionHHVM, HHVM_VERSION, $phpORhhvmText, $php
		);
	}

	// @codingStandardsIgnoreStart MediaWiki.Usage.DirUsage.FunctionFound
	if ( !file_exists( dirname( __FILE__ ) . '/../vendor/autoload.php' ) ) {
		// @codingStandardsIgnoreEnd
		wfMissingVendorError( $entryPoint, $mwVersion );
	}
}

/**
 * Display something vaguely comprehensible in the event of a totally unrecoverable error.
 * Does not assume access to *anything*; no globals, no autoloader, no database, no localisation.
 * Safe for PHP4 (and putting this here means that WebStart.php and GlobalSettings.php
 * no longer need to be).
 *
 * Calling this function kills execution immediately.
 *
 * @param string $type Which entry point we are protecting. One of:
 *   - index.php
 *   - load.php
 *   - api.php
 *   - mw-config/index.php
 *   - cli
 * @param string $mwVersion The number of the MediaWiki version used
 * @param string $title HTML code to be put within an <h2> tag
 * @param string $shortText
 * @param string $longText
 * @param string $longHtml
 */
function wfGenericError( $type, $mwVersion, $title, $shortText, $longText, $longHtml ) {
	$protocol = isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';

	if ( $type == 'cli' ) {
		$finalOutput = $longText;
	} else {
		header( "$protocol 500 MediaWiki configuration Error" );
		// Don't cache error pages!  They cause no end of trouble...
		header( 'Cache-control: none' );
		header( 'Pragma: no-cache' );

		if ( $type == 'index.php' || $type == 'mw-config/index.php' ) {
			$pathinfo = pathinfo( $_SERVER['SCRIPT_NAME'] );
			if ( $type == 'mw-config/index.php' ) {
				$dirname = dirname( $pathinfo['dirname'] );
			} else {
				$dirname = $pathinfo['dirname'];
			}
			$encLogo = htmlspecialchars(
				str_replace( '//', '/', $dirname . '/' ) .
				'resources/assets/mediawiki.png'
			);
			$shortHtml = htmlspecialchars( $shortText );

			header( 'Content-type: text/html; charset=UTF-8' );

			$finalOutput = <<<HTML
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
		// Handle everything that's not index.php
		} else {
			// So nothing thinks this is JS or CSS
			$finalOutput = ( $type == 'load.php' ) ? "/* $shortText */" : $shortText;
		}
	}
	echo "$finalOutput\n";
	die( 1 );
}

/**
 * Display an error for the minimum PHP version requirement not being satisfied.
 *
 * @param string $type See wfGenericError
 * @param string $mwVersion See wfGenericError
 * @param string $minimumVersionPHPorHHVM The minimum PHP Or HHVM version supported by MediaWiki
 * @param string $phpORhhvmVersion The current PHP Or HHVM version
 * @param string $phpORhhvmText returns PHP or HHVM
 * @param string $php returns true if it detects wrong php version
 * @param string $hhvm returns true if it detects wrong hhvm version
 */
function wfPHPVersionError(
	$type, $mwVersion, $minimumVersionPHPorHHVM, $phpORhhvmVersion, $phpORhhvmText, $php
) {
	$shortText = "MediaWiki $mwVersion requires at least "
		. "$phpORhhvmText version $minimumVersionPHPorHHVM, you are using $phpORhhvmText $phpORhhvmVersion.";

	$longText = "Error: You might be using an older $phpORhhvmText version. \n"
		. "MediaWiki $mwVersion needs $phpORhhvmText $minimumVersionPHPorHHVM or higher.\n\n"
		. "Check if you have a newer php executable with a different name, such as php5.\n\n";
	if ( $php ) {
		$longHtml = <<<HTML
				Please consider <a href="http://www.php.net/downloads.php">upgrading your copy of PHP</a>.
				PHP versions less than 5.5.0 are no longer supported by the PHP Group and will not receive
				security or bugfix updates.
			</p>
			<p>
				If for some reason you are unable to upgrade your PHP version, you will need to
				<a href="https://www.mediawiki.org/wiki/Download">download</a> an older version
				of MediaWiki from our website.  See our
				<a href="https://www.mediawiki.org/wiki/Compatibility#PHP">compatibility page</a>
				for details of which versions are compatible with prior versions of PHP.
HTML;
	} else {
		$longHtml = <<<HTML
				Please consider <a href="https://docs.hhvm.com/hhvm/installation/introduction">
				upgrading your copy of HHVM</a>.
				HHVM versions less than 3.6.5 are no longer supported by Facebook and will not receive
				security or bugfix updates.
			</p>
			<p>
				If for some reason you are unable to upgrade your HHVM version, you will need to
				<a href="https://www.mediawiki.org/wiki/Download">download</a> an older version
				of MediaWiki from our website.  See our
				<a href="https://www.mediawiki.org/wiki/Compatibility#HHVM">compatibility page</a>
				for details of which versions are compatible with prior versions of HHVM.
HTML;
	}
	wfGenericError( $type, $mwVersion, 'Supported $phpORhhvmText versions', $shortText, $longText, $longHtml );
}

/**
 * Display an error for the vendor/autoload.php file not being found.
 *
 * @param string $type See wfGenericError
 * @param string $mwVersion See wfGenericError
 */
function wfMissingVendorError( $type, $mwVersion ) {
	$shortText = "Installing some external dependencies (e.g. via composer) is required.";

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

	wfGenericError( $type, $mwVersion, 'External dependencies', $shortText, $longText, $longHtml );
}
