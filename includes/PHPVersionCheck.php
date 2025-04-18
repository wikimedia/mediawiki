<?php
/**
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

// phpcs:disable Generic.Arrays.DisallowLongArraySyntax,PSR2.Classes.PropertyDeclaration,MediaWiki.Usage.DirUsage
// phpcs:disable Squiz.Scope.MemberVarScope.Missing,Squiz.Scope.MethodScope.Missing
// phpcs:disable MediaWiki.Usage.StaticClosure.StaticClosure
/**
 * Check PHP Version, as well as for composer dependencies in entry points,
 * and display something vaguely comprehensible in the event of a totally
 * unrecoverable error.
 *
 * @note Since we can't rely on anything external, the minimum PHP versions
 * and MW current version are hardcoded in this class.
 *
 * @note This class uses setter methods instead of a constructor so that
 * it can be compatible with PHP 4 through PHP 8 (without warnings).
 */
class PHPVersionCheck {
	/** @var string The number of the MediaWiki version used. If you're updating MW_VERSION in Defines.php, you must also update this value. */
	var $mwVersion = '1.43';

	/** @var string[] A mapping of PHP functions to PHP extensions. */
	var $functionsExtensionsMapping = array(
		'mb_substr'   => 'mbstring',
		'xml_parser_create' => 'xml',
		'ctype_digit' => 'ctype',
		'json_decode' => 'json',
		'iconv'       => 'iconv',
		'mime_content_type' => 'fileinfo',
		'intl_is_failure' => 'intl',
	);

	/**
	 * @var string The format used for errors. One of "text" or "html"
	 */
	var $format = 'text';

	/**
	 * @var string
	 */
	var $scriptPath = '/';

	/**
	 * Set the format used for errors.
	 *
	 * @param string $format One of "text" or "html"
	 */
	function setFormat( $format ) {
		$this->format = $format;
	}

	/**
	 * Set the script path used for images in HTML-formatted errors.
	 *
	 * @param string $scriptPath
	 */
	function setScriptPath( $scriptPath ) {
		$this->scriptPath = $scriptPath;
	}

	/**
	 * Displays an error, if the installed PHP version does not meet the minimum requirement.
	 */
	function checkRequiredPHPVersion() {
		$minimumVersion = '8.1.0';

		/**
		 * This is a list of known-bad ranges of PHP versions. Syntax is like SemVer – either:
		 *
		 *  - '1.2.3' to prohibit a single version of PHP, or
		 *  - '1.2.3 – 1.2.5' to block a range, inclusive.
		 *
		 * Whitespace will be ignored.
		 *
		 * The key is not shown to users; use it to prompt future developers as to why this was
		 * chosen, ideally one or more Phabricator task references.
		 *
		 * Remember to drop irrelevant ranges when bumping $minimumVersion.
		 */
		$knownBad = array(
		);

		$passes = version_compare( PHP_VERSION, $minimumVersion, '>=' );

		$versionString = "PHP $minimumVersion or higher";

		// Left as a programmatic check to make it easier to update.
		if ( count( $knownBad ) ) {
			$versionString .= ' (and not ' . implode( ', ', array_values( $knownBad ) ) . ')';

			foreach ( $knownBad as $range ) {
				// As we don't have composer at this point, we have to do our own version range checking.
				if ( strpos( $range, '-' ) ) {
					$passes = $passes && !(
						version_compare( PHP_VERSION, trim( strstr( $range, '-', true ) ), '>=' )
						&& version_compare( PHP_VERSION, trim( substr( strstr( $range, '-', false ), 1 ) ), '<' )
					);
				} else {
					$passes = $passes && version_compare( PHP_VERSION, trim( $range ), '<>' );
				}
			}
		}

		if ( !$passes ) {
			$cliText = "Error: You are using an unsupported PHP version (PHP " . PHP_VERSION . ").\n"
			. "MediaWiki $this->mwVersion needs $versionString.\n\nCheck if you might have a newer "
			. "PHP executable with a different name.\n\n";

			$web = array();
			$web['intro'] = "MediaWiki $this->mwVersion requires $versionString; you are using PHP "
				. PHP_VERSION . ".";

			$web['longTitle'] = "Supported PHP versions";
			// phpcs:disable Generic.Files.LineLength
			$web['longHtml'] = <<<HTML
		<p>
			Please consider <a href="https://www.php.net/downloads.php">upgrading your copy of PHP</a>.
			PHP versions less than v8.1.0 are no longer <a href="https://www.php.net/supported-versions.php">supported</a>
			by the PHP Group and will not receive security or bugfix updates.
		</p>
		<p>
			If for some reason you are unable to upgrade your PHP version, you will need to
			<a href="https://www.mediawiki.org/wiki/Download">download</a> an older version of
			MediaWiki from our website. See our
			<a href="https://www.mediawiki.org/wiki/Compatibility#PHP">compatibility page</a>
			for details of which versions are compatible with prior versions of PHP.
		</p>
HTML;
			// phpcs:enable Generic.Files.LineLength
			$this->triggerError(
				$web,
				$cliText
			);
		}
	}

	/**
	 * Displays an error, if the vendor/autoload.php file could not be found.
	 */
	function checkVendorExistence() {
		if ( !file_exists( dirname( __FILE__ ) . '/../vendor/autoload.php' ) ) {
			$cliText = "Error: You are missing some dependencies. \n"
				. "MediaWiki has dependencies that need to be installed via Composer\n"
				. "or from a separate repository. Please see\n"
				. "https://www.mediawiki.org/wiki/Download_from_Git#Fetch_external_libraries\n"
				. "for help with installing them.";

			$web = array();
			$web['intro'] = "Installing some dependencies is required.";
			$web['longTitle'] = 'Dependencies';
			// phpcs:disable Generic.Files.LineLength
			$web['longHtml'] = <<<HTML
		<p>
		MediaWiki has dependencies that need to be installed via Composer
		or from a separate repository. Please see the
		<a href="https://www.mediawiki.org/wiki/Download_from_Git#Fetch_external_libraries">instructions
		for installing external libraries</a> on MediaWiki.org.
		</p>
HTML;
			// phpcs:enable Generic.Files.LineLength

			$this->triggerError( $web, $cliText );
		}
	}

	/**
	 * Displays an error, if a PHP extension does not exist.
	 */
	function checkExtensionExistence() {
		$missingExtensions = array();
		foreach ( $this->functionsExtensionsMapping as $function => $extension ) {
			if ( !function_exists( $function ) ) {
				$missingExtensions[] = array( $extension );
			}
		}

		// Special case: either of those is required, but only on 32-bit systems (T391169)
		if ( PHP_INT_SIZE < 8 && !extension_loaded( 'gmp' ) && !extension_loaded( 'bcmath' ) ) {
			$missingExtensions[] = array( 'bcmath', 'gmp' );
		}

		if ( $missingExtensions ) {
			$missingExtText = '';
			$missingExtHtml = '';
			$baseUrl = 'https://www.php.net';
			foreach ( $missingExtensions as $extNames ) {
				$plaintextLinks = array();
				$htmlLinks = array();
				foreach ( $extNames as $ext ) {
					$plaintextLinks[] = "$ext <$baseUrl/$ext>";
					$htmlLinks[] = "<b>$ext</b> (<a href=\"$baseUrl/$ext\">more information</a>)";
				}

				$missingExtText .= ' * ' . implode( ' or ', $plaintextLinks ) . "\n";
				$missingExtHtml .= "<li>" . implode( ' or ', $htmlLinks ) . "</li>";
			}

			$cliText = "Error: Missing one or more required PHP extensions. Please see\n"
				. "https://www.mediawiki.org/wiki/Manual:Installation_requirements#PHP\n"
				. "for help with installing them.\n"
				. "Please install or enable:\n" . $missingExtText;

			$web = array();
			$web['intro'] = "Installing some PHP extensions is required.";
			$web['longTitle'] = 'Required PHP extensions';
			$web['longHtml'] = <<<HTML
		<p>
		You are missing one or more extensions to PHP that MediaWiki requires to run. Please see the
		<a href="https://www.mediawiki.org/wiki/Manual:Installation_requirements#PHP">PHP
		installation requirements</a> on MediaWiki.org.
		</p>
		<p>Please install or enable:</p>
		<ul>
		$missingExtHtml
		</ul>
HTML;

			$this->triggerError( $web, $cliText );
		}
	}

	/**
	 * Output headers that prevents error pages to be cached.
	 */
	function outputHTMLHeader() {
		$protocol = isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';

		header( "$protocol 500 MediaWiki configuration Error" );
		// Don't cache error pages! They cause no end of trouble...
		header( 'Cache-Control: no-cache' );
	}

	/**
	 * Returns an error page, which is suitable for output to the end user via a web browser.
	 *
	 * @param string $introText
	 * @param string $longTitle
	 * @param string $longHtml
	 * @return string
	 */
	function getIndexErrorOutput( $introText, $longTitle, $longHtml ) {
		$encLogo =
			htmlspecialchars( str_replace( '//', '/', $this->scriptPath . '/' ) .
				'resources/assets/mediawiki.png' );

		$introHtml = htmlspecialchars( $introText );
		$longTitleHtml = htmlspecialchars( $longTitle );

		header( 'Content-type: text/html; charset=UTF-8' );

		$finalOutput = <<<HTML
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="UTF-8" />
		<title>MediaWiki {$this->mwVersion}</title>
		<style media="screen">
			body {
				color: #000;
				background-color: #fff;
				font-family: sans-serif;
				padding: 2em;
				text-align: center;
			}
			p, img, h1, h2, ul {
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
		<img src="{$encLogo}" alt="The MediaWiki logo" />
		<h1>MediaWiki {$this->mwVersion} internal error</h1>
		<p>
			{$introHtml}
		</p>
		<h2>{$longTitleHtml}</h2>
		{$longHtml}
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
	 * This function immediately terminates the PHP process.
	 *
	 * @param string[] $web
	 *  - (string) intro: Short error message, displayed on top.
	 *  - (string) longTitle: Title for the longer message.
	 *  - (string) longHtml: The longer message, as raw HTML.
	 * @param string $cliText
	 */
	function triggerError( $web, $cliText ) {
		if ( $this->format === 'html' ) {
			// Used by index.php and mw-config/index.php
			$this->outputHTMLHeader();
			$finalOutput = $this->getIndexErrorOutput(
				$web['intro'],
				$web['longTitle'],
				$web['longHtml']
			);
		} else {
			// Used by Maintenance.php (CLI)
			$finalOutput = $cliText;
		}

		echo "$finalOutput\n";
		die( 1 );
	}
}

/**
 * Check PHP version and that external dependencies are installed, and
 * display an informative error if either condition is not satisfied.
 *
 * @param string $format One of "text" or "html"
 * @param string $scriptPath Used when an error is formatted as HTML.
 */
function wfEntryPointCheck( $format = 'text', $scriptPath = '/' ) {
	$phpVersionCheck = new PHPVersionCheck();
	$phpVersionCheck->setFormat( $format );
	$phpVersionCheck->setScriptPath( $scriptPath );
	$phpVersionCheck->checkRequiredPHPVersion();
	$phpVersionCheck->checkVendorExistence();
	$phpVersionCheck->checkExtensionExistence();
}
