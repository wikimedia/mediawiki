<?php
/**
 * Functions that need to be available during bootstrapping.
 * Code in this file cannot expect MediaWiki to have been initialized.
 * @file
 */

/**
 * Decide and remember where to load LocalSettings from.
 *
 * This is used by Setup.php and will (if not already) store the result
 * in the MW_CONFIG_FILE constant.
 *
 * The primary settings file is traditionally LocalSettings.php under the %MediaWiki
 * installation path, but can also be placed differently and specified via the
 * MW_CONFIG_FILE constant (from an entrypoint wrapper) or via a `MW_CONFIG_FILE`
 * environment variable (from the web server).
 *
 * Experimental: The settings file can use the `.yaml` or `.json` extension, which
 * must use the format described on
 * https://www.mediawiki.org/wiki/Manual:YAML_settings_file_format.
 *
 * @internal Only for use by Setup.php and Installer.
 * @since 1.38
 * @param string $installationPath The installation's base path, typically global $IP.
 * @return string The path to the settings file
 */
function wfDetectLocalSettingsFile( string $installationPath ): string {
	if ( defined( 'MW_CONFIG_FILE' ) ) {
		return MW_CONFIG_FILE;
	}

	// We could look for LocalSettings.yaml and LocalSettings.json,
	// and use them if they exist. But having them in a web accessible
	// place is dangerous, so better not to encourage that.
	// In order to use LocalSettings.yaml and LocalSettings.json, they
	// will have to be referenced explicitly by MW_CONFIG_FILE.
	$configFile = getenv( 'MW_CONFIG_FILE' ) ?: "LocalSettings.php";
	// Can't use str_contains because for maintenance scripts (update.php, install.php),
	// this is called *before* Setup.php and vendor (polyfill-php80) are included.
	if ( strpos( $configFile, '/' ) === false ) {
		$configFile = "$installationPath/$configFile";
	}

	define( 'MW_CONFIG_FILE', $configFile );
	return $configFile;
}
