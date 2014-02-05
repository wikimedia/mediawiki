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
 * Abstract class for get settings for
 *
 * @since 1.23
 */
abstract class Config {
	/**
	 * @param string $name configuration variable name without prefix
	 * @param string $prefix of the variable name
	 * @return mixed
	 */
	abstract public function get( $name, $prefix = 'wg' );

	/**
	 * @param string $name configuration variable name without prefix
	 * @param mixed $value to set
	 * @param string $prefix of the variable name
	 * @return Status object indicating success or failure
	 */
	abstract public function set( $name, $value, $prefix = 'wg' );

	/**
	 * @param string|null $type class name for Config object,
	 *        uses $wgConfigClass if not provided
	 * @return Config
	 */
	public static function factory( $type = null ) {
		if ( !$type ) {
			global $wgConfigClass;
			$type = $wgConfigClass;
		}

		return new $type;
	}
}
