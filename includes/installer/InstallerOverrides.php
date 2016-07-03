<?php
/**
 * MediaWiki installer overrides. See mw-config/overrides/README for details.
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
 * @since 1.20
 */
class InstallerOverrides {
	private static function getOverrides() {
		global $IP;
		static $overrides;

		if ( !$overrides ) {
			$overrides = [
				'LocalSettingsGenerator' => 'LocalSettingsGenerator',
				'WebInstaller' => 'WebInstaller',
				'CliInstaller' => 'CliInstaller',
			];
			foreach ( glob( "$IP/mw-config/overrides/*.php" ) as $file ) {
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
