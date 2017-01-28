<?php
/**
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
 * Minimalist confguration settings to bootstrap mediawiki services
 *
 * @since 1.29
 */
class BootstrapConfig implements Config {

	/**
	 * @warning intentional upper case
	 * @var array
	*/
	private $ConfigRegistry;

	/**
	 * @warning intentional upper case
	 * @var array
	*/
	private $ServiceWiringFiles;

	public function __construct() {
		global $wgConfigRegistry, $wgServiceWiringFiles;
		$this->ConfigRegistry = $wgConfigRegistry;
		$this->ServiceWiringFiles = $wgServiceWiringFiles;
	}

	/**
	 * @see Config::get
	 */
	public function get( $name ) {
		if ( !$this->has( $name ) ) {
			throw new ConfigException( __METHOD__ . ": undefined option: '$name'" );
		}
		return $this->$name;
	}

	/**
	 * @see Config::has
	 */
	public function has( $name ) {
		return property_exists( 'BootstrapConfig', $name );
	}

}
