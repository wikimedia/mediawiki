<?php
/**
 * Per-process memory cache for storing items.
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
 * @ingroup Cache
 */

/**
 * Trivial get/set interface for an associative array
 *
 * @ingroup Cache
 * @since 1.27
 */
class MapCache {
	/** @var array */
	protected $cache = array(); // (key => value)

	/**
	 * @param array $initialData Map of key/values to pre-load.
	 */
	public function __construct( $initialData = array() ) {
		$this->cache = $initialData;
	}

	/**
	 * Set a key/value pair.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function set( $key, $value ) {
		$this->cache[$key] = $value;
	}

	/**
	 * Check if a key exists
	 *
	 * @param string $key
	 * @return bool
	 */
	public function has( $key ) {
		return array_key_exists( $key, $this->cache );
	}

	/**
	 * Get the value for a key.
	 * This returns null if the key is not set.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get( $key ) {
		return $this->has( $key ) ? $this->cache[$key] : null;
	}

	/**
	 * @return array
	 * @since 1.25
	 */
	public function getAllKeys() {
		return array_keys( $this->cache );
	}

	/**
	 * Clear one or several cache entries, or all cache entries
	 *
	 * @param string|array $keys
	 * @return void
	 */
	public function clear( $keys = null ) {
		if ( $keys === null ) {
			$this->cache = array();
		} else {
			foreach ( (array)$keys as $key ) {
				unset( $this->cache[$key] );
			}
		}
	}
}
