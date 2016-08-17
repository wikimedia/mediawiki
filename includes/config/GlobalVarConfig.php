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
 * Accesses configuration settings from $GLOBALS
 *
 * @since 1.23
 */
class GlobalVarConfig implements Config {

	/**
	 * Prefix to use for configuration variables
	 * @var string
	 */
	private $prefix;

	/**
	 * A list of keys that are "part" of this Config instance.
	 * If set, this will be an array, otherwise false for all
	 * keys
	 *
	 * @var bool|array
	 */
	private $keys = false;

	/**
	 * Default builder function
	 * @return GlobalVarConfig
	 */
	public static function newInstance() {
		return new GlobalVarConfig();
	}

	/**
	 * @param string $prefix
	 * @param array|bool $keys A list of keys that are "part" of this Config
	 *                         instance. If set, accessing a key not in the list
	 *                         will throw a ConfigException.
	 */
	public function __construct( $prefix = 'wg', $keys = false ) {
		$this->prefix = $prefix;
		$this->keys = $keys;
	}

	/**
	 * @see Config::get
	 */
	public function get( $name ) {
		if ( !$this->has( $name ) ) {
			throw new ConfigException( __METHOD__ . ": undefined option: '$name'" );
		}
		return $this->getWithPrefix( $this->prefix, $name );
	}

	/**
	 * @see Config::has
	 */
	public function has( $name ) {
		if ( $this->keys !== false && !in_array( $name, $this->keys ) ) {
			// If we have a whitelist, and it is not set, throw an exception.
			return false;
		}
		return $this->hasWithPrefix( $this->prefix, $name );
	}

	/**
	 * Get a variable with a given prefix, if not the defaults.
	 *
	 * @param string $prefix Prefix to use on the variable, if one.
	 * @param string $name Variable name without prefix
	 * @return mixed
	 */
	protected function getWithPrefix( $prefix, $name ) {
		return $GLOBALS[$prefix . $name];
	}

	/**
	 * Check if a variable with a given prefix is set
	 *
	 * @param string $prefix Prefix to use on the variable
	 * @param string $name Variable name without prefix
	 * @return bool
	 */
	protected function hasWithPrefix( $prefix, $name ) {
		$var = $prefix . $name;
		return array_key_exists( $var, $GLOBALS );
	}
}
