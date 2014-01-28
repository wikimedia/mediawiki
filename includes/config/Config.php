<?php
/**
 * Copyright 2014
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
 * Base class for configuration
 *
 * @since 1.23
 */
abstract class Config {
	/**
	 * Factory for getting configuration objects
	 * @param string $backendClass IConfig implementation to use, default $wgConfigClass
	 * @param array $config Configuration to pass to an implementation
	 * @return Config
	 */
	public static function factory( $backendClass = null, $config = array() ) {
		global $wgConfigClass;

		$class = $backendClass ?: $wgConfigClass;
		$conf = new $class();
		$conf->setConfigConfig( $config );

		return $conf;
	}

	/**
	 * Get a configuration variable such as "SiteName" or "UploadMaintenance."
	 *
	 * @param string $name Name of configuration option
	 * @return mixed Value configured
	 * @throws ConfigException
	 */
	abstract public function get( $name );

	/**
	 * Set a configuration variable such a "SiteName" to something like "My Wiki"
	 *
	 * @param string $name Name of configuration option
	 * @param mixed $value Value to set
	 * @throws ConfigException
	 */
	abstract public function set( $name, $value );

	/**
	 * Set configuration for this configuration. No op for base class.
	 *
	 * @param array $conf Array of configuration. Parameters will vary per backend
	 */
	public function setConfigConfig( array $conf ) {}
}
