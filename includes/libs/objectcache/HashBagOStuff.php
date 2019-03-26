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
 * Simple store for keeping values in an associative array for the current process.
 *
 * Data will not persist and is not shared with other processes.
 *
 * @ingroup Cache
 */
class HashBagOStuff extends BagOStuff {
	/** @var mixed[] */
	protected $bag = [];
	/** @var int Max entries allowed */
	protected $maxCacheKeys;

	/** @var string CAS token prefix for this instance */
	private $token;

	/** @var int CAS token counter */
	private static $casCounter = 0;

	const KEY_VAL = 0;
	const KEY_EXP = 1;
	const KEY_CAS = 2;

	/**
	 * @param array $params Additional parameters include:
	 *   - maxKeys : only allow this many keys (using oldest-first eviction)
	 */
	function __construct( $params = [] ) {
		parent::__construct( $params );

		$this->token = microtime( true ) . ':' . mt_rand();
		$this->maxCacheKeys = $params['maxKeys'] ?? INF;
		if ( $this->maxCacheKeys <= 0 ) {
			throw new InvalidArgumentException( '$maxKeys parameter must be above zero' );
		}
	}

	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$casToken = null;

		if ( !$this->hasKey( $key ) || $this->expire( $key ) ) {
			return false;
		}

		// Refresh key position for maxCacheKeys eviction
		$temp = $this->bag[$key];
		unset( $this->bag[$key] );
		$this->bag[$key] = $temp;

		$casToken = $this->bag[$key][self::KEY_CAS];

		return $this->bag[$key][self::KEY_VAL];
	}

	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		// Refresh key position for maxCacheKeys eviction
		unset( $this->bag[$key] );
		$this->bag[$key] = [
			self::KEY_VAL => $value,
			self::KEY_EXP => $this->convertToExpiry( $exptime ),
			self::KEY_CAS => $this->token . ':' . ++self::$casCounter
		];

		if ( count( $this->bag ) > $this->maxCacheKeys ) {
			reset( $this->bag );
			$evictKey = key( $this->bag );
			unset( $this->bag[$evictKey] );
		}

		return true;
	}

	public function add( $key, $value, $exptime = 0, $flags = 0 ) {
		if ( $this->get( $key ) === false ) {
			return $this->set( $key, $value, $exptime, $flags );
		}

		return false; // key already set
	}

	public function delete( $key, $flags = 0 ) {
		unset( $this->bag[$key] );

		return true;
	}

	public function incr( $key, $value = 1 ) {
		$n = $this->get( $key );
		if ( $this->isInteger( $n ) ) {
			$n = max( $n + intval( $value ), 0 );
			$this->bag[$key][self::KEY_VAL] = $n;

			return $n;
		}

		return false;
	}

	/**
	 * Clear all values in cache
	 */
	public function clear() {
		$this->bag = [];
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	protected function expire( $key ) {
		$et = $this->bag[$key][self::KEY_EXP];
		if ( $et == self::TTL_INDEFINITE || $et > $this->getCurrentTime() ) {
			return false;
		}

		$this->delete( $key );

		return true;
	}

	/**
	 * Does this bag have a non-null value for the given key?
	 *
	 * @param string $key
	 * @return bool
	 * @since 1.27
	 */
	protected function hasKey( $key ) {
		return isset( $this->bag[$key] );
	}
}
