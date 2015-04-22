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
use Wikimedia\Assert\Assert;

/**
 * Handles a simple LRU key/value map with a maximum number of entries
 *
 * Use ProcessCacheLRU if hierarchical purging is needed or objects can become stale
 *
 * @see ProcessCacheLRU
 * @ingroup Cache
 * @since 1.23
 */
class MapCacheLRU {
	/** @var array */
	protected $cache = array(); // (key => value)

	protected $maxCacheKeys; // integer; max entries

	/**
	 * @param int $maxKeys Maximum number of entries allowed (min 1).
	 * @throws Exception When $maxCacheKeys is not an int or =< 0.
	 */
	public function __construct( $maxKeys ) {
		Assert::parameterType( 'integer', $maxKeys, '$maxKeys' );
		Assert::parameter( $maxKeys >= 1, '$maxKeys', 'must be >= 1' );

		$this->maxCacheKeys = $maxKeys;
	}

	/**
	 * Set a key/value pair.
	 * This will prune the cache if it gets too large based on LRU.
	 * If the item is already set, it will be pushed to the top of the cache.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function set( $key, $value ) {
		if ( array_key_exists( $key, $this->cache ) ) {
			$this->ping( $key ); // push to top
		} elseif ( count( $this->cache ) >= $this->maxCacheKeys ) {
			reset( $this->cache );
			$evictKey = key( $this->cache );
			unset( $this->cache[$evictKey] );
		}
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
	 * If the item is already set, it will be pushed to the top of the cache.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get( $key ) {
		if ( array_key_exists( $key, $this->cache ) ) {
			$this->ping( $key ); // push to top
			return $this->cache[$key];
		} else {
			return null;
		}
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

	/**
	 * Push an entry to the top of the cache
	 *
	 * @param string $key
	 */
	protected function ping( $key ) {
		$item = $this->cache[$key];
		unset( $this->cache[$key] );
		$this->cache[$key] = $item;
	}
}
