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

namespace MediaWiki\Config;

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
	 * @return self
	 */
	public static function newInstance() {
		return new self();
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
		return $GLOBALS[$this->prefix . $name];
	}

	/**
	 * @inheritDoc
	 */
	public function has( $name ) {
		$var = $this->prefix . $name;
		// (T317951) Don't call array_key_exists unless we have to, as it's slow
		// on PHP 8.1+ for $GLOBALS. When the key is set but is explicitly set
		// to null, we still need to fall back to array_key_exists, but that's
		// rarer.
		return isset( $GLOBALS[$var] ) || array_key_exists( $var, $GLOBALS );
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( GlobalVarConfig::class, 'GlobalVarConfig' );
