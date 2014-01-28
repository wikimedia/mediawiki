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
 * Dispatcher for configuration in MediaWiki. To use config, construct
 * this object and then call get() or set() as needed
 *
 * @since 1.23
 */
class Config implements IConfig {
	/**
	 * @var IConfig
	 */
	protected $backend;

	/**
	 * Constructor
	 * @param IConfig $backend The backend to use for configuration
	 * @param array $config Any configuration for the backend
	 */
	public function __construct( IConfig $backend ) {
		$this->backend = $backend;
		$this->config = $config;
	}

	/**
	 * Factory for getting configuration objects
	 * @param string $backendClass IConfig implementation to use, default $wgConfigClass
	 * @param array $config Configuration to pass to an implementation
	 * @return Config
	 */
	public static function factory( $backendClass = null, $config = array() ) {
		global $wgConfigClass;
		static $configs = array();

		$class = $backendClass ?: $wgConfigClass;
		if ( !isset( $configs[$class] ) ) {
			$configs[$class] = new self( new $class( $config ) );
		}

		return $configs[$class];
	}

	/**
	 * @param string $name Name of configuration option
	 */
	public function get( $name ) {
		return $this->backend->get( $name );
	}

	/**
	 * @param string $name Name of configuration option
	 * @param mixed $value Value to set
	 * @throws ConfigException
	 */
	public function set( $name, $value ) {
		$this->backend->set( $name, $value );
	}
}
