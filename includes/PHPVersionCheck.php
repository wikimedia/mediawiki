<?php
// @codingStandardsIgnoreFile Generic.Arrays.DisallowLongArraySyntax
// @codingStandardsIgnoreFile Generic.Files.LineLength
// @codingStandardsIgnoreFile MediaWiki.Usage.DirUsage.FunctionFound
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
class PHPVersionCheck {
	/* @var string The number of the MediaWiki version used */
	var $mwVersion = '1.29';
	/* @var string The minimum php version for MediaWiki to run */
	var $minimumVersionPHP = '5.5.9';
	var $functionsExtensionsMapping = array(
		'mb_substr'   => 'mbstring',
		'utf8_encode' => 'xml',
		'ctype_digit' => 'ctype',
		'json_decode' => 'json',
		'iconv'       => 'iconv',
	);

	/**
	 * @var string Which entry point we are protecting. One of:
	 *   - index.php
	 *   - load.php
	 *   - api.php
	 *   - mw-config/index.php
	 *   - cli
	 */
	var $entryPoint = null;

	/**
	 * @param string $entryPoint Which entry point we are protecting. One of:
	 *   - index.php
	 *   - load.php
	 *   - api.php
	 *   - mw-config/index.php
	 *   - cli
	 * @return $this
	 */
	function setEntryPoint( $entryPoint ) {
		$this->entryPoint = $entryPoint;
	}

	/**
	 * Returns the version of the installed php implementation.
	 *
	 * @return string
	 */
	function getPHPImplVersion() {
		return PHP_VERSION;
	}

	/**
	 * Displays an error, if the installed php version does not meet the minimum requirement.
	 *
	 * @return $this
	 */
	function checkRequiredPHPVersion() {
		if (
			!function_exists( 'version_compare' )
			|| version_compare( $this->getPHPImplVersion(), $this->minimumVersionPHP ) < 0
		) {
			$shortText = "MediaWiki $this->mwVersion requires at least PHP version"
				. " $this->minimumVersionPHP, you are using PHP {$this->getPHPImplVersion()}.";

			$longText = "Error: You might be using on older PHP version. \n"
				. "MediaWiki $this->mwVersion needs PHP $this->minimumVersionPHP or higher.\n\n"
				. "Check if you have a newer php executable with a different name, "
				. "such as php5.\n\n";

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
			$this->triggerError( 'Supported PHP versions', $shortText, $longText, $longHtml );
		}
	}

	/**
	 * Displays an error, if the vendor/autoload.php file could not be found.
	 *
	 * @return $this
	 */
	function checkVendorExistence() {
		if ( !file_exists( dirname( __FILE__ ) . '/../vendor/autoload.php' ) ) {
			$shortText = "Installing some external dependencies (e.g. via composer) is required.";

			$longText = "Error: You are missing some external dependencies. \n"
				. "MediaWiki now also has some external dependencies that need to be installed\n"
				. "via composer or from a separate git repo. Please see\n"
				. "https://www.mediawiki.org/wiki/Download_from_Git#Fetch_external_libraries\n"
				. "for help on installing the required components.";

			$longHtml = <<<HTML
		MediaWiki now also has some external dependencies that need to be installed via
		composer or from a separate git repo. Please see
		<a href="https://www.mediawiki.org/wiki/Download_from_Git#Fetch_external_libraries">mediawiki.org</a>
		for help on installing the required components.
HTML;

			$this->triggerError( 'External dependencies', $shortText, $longText, $longHtml );
		}
	}

	/**
	 * Displays an error, if a PHP extension does not exist.
	 *
	 * @return $this
	 */
	function checkExtensionExistence() {
		$missingExtensions = array();
		foreach ( $this->functionsExtensionsMapping as $function => $extension ) {
			if ( !function_exists( $function ) ) {
				$missingExtensions[] = $extension;
			}
		}

		if ( $missingExtensions ) {
			$shortText = "Installing some PHP extensions is required.";

			$missingExtText = '';
			$missingExtHtml = '';
			$baseUrl = 'https://secure.php.net';
			foreach ( $missingExtensions as $ext ) {
				$missingExtText .= " * $ext <$baseUrl/$ext>\n";
				$missingExtHtml .= "<li><b>$ext</b> "
					. "(<a href=\"$baseUrl/$ext\">more information</a>)</li>";
			}

			$cliText = "Error: Missing one or more required components of PHP.\n"
				. "You are missing a required extension to PHP that MediaWiki needs.\n"
				. "Please install:\n" . $missingExtText;

			$longHtml = <<<HTML
		You are missing a required extension to PHP that MediaWiki
		requires to run. Please install:
		<ul>
		$missingExtHtml
		</ul>
HTML;

			$this->triggerError( 'Required components', $shortText, $cliText, $longHtml );
		}
	}

	/**
	 * Output headers that prevents error pages to be cached.
	 */
	function outputHTMLHeader() {
		$protocol = isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';

		header( "$protocol 500 MediaWiki configuration Error" );
		// Don't cache error pages!  They cause no end of trouble...
		header( 'Cache-control: none' );
		header( 'Pragma: no-cache' );
	}

	/**
	 * Returns an error page, which is suitable for output to the end user via a web browser.
	 *
	 * @param $title
	 * @param $longHtml
	 * @param $shortText
	 * @return string
	 */
	function getIndexErrorOutput( $title, $longHtml, $shortText ) {
		$pathinfo = pathinfo( $_SERVER['SCRIPT_NAME'] );
		if ( $this->entryPoint == 'mw-config/index.php' ) {
			$dirname = dirname( $pathinfo['dirname'] );
		} else {
			$dirname = $pathinfo['dirname'];
		}
		$encLogo =
			htmlspecialchars( str_replace( '//', '/', $dirname . '/' ) .
				'resources/assets/mediawiki.png' );
		$shortHtml = htmlspecialchars( $shortText );

		header( 'Content-type: text/html; charset=UTF-8' );

		$finalOutput = <<<HTML
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="UTF-8" />
		<title>MediaWiki {$this->mwVersion}</title>
		<style media='screen'>
			body {
				color: #000;
				background-color: #fff;
				font-family: sans-serif;
				padding: 2em;
				text-align: center;
			}
			p, img, h1, h2, ul  {
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
		<h1>MediaWiki {$this->mwVersion} internal error</h1>
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

		return $finalOutput;
	}

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
	function triggerError( $title, $shortText, $longText, $longHtml ) {
		switch ( $this->entryPoint ) {
			case 'cli':
				$finalOutput = $longText;
				break;
			case 'index.php':
			case 'mw-config/index.php':
				$this->outputHTMLHeader();
				$finalOutput = $this->getIndexErrorOutput( $title, $longHtml, $shortText );
				break;
			case 'load.php':
				$this->outputHTMLHeader();
				$finalOutput = "/* $shortText */";
				break;
			default:
				$this->outputHTMLHeader();
				// Handle everything that's not index.php
				$finalOutput = $shortText;
		}

		echo "$finalOutput\n";
		die( 1 );
	}
}

/**
 * Check php version and that external dependencies are installed, and
 * display an informative error if either condition is not satisfied.
 *
 * @note Since we can't rely on anything, the minimum PHP versions and MW current
 * version are hardcoded here
 */
function wfEntryPointCheck( $entryPoint ) {
	$phpVersionCheck = new PHPVersionCheck();
	$phpVersionCheck->setEntryPoint( $entryPoint );
	$phpVersionCheck->checkRequiredPHPVersion();
	$phpVersionCheck->checkVendorExistence();
	$phpVersionCheck->checkExtensionExistence();
}
