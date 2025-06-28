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
 * @param string|null $installationPath The installation's base path,
 *        as returned by wfDetectInstallPath().
 *
 * @return string The path to the settings file
 */
function wfDetectLocalSettingsFile( ?string $installationPath = null ): string {
	if ( defined( 'MW_CONFIG_FILE' ) ) {
		return MW_CONFIG_FILE;
	}

	$installationPath ??= wfDetectInstallPath();

	// We could look for LocalSettings.yaml and LocalSettings.json,
	// and use them if they exist. But having them in a web accessible
	// place is dangerous, so better not to encourage that.
	// In order to use LocalSettings.yaml and LocalSettings.json, they
	// will have to be referenced explicitly by MW_CONFIG_FILE.
	$configFile = getenv( 'MW_CONFIG_FILE' ) ?: "LocalSettings.php";
	// Can't use str_contains because for maintenance scripts (update.php, install.php),
	// this is called *before* Setup.php and vendor (polyfill-php80) are included.
	if ( !str_contains( $configFile, '/' ) ) {
		$configFile = "$installationPath/$configFile";
	}

	define( 'MW_CONFIG_FILE', $configFile );
	return $configFile;
}

/**
 * Decide and remember where mediawiki is installed.
 *
 * This is used by Setup.php and will (if not already) store the result
 * in the MW_INSTALL_PATH constant.
 *
 * The install path is detected based on the location of this file,
 * but can be overwritten using the MW_INSTALL_PATH environment variable.
 *
 * @internal Only for use by Setup.php and Installer.
 * @since 1.39
 * @return string The path to the mediawiki installation
 */
function wfDetectInstallPath(): string {
	if ( !defined( 'MW_INSTALL_PATH' ) ) {
		$IP = getenv( 'MW_INSTALL_PATH' );
		if ( $IP === false ) {
			$IP = dirname( __DIR__ );
		}
		define( 'MW_INSTALL_PATH', $IP );
	}

	return MW_INSTALL_PATH;
}

/**
 * Check if the operating system is Windows
 *
 * @return bool True if it's Windows, false otherwise.
 */
function wfIsWindows() {
	return PHP_OS_FAMILY === 'Windows';
}

/**
 * Check if we are running from the commandline
 *
 * @since 1.31
 * @return bool
 */
function wfIsCLI() {
	return PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg';
}
