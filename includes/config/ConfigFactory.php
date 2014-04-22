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

	protected $configs = array();

	public static function singleton() {
		static $self = null;
		if ( !$self ) {
			$self = new self;
		}
		return $self;
	}

	/**
	 * Register a new config
	 * @param string $name
	 * @param callable $callback
	 */
	public function register( $name, $callback ) {
		$this->configs[$name] = $callback;
	}

	/**
	 * Create a given Config using the callback
	 * provided earlier
	 * @param string $name
	 * @return Config
	 */
	public function makeConfig( $name ) {
		return $this->configs[$name]();
	}
}
