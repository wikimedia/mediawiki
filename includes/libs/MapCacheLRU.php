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
	protected $cache = []; // (key => value)

	protected $maxCacheKeys; // integer; max entries

	/**
	 * @param int $maxKeys Maximum number of entries allowed (min 1).
	 * @throws Exception When $maxCacheKeys is not an int or not above zero.
	 */
	public function __construct( $maxKeys ) {
		Assert::parameterType( 'integer', $maxKeys, '$maxKeys' );
		Assert::parameter( $maxKeys > 0, '$maxKeys', 'must be above zero' );

		$this->maxCacheKeys = $maxKeys;
	}

	/**
	 * @param array $values Key/value map in order of increasingly recent access
	 * @param int $maxKeys
	 * @return MapCacheLRU
	 * @since 1.30
	 */
	public static function newFromArray( array $values, $maxKeys ) {
		$mapCache = new self( $maxKeys );
		$mapCache->cache = ( count( $values ) > $maxKeys )
			? array_slice( $values, -$maxKeys, null, true )
			: $values;

		return $mapCache;
	}

	/**
	 * @return array Key/value map in order of increasingly recent access
	 * @since 1.30
	 */
	public function toArray() {
		return $this->cache;
	}

	/**
	 * Set a key/value pair.
	 * This will prune the cache if it gets too large based on LRU.
	 * If the item is already set, it will be pushed to the top of the cache.
	 *
	 * To reduce evictions due to one-off use of many new keys, $rank can be
	 * set to have keys start at the top of a bottom fraction of the list. A value
	 * of 3/8 means values start at the top of the bottom 3/8s of the list and are
	 * moved to the top of the list when accessed a second time.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param float $rank Bottom fraction of the list where keys start off [Default: 1.0]
	 * @return void
	 */
	public function set( $key, $value, $rank = 1.0 ) {
		if ( $this->has( $key ) ) {
			$this->ping( $key );
		} elseif ( count( $this->cache ) >= $this->maxCacheKeys ) {
			reset( $this->cache );
			$evictKey = key( $this->cache );
			unset( $this->cache[$evictKey] );
		}

		if ( $rank < 1.0 && $rank > 0 ) {
			$offset = intval( $rank * count( $this->cache ) );
			$this->cache = array_slice( $this->cache, 0, $offset, true )
				+ [ $key => $value ]
				+ array_slice( $this->cache, $offset, null, true );
		} else {
			$this->cache[$key] = $value;
		}
	}

	/**
	 * Check if a key exists
	 *
	 * @param string $key
	 * @return bool
	 */
	public function has( $key ) {
		if ( !is_int( $key ) && !is_string( $key ) ) {
			throw new UnexpectedValueException(
				__METHOD__ . ' called with invalid key. Must be string or integer.' );
		}
		return array_key_exists( $key, $this->cache );
	}

	/**
	 * Get the value for a key.
	 * This returns null if the key is not set.
	 * If the item is already set, it will be pushed to the top of the cache.
	 *
	 * @param string $key
	 * @return mixed Returns null if the key was not found
	 */
	public function get( $key ) {
		if ( !$this->has( $key ) ) {
			return null;
		}

		$this->ping( $key );

		return $this->cache[$key];
	}

	/**
	 * @return array
	 * @since 1.25
	 */
	public function getAllKeys() {
		return array_keys( $this->cache );
	}

	/**
	 * Get an item with the given key, producing and setting it if not found.
	 *
	 * If the callback returns false, then nothing is stored.
	 *
	 * @since 1.28
	 * @param string $key
	 * @param callable $callback Callback that will produce the value
	 * @param float $rank Bottom fraction of the list where keys start off [Default: 1.0]
	 * @return mixed The cached value if found or the result of $callback otherwise
	 */
	public function getWithSetCallback( $key, callable $callback, $rank = 1.0 ) {
		if ( $this->has( $key ) ) {
			$value = $this->get( $key );
		} else {
			$value = call_user_func( $callback );
			if ( $value !== false ) {
				$this->set( $key, $value, $rank );
			}
		}

		return $value;
	}

	/**
	 * Clear one or several cache entries, or all cache entries
	 *
	 * @param string|array $keys
	 * @return void
	 */
	public function clear( $keys = null ) {
		if ( $keys === null ) {
			$this->cache = [];
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
