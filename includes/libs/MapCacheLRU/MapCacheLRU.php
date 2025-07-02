<?php
/**
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

namespace Wikimedia\MapCacheLRU;

use InvalidArgumentException;
use UnexpectedValueException;
use Wikimedia\LightweightObjectStore\ExpirationAwareness;

/**
 * Store key-value entries in a size-limited in-memory LRU cache.
 *
 * The last-modification timestamp of entries is internally tracked so that callers can
 * specify the maximum acceptable age of entries in calls to the has() method. As a convenience,
 * the hasField(), getField(), and setField() methods can be used for entries that are field/value
 * maps themselves; such fields will have their own internally tracked last-modification timestamp.
 *
 * @ingroup Cache
 * @since 1.23
 */
class MapCacheLRU implements ExpirationAwareness {
	/** @var array Map of (key => value) */
	private $cache = [];
	/** @var array Map of (key => (UNIX timestamp, (field => UNIX timestamp))) */
	private $timestamps = [];
	/** @var float Default entry timestamp if not specified */
	private $epoch;

	/** @var int Max number of entries */
	private $maxCacheKeys;

	/** @var float|null */
	private $wallClockOverride;

	/** @var float */
	private const RANK_TOP = 1.0;

	/** @var int Array key that holds the entry's main timestamp (flat key use) */
	private const SIMPLE = 0;
	/** @var int Array key that holds the entry's field timestamps (nested key use) */
	private const FIELDS = 1;

	/**
	 * @param int $maxKeys Maximum number of entries allowed (min 1)
	 */
	public function __construct( int $maxKeys ) {
		if ( $maxKeys <= 0 ) {
			throw new InvalidArgumentException( '$maxKeys must be above zero' );
		}

		$this->maxCacheKeys = $maxKeys;
		// Use the current time as the default "as of" timestamp of entries
		$this->epoch = $this->getCurrentTime();
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
	 * @param string|int $key
	 * @param mixed $value
	 * @param float $rank Bottom fraction of the list where keys start off [default: 1.0]
	 * @return void
	 */
	public function set( $key, $value, $rank = self::RANK_TOP ) {
		if ( $this->has( $key ) ) {
			$this->ping( $key );
		} elseif ( count( $this->cache ) >= $this->maxCacheKeys ) {
			$evictKey = array_key_first( $this->cache );
			unset( $this->cache[$evictKey] );
			unset( $this->timestamps[$evictKey] );
		}

		if ( $rank < 1.0 && $rank > 0 ) {
			$offset = intval( $rank * count( $this->cache ) );
			$this->cache = array_slice( $this->cache, 0, $offset, true )
				+ [ $key => $value ]
				+ array_slice( $this->cache, $offset, null, true );
		} else {
			$this->cache[$key] = $value;
		}

		$this->timestamps[$key] = [
			self::SIMPLE => $this->getCurrentTime(),
			self::FIELDS => []
		];
	}

	/**
	 * Check if a key exists
	 *
	 * @param string|int $key
	 * @param float $maxAge Ignore items older than this many seconds [default: INF]
	 * @return bool
	 * @since 1.32 Added $maxAge
	 */
	public function has( $key, $maxAge = INF ) {
		if ( !is_int( $key ) && !is_string( $key ) ) {
			throw new UnexpectedValueException(
				__METHOD__ . ': invalid key; must be string or integer.' );
		}
		return array_key_exists( $key, $this->cache )
			&& (
				// Optimization: Avoid expensive getAge/getCurrentTime for common case (T275673)
				$maxAge === INF
				|| $maxAge <= 0
				|| $this->getKeyAge( $key ) <= $maxAge
			);
	}

	/**
	 * Get the value for a key.
	 * This returns null if the key is not set.
	 * If the item is already set, it will be pushed to the top of the cache.
	 *
	 * @param string|int $key
	 * @param float $maxAge Ignore items older than this many seconds [default: INF]
	 * @param mixed|null $default Value to return if no key is found [default: null]
	 * @return mixed Returns $default if the key was not found or is older than $maxAge
	 * @since 1.32 Added $maxAge
	 * @since 1.34 Added $default
	 *
	 * Although sometimes this can be tainted, taint-check doesn't distinguish separate instances
	 * of MapCacheLRU, so assume untainted to cut down on false positives. See T272134.
	 * @return-taint none
	 */
	public function get( $key, $maxAge = INF, $default = null ) {
		if ( !$this->has( $key, $maxAge ) ) {
			return $default;
		}

		$this->ping( $key );

		return $this->cache[$key];
	}

	/**
	 * @param string|int $key
	 * @param string|int $field
	 * @param mixed $value
	 * @param float $initRank
	 */
	public function setField( $key, $field, $value, $initRank = self::RANK_TOP ) {
		if ( $this->has( $key ) ) {
			$this->ping( $key );

			if ( !is_array( $this->cache[$key] ) ) {
				$type = get_debug_type( $this->cache[$key] );
				throw new UnexpectedValueException( "Cannot add field to non-array value ('$key' is $type)" );
			}
		} else {
			$this->set( $key, [], $initRank );
		}

		if ( !is_string( $field ) && !is_int( $field ) ) {
			throw new UnexpectedValueException(
				__METHOD__ . ": invalid field for '$key'; must be string or integer." );
		}

		$this->cache[$key][$field] = $value;
		$this->timestamps[$key][self::FIELDS][$field] = $this->getCurrentTime();
	}

	/**
	 * @param string|int $key
	 * @param string|int $field
	 * @param float $maxAge Ignore items older than this many seconds [default: INF]
	 * @return bool
	 * @since 1.32 Added $maxAge
	 */
	public function hasField( $key, $field, $maxAge = INF ) {
		$value = $this->get( $key );

		if ( !is_int( $field ) && !is_string( $field ) ) {
			throw new UnexpectedValueException(
				__METHOD__ . ": invalid field for '$key'; must be string or integer." );
		}
		return is_array( $value )
			&& array_key_exists( $field, $value )
			&& (
				$maxAge === INF
				|| $maxAge <= 0
				|| $this->getKeyFieldAge( $key, $field ) <= $maxAge
			);
	}

	/**
	 * @param string|int $key
	 * @param string|int $field
	 * @param float $maxAge Ignore items older than this many seconds [default: INF]
	 * @return mixed Returns null if the key was not found or is older than $maxAge
	 * @since 1.32 Added $maxAge
	 */
	public function getField( $key, $field, $maxAge = INF ) {
		if ( !$this->hasField( $key, $field, $maxAge ) ) {
			return null;
		}

		return $this->cache[$key][$field];
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
	 * @param string|int $key
	 * @param callable $callback Callback that will produce the value
	 * @param float $rank Bottom fraction of the list where keys start off [default: 1.0]
	 * @param float $maxAge Ignore items older than this many seconds [default: INF]
	 * @return mixed The cached value if found or the result of $callback otherwise
	 * @since 1.32 Added $maxAge
	 */
	public function getWithSetCallback(
		$key, callable $callback, $rank = self::RANK_TOP, $maxAge = INF
	) {
		if ( $this->has( $key, $maxAge ) ) {
			$value = $this->get( $key );
		} else {
			$value = $callback();
			if ( $value !== false ) {
				$this->set( $key, $value, $rank );
			}
		}

		return $value;
	}

	/**
	 * Format a cache key string
	 *
	 * @since 1.41
	 * @param string|int ...$components Key components
	 * @return string
	 */
	public function makeKey( ...$components ) {
		// Based on BagOStuff::makeKeyInternal, except without a required
		// $keygroup prefix. While MapCacheLRU can and is used as cache for
		// multiple groups of keys, it is equally common for the instance itself
		// to represent a single group, and thus have keys where the first component
		// can directly be a user-controlled variable.
		$key = '';
		foreach ( $components as $i => $component ) {
			if ( $i > 0 ) {
				$key .= ':';
			}
			$key .= strtr( $component, [ '%' => '%25', ':' => '%3A' ] );
		}
		return $key;
	}

	/**
	 * Clear one or several cache entries, or all cache entries
	 *
	 * @param string|int|array|null $keys
	 * @return void
	 */
	public function clear( $keys = null ) {
		if ( func_num_args() == 0 ) {
			$this->cache = [];
			$this->timestamps = [];
		} else {
			foreach ( (array)$keys as $key ) {
				unset( $this->cache[$key] );
				unset( $this->timestamps[$key] );
			}
		}
	}

	/**
	 * Get the maximum number of keys allowed
	 *
	 * @return int
	 * @since 1.32
	 */
	public function getMaxSize() {
		return $this->maxCacheKeys;
	}

	/**
	 * Resize the maximum number of cache entries, removing older entries as needed
	 *
	 * @param int $maxKeys Maximum number of entries allowed (min 1)
	 * @return void
	 * @since 1.32
	 */
	public function setMaxSize( int $maxKeys ) {
		if ( $maxKeys <= 0 ) {
			throw new InvalidArgumentException( '$maxKeys must be above zero' );
		}

		$this->maxCacheKeys = $maxKeys;
		while ( count( $this->cache ) > $this->maxCacheKeys ) {
			$evictKey = array_key_first( $this->cache );
			unset( $this->cache[$evictKey] );
			unset( $this->timestamps[$evictKey] );
		}
	}

	/**
	 * Push an entry to the top of the cache
	 *
	 * @param string|int $key
	 */
	private function ping( $key ) {
		$item = $this->cache[$key];
		unset( $this->cache[$key] );
		$this->cache[$key] = $item;
	}

	/**
	 * @param string|int $key
	 * @return float UNIX timestamp; the age of the given entry
	 */
	private function getKeyAge( $key ) {
		$mtime = $this->timestamps[$key][self::SIMPLE] ?? $this->epoch;

		return ( $this->getCurrentTime() - $mtime );
	}

	/**
	 * @param string|int $key
	 * @param string|int|null $field
	 * @return float UNIX timestamp; the age of the given entry field
	 */
	private function getKeyFieldAge( $key, $field ) {
		$mtime = $this->timestamps[$key][self::FIELDS][$field] ?? $this->epoch;

		return ( $this->getCurrentTime() - $mtime );
	}

	public function __serialize() {
		return [
			'entries' => $this->cache,
			'timestamps' => $this->timestamps,
			'maxCacheKeys' => $this->maxCacheKeys,
		];
	}

	public function __unserialize( $data ) {
		$this->cache = $data['entries'] ?? [];
		$this->timestamps = $data['timestamps'] ?? [];
		// Fallback needed for serializations prior to T218511
		$this->maxCacheKeys = $data['maxCacheKeys'] ?? ( count( $this->cache ) + 1 );
		$this->epoch = $this->getCurrentTime();
	}

	/**
	 * @return float UNIX timestamp
	 * @codeCoverageIgnore
	 */
	protected function getCurrentTime() {
		return $this->wallClockOverride ?: microtime( true );
	}

	/**
	 * @param float|null &$time Mock UNIX timestamp for testing
	 * @codeCoverageIgnore
	 */
	public function setMockTime( &$time ) {
		$this->wallClockOverride =& $time;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( MapCacheLRU::class, 'MapCacheLRU' );
