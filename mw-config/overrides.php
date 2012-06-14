<?php
/**
 * MediaWiki installer overrides.
 * Modify this file if you are a packager who needs to modify the behavior of the MediaWiki installer.
 * Altering it is preferred over changing anything in /includes.
 *
 * Note: this file doesn't gets included from a global scope, don't use globals directly.
 */

/*

Example of modifications:

	public static function getLocalSettingsGenerator( Installer $installer ) {
		return new MyLocalSettingsGenerator( $installer );
	}

Then add the following to the bottom of this file:

class MyLocalSettingsGenerator extends LocalSettingsGenerator {
	function getText() {
		// Modify an existing setting
		$this->values['wgResourceLoaderMaxQueryLength'] = 512;
		// add a new setting
		$ls = parent::getText();
		return $ls . "\n\$wgUseTex = true;\n";
	}
}
*/

/**
 * @since 1.20
 */
class InstallerOverrides {
	/**
	 * Instantiates and returns an instance of LocalSettingsGenerator or its descendant classes
	 * @param Installer $installer
	 * @return LocalSettingsGenerator
	 */
	public static function getLocalSettingsGenerator( Installer $installer ) {
		return new LocalSettingsGenerator( $installer );
	}

	/**
	 * Instantiates and returns an instance of WebInstaller or its descendant classes
	 * @param WebRequest $request
	 * @return WebInstaller
	 */
	public static function getWebInstaller( WebRequest $request ) {
		return new WebInstaller( $request );
	}

	/**
	 * Instantiates and returns an instance of CliInstaller or its descendant classes
	 * @param string $siteName
	 * @param string|null $admin
	 * @param array $options
	 * @return CliInstaller
	 */
	public static function getCliInstaller( $siteName, $admin = null, array $options = array() ) {
		return new CliInstaller( $siteName, $admin, $options );
	}
}
