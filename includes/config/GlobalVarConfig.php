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
 * @newable
 * @since 1.23
 */
class GlobalVarConfig implements Config {

	/**
	 * Prefix to use for configuration variables
	 * @var string
	 */
	private $prefix;

	/**
	 * Default builder function
	 * @return GlobalVarConfig
	 */
	public static function newInstance() {
		return new GlobalVarConfig();
	}

	/**
	 * @stable to call
	 *
	 * @param string $prefix
	 */
	public function __construct( $prefix = 'wg' ) {
		$this->prefix = $prefix;
	}

	/**
	 * @inheritDoc
	 */
	public function get( $name ) {
		if ( !$this->has( $name ) ) {
			throw new ConfigException( __METHOD__ . ": undefined option: '$name'" );
		}
		return $this->getWithPrefix( $this->prefix, $name );
	}

	/**
	 * @inheritDoc
	 */
	public function has( $name ) {
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
