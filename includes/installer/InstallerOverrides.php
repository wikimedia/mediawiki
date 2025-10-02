<?php

/**
 * MediaWiki installer overrides. See mw-config/overrides/README for details.
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Installer;

use MediaWiki\Request\WebRequest;

/**
 * @since 1.20
 */
class InstallerOverrides {
	/** @return array<string,class-string> */
	private static function getOverrides(): array {
		static $overrides;

		if ( !$overrides ) {
			$overrides = [
				'LocalSettingsGenerator' => LocalSettingsGenerator::class,
				'WebInstaller' => WebInstaller::class,
				'CliInstaller' => CliInstaller::class,
			];
			foreach ( glob( MW_INSTALL_PATH . '/mw-config/overrides/*.php' ) as $file ) {
				require $file;
			}
		}

		return $overrides;
	}

	/**
	 * Instantiates and returns an instance of LocalSettingsGenerator or its descendant classes
	 * @param Installer $installer
	 * @return LocalSettingsGenerator
	 */
	public static function getLocalSettingsGenerator( Installer $installer ) {
		$className = self::getOverrides()['LocalSettingsGenerator'];
		return new $className( $installer );
	}

	/**
	 * Instantiates and returns an instance of WebInstaller or its descendant classes
	 * @param WebRequest $request
	 * @return WebInstaller
	 */
	public static function getWebInstaller( WebRequest $request ) {
		$className = self::getOverrides()['WebInstaller'];
		return new $className( $request );
	}

	/**
	 * Instantiates and returns an instance of CliInstaller or its descendant classes
	 * @param string $siteName
	 * @param string|null $admin
	 * @param array $options
	 * @return CliInstaller
	 */
	public static function getCliInstaller( $siteName, $admin = null, array $options = [] ) {
		$className = self::getOverrides()['CliInstaller'];
		return new $className( $siteName, $admin, $options );
	}
}
