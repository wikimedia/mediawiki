<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

// phpcs:disable PSR12.Properties.ConstantVisibility.NotFound

namespace MediaWiki;

/**
 * Check PHP Version, as well as for composer dependencies in entry points,
 * and display something vaguely comprehensible in the event of a totally
 * unrecoverable error.
 *
 * @note Since we can't rely on anything external, the minimum PHP versions
 * and MW current version are hardcoded in this class.
 *
 * @note This class should be compatible with PHP 5.4 through PHP 8
 * (without warnings). It is no longer compatible with PHP 4.
 *
 * @internal
 */
class PHPVersionCheck {
	/**
	 * The number of the MediaWiki version used.
	 *
	 * If you're updating MW_VERSION in Defines.php, you must also update this value.
	 *
	 * @note For PHP 7.0 compatibility, this constant has no visibility keyword.
	 *
	 * @internal
	 */
	const MW_VERSION = '1.47';

	/**
	 * @var string[] A mapping of PHP functions to PHP extensions.
	 * @note For PHP 5.5 compatibility, this is a property rather than a constant.
	 */
	private static $functionsExtensionsMapping = [
		'mb_substr'   => 'mbstring',
		'xml_parser_create' => 'xml',
		'ctype_digit' => 'ctype',
		'iconv'       => 'iconv',
		'mime_content_type' => 'fileinfo',
		'intl_is_failure' => 'intl',
	];

	/**
	 * @var string The format used for errors. One of "text" or "html"
	 */
	private $format;

	/**
	 * @var string
	 */
	private $scriptPath;

	/**
	 * @param string $format The format used for errors. One of "text" or "html"
	 * @param string $scriptPath Used when an error is formatted as HTML.
	 */
	public function __construct( $format = 'text', $scriptPath = '/' ) {
		$this->format = $format;
		$this->scriptPath = $scriptPath;
	}

	/**
	 * Displays an error, if the installed PHP version does not meet the minimum requirement.
	 */
	private function checkRequiredPHPVersion() {
		// NOTE: Keep this in sync with composer.json and ScopeStructureTest.php
		$minimumVersion = '8.3.0';

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
		$knownBad = [
		];

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
			$mwVersion = self::MW_VERSION;
			$cliText = "Error: You are using an unsupported PHP version (PHP " . PHP_VERSION . ").\n"
			. "MediaWiki $mwVersion needs $versionString.\n\nCheck if you might have a newer "
			. "PHP executable with a different name.\n\n";

			$web = [];
			$web['intro'] = "MediaWiki $mwVersion requires $versionString; you are using PHP "
				. PHP_VERSION . ".";

			$web['longTitle'] = "Supported PHP versions";
			// phpcs:disable Generic.Files.LineLength
			$web['longHtml'] = <<<HTML
		<p>
			Please consider <a href="https://www.php.net/downloads.php">upgrading your copy of PHP</a>.
			PHP versions less than v8.2.0 are no longer <a href="https://www.php.net/supported-versions.php">supported</a>
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
	private function checkVendorExistence() {
		if ( !file_exists( __DIR__ . '/../vendor/autoload.php' ) ) {
			$cliText = "Error: You are missing some dependencies. \n"
				. "MediaWiki has dependencies that need to be installed via Composer\n"
				. "or from a separate repository. Please see\n"
				. "https://www.mediawiki.org/wiki/Download_from_Git#Fetch_external_libraries\n"
				. "for help with installing them.";

			$web = [];
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
	private function checkExtensionExistence() {
		$missingExtensions = [];
		foreach ( self::$functionsExtensionsMapping as $function => $extension ) {
			if ( !function_exists( $function ) ) {
				$missingExtensions[] = [ $extension ];
			}
		}

		// Special case: either of those is required, but only on 32-bit systems (T391169)
		if ( PHP_INT_SIZE < 8 && !extension_loaded( 'gmp' ) && !extension_loaded( 'bcmath' ) ) {
			$missingExtensions[] = [ 'bcmath', 'gmp' ];
		}

		if ( $missingExtensions ) {
			$missingExtText = '';
			$missingExtHtml = '';
			$baseUrl = 'https://www.php.net';
			foreach ( $missingExtensions as $extNames ) {
				$plaintextLinks = [];
				$htmlLinks = [];
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

			$web = [];
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
	private function outputHTMLHeader() {
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
	private function getIndexErrorOutput( $introText, $longTitle, $longHtml ) {
		$encLogo =
			htmlspecialchars( str_replace( '//', '/', $this->scriptPath . '/' ) .
				'resources/assets/mediawiki.png' );

		$introHtml = htmlspecialchars( $introText );
		$longTitleHtml = htmlspecialchars( $longTitle );

		header( 'Content-type: text/html; charset=UTF-8' );

		$mwVersion = self::MW_VERSION;
		$finalOutput = <<<HTML
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="UTF-8" />
		<title>MediaWiki {$mwVersion}</title>
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
		<h1>MediaWiki {$mwVersion} internal error</h1>
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
	 *
	 * This function immediately terminates the PHP process.
	 *
	 * @param string[] $web
	 *  - (string) intro: Short error message, displayed on top.
	 *  - (string) longTitle: Title for the longer message.
	 *  - (string) longHtml: The longer message, as raw HTML.
	 * @param string $cliText
	 */
	private function triggerError( array $web, $cliText ) {
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

	/**
	 * Check PHP version and that external dependencies are installed, and
	 * display an informative error if either condition is not satisfied.
	 *
	 * @internal
	 */
	public function run() {
		$this->checkRequiredPHPVersion();
		$this->checkVendorExistence();
		$this->checkExtensionExistence();
	}
}
