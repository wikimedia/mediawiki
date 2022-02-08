<?php
/**
 * Functions that need to be available during bootstrapping.
 * Code in this file cannot expect MediaWiki to have been initialized.
 * @file
 */

/**
 * Detect the path to the primary settings file that is
 * to be loaded by Setup.php and define MW_CONFIG_FILE.
 *
 * The primary settings file would traditionally be
 * LocalSettings.php in the installation root, but can be
 * a different file specified via the MW_CONFIG_FILE constant
 * or the environment variable of the same name.
 *
 * If the settings file has the .yaml or .json extension, it is expected
 * to use the format described on
 * https://www.mediawiki.org/wiki/Manual:YAML_settings_file_format.
 *
 * @internal
 *
 * @param string $installationPath The installation's base path, typically global $IP.
 *
 * @return string The path to the primary settings file
 *
 * @since 1.38
 */
function wfDetectLocalSettingsFile( string $installationPath ): string {
	if ( defined( 'MW_CONFIG_FILE' ) ) {
		return MW_CONFIG_FILE;
	}

	$configFile = getenv( 'MW_CONFIG_FILE' );
	if ( $configFile !== false ) {
		if ( !preg_match( '@[\\\\/]@', $configFile ) ) {
			$configFile = "$installationPath/$configFile";
		}
	} else {
		// NOTE: We could look for LocalSettings.yaml and LocalSettings.json,
		//      and use them if they exist. But having them in a web accessible
		//      place is dangerous, so better not to encourage that.
		//      In order to use LocalSettings.yaml and LocalSettings.json, they
		//      will have to be referenced explicitly by MW_CONFIG_FILE.

		$configFile = "$installationPath/LocalSettings.php";
	}

	define( 'MW_CONFIG_FILE', $configFile );
	return $configFile;
}
