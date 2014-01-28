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

	/**
	 * @see Config::get
	 */
	public function get( $name ) {
		$var = $this->prefix . $name;
		if ( isset( $GLOBALS[ $var ] ) ) {
			return $GLOBALS[ $var ];
		} else {
			throw new ConfigException( __METHOD__ . ": undefined variable: '$var'" );
		}
	}

	/**
	 * @see Config::set
	 */
	public function set( $name, $value ) {
		$GLOBALS[ $this->prefix . $name ] = $value;
	}

	/**
	 * @see Config::setConfigConfig
	 */
	public function setConfigConfig( array $conf ) {
		if ( isset( $conf['prefix'] ) ) {
			$this->prefix = $conf['prefix'];
		}
	}

	/**
	 * Get a variable with a given prefix, if not the defaults.
	 *
	 * @param string $name Variable name without prefix
	 * @param string $prefix Prefix to use on the variable, if one.
	 * @return mixed
	 */
	public function getWithPrefix( $name, $prefix = '' ) {
		$oldPrefix = $this->prefix;
		$this->prefix = $prefix;
		$val = $this->get( $name );
		$this->prefix = $oldPrefix;
		return $val;
	}

	/**
	 * Get a variable with a given prefix, if not the defaults.
	 *
	 * @param string $name Variable name without prefix
	 * @param mixed
	 * @param string $prefix Prefix to use on the variable, if one.
	 */
	public function setWithPrefix( $name, $prefix = '') {
		$oldPrefix = $this->prefix;
		$this->prefix = $prefix;
		$this->set( $name, $value );
		$this->prefix = $oldPrefix;
	}
}
