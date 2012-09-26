<?php
/**
 * MediaWiki installer overrides.
 * Modify this file if you are a packager who needs to modify the behavior of the MediaWiki installer.
 * Altering it is preferred over changing anything in /includes.
 *
 * Note: this file doesn't gets included from a global scope, don't use globals directly.
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
