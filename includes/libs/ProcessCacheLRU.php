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
 * Class for process caching individual properties of expiring items
 *
 * When the key for an entire item is deleted, all properties for it are deleted
 *
 * @ingroup Cache
 * @deprecated Since 1.32 Use MapCacheLRU instead
 */
class ProcessCacheLRU {
	/** @var MapCacheLRU */
	protected $cache;

	/**
	 * @param int $maxKeys Maximum number of entries allowed (min 1).
	 * @throws UnexpectedValueException When $maxCacheKeys is not an int or =< 0.
	 */
	public function __construct( $maxKeys ) {
		$this->cache = new MapCacheLRU( $maxKeys );
	}

	/**
	 * Set a property field for a cache entry.
	 * This will prune the cache if it gets too large based on LRU.
	 * If the item is already set, it will be pushed to the top of the cache.
	 *
	 * @param string $key
	 * @param string $prop
	 * @param mixed $value
	 * @return void
	 */
	public function set( $key, $prop, $value ) {
		$this->cache->setField( $key, $prop, $value );
	}

	/**
	 * Check if a property field exists for a cache entry.
	 *
	 * @param string $key
	 * @param string $prop
	 * @param float $maxAge Ignore items older than this many seconds (since 1.21)
	 * @return bool
	 */
	public function has( $key, $prop, $maxAge = 0.0 ) {
		return $this->cache->hasField( $key, $prop, $maxAge );
	}

	/**
	 * Get a property field for a cache entry.
	 * This returns null if the property is not set.
	 * If the item is already set, it will be pushed to the top of the cache.
	 *
	 * @param string $key
	 * @param string $prop
	 * @return mixed
	 */
	public function get( $key, $prop ) {
		return $this->cache->getField( $key, $prop );
	}

	/**
	 * Clear one or several cache entries, or all cache entries.
	 *
	 * @param string|array|null $keys
	 * @return void
	 */
	public function clear( $keys = null ) {
		$this->cache->clear( $keys );
	}

	/**
	 * Resize the maximum number of cache entries, removing older entries as needed
	 *
	 * @param int $maxKeys
	 * @return void
	 * @throws UnexpectedValueException
	 */
	public function resize( $maxKeys ) {
		$this->cache->setMaxSize( $maxKeys );
	}

	/**
	 * Get cache size
	 * @return int
	 */
	public function getSize() {
		return $this->cache->getMaxSize();
	}
}
