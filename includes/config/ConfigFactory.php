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
 * Factory class to create Config objects
 *
 * @since 1.23
 */
class ConfigFactory {

	/**
	 * Map of config name => callback
	 * @var array
	 */
	protected $builders = array();

	/**
	 * Config objects that have already been created
	 * name => Config object
	 * @var array
	 */
	protected $configs = array();

	public static function singleton() {
		static $self = null;
		if ( !$self ) {
			$self = new self;
			global $wgConfigRegistry;
			foreach ( $wgConfigRegistry as $name => $callback ) {
				$self->register( $name, $callback );
			}
		}
		return $self;
	}

	/**
	 * Register a new config builder
	 * @param string $name
	 * @param callable $callback
	 */
	public function register( $name, $callback ) {
		$this->builders[$name] = $callback;
	}

	/**
	 * Create a given Config using the callback
	 * If an object was already created, the same
	 * object is returned
	 * provided earlier
	 * @param string $name
	 * @return Config
	 */
	public function makeConfig( $name ) {
		if ( !isset( $this->configs[$name] ) ) {
			$this->configs[$name] = call_user_func( $this->builders[$name] );
		}

		return $this->configs[$name];
	}
}
