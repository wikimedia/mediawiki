<?php

namespace MediaWiki\Settings;

use MediaWiki\Settings\Source\SettingsFileUtils;

/**
 * Utility for loading LocalSettings files.
 *
 * @since 1.38
 */
class LocalSettingsLoader {

	/** @var SettingsBuilder */
	private $settingsBuilder;

	/** @var string */
	private $baseDir;

	/**
	 * @param SettingsBuilder $settingsBuilder
	 * @param string $baseDir
	 */
	public function __construct( SettingsBuilder $settingsBuilder, string $baseDir ) {
		$this->settingsBuilder = $settingsBuilder;
		$this->baseDir = $baseDir;
	}

	/**
	 * Loads a settings file into the SettingsBuilder provided to the constructor.
	 *
	 * This supports JSON and YAML files, PHP files that return a settings array, as well as
	 * traditional LocalSettings.php files that set variables, for backwards compatibility.
	 *
	 * @warning This does not support setting configuration variables that use a prefix other
	 *          than "wg"!
	 *
	 * @param string $file
	 */
	public function loadLocalSettingsFile( string $file ) {
		// If $file is not a PHP file, just load it as a data file.
		if ( !str_ends_with( $file, '.php' ) ) {
			$this->settingsBuilder->loadFile( $file );
			return;
		}

		// Make config settings available in local scope.
		$config = $this->settingsBuilder->getConfig();
		foreach ( $this->settingsBuilder->getDefinedConfigKeys() as $key ) {
			$var = "wg$key"; // NOTE: broken for extensions that use prefixes other than "wg"!
			$$var = $config->get( $key ); // XXX: slow?! Can we get the entire array in one go?
		}

		// make available some non-config globals available
		// phpcs:ignore MediaWiki.VariableAnalysis.UnusedGlobalVariables.UnusedGlobal$wgCommandLineMode
		global $wgCommandLineMode;

		// make additional variables available
		// phpcs:ignore MediaWiki.VariableAnalysis.MisleadingGlobalNames.Misleading$wgSettings
		$wgSettings = $this->settingsBuilder;
		$IP = $this->baseDir;

		// pull in the actual settings file
		$file = SettingsFileUtils::resolveRelativeLocation( $file, $this->baseDir );
		$settings = require $file;

		// Capture config variables.
		$overrides = [];
		foreach ( get_defined_vars() as $name => $value ) {
			if ( str_starts_with( $name, 'wg' ) ) {
				$key = substr( $name, 2 );
				$overrides[$key] = $value;
			}
		}

		$this->settingsBuilder->overrideConfigValues( $overrides );

		// If the file returned a settings array, use it.
		// This is especially useful for generated PHP files.
		if ( is_array( $settings ) ) {
			$this->settingsBuilder->loadArray( $settings );
		}
	}
}
