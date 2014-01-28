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
class GlobalVarConfig extends Config {
	/**
	 * Default prefix for MediaWiki global variables. They begin with 'wg'
	 * @var string
	 */
	const DEFAULT_PREFIX = 'wg';

	/**
	 * Prefix to use for configuration variables
	 * @var string
	 */
	private $prefix = self::DEFAULT_PREFIX;

	public function __construct( array $options ) {
		if ( isset( $options['prefix'] ) ) {
			$this->prefix = $options['prefix'];
		}
	}

	/**
	 * @see Config::get
	 */
	public function get( $name ) {
		return $this->getWithPrefix( $this->prefix, $name );
	}

	/**
	 * @see Config::set
	 */
	public function set( $name, $value ) {
		$this->setWithPrefix( $this->prefix, $name, $value );
	}

	/**
	 * Get a variable with a given prefix, if not the defaults.
	 *
	 * @param string $prefix Prefix to use on the variable, if one.
	 * @param string $name Variable name without prefix
	 * @throws ConfigException
	 * @return mixed
	 */
	public function getWithPrefix( $prefix, $name ) {
		$var = $prefix . $name;
		if ( !isset( $GLOBALS[ $var ] ) ) {
			throw new ConfigException( __METHOD__ . ": undefined variable: '$var'" );
		}
		return $GLOBALS[ $var ];
	}

	/**
	 * Get a variable with a given prefix, if not the defaults.
	 *
	 * @param string $prefix Prefix to use on the variable
	 * @param string $name Variable name without prefix
	 * @param mixed $value value to set
	 */
	public function setWithPrefix( $prefix, $name, $value ) {
		$GLOBALS[ $prefix . $name ] = $value;
	}
}
