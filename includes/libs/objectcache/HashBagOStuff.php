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

namespace Wikimedia\ObjectCache;

use InvalidArgumentException;

/**
 * Store data in a memory for the current request/process only.
 *
 * This keeps values in a simple associative array.
 * Data will not persist and is not shared with other requests
 * on the same server.
 *
 * @newable
 * @ingroup Cache
 */
class HashBagOStuff extends MediumSpecificBagOStuff {
	/** @var mixed[] */
	protected $bag = [];
	/** @var int|double Max entries allowed, INF for unlimited */
	protected $maxCacheKeys;

	/** @var string CAS token prefix for this instance */
	private $token;

	/** @var int CAS token counter */
	private static $casCounter = 0;

	public const KEY_VAL = 0;
	public const KEY_EXP = 1;
	public const KEY_CAS = 2;

	/**
	 * @stable to call
	 *
	 * @param array $params Additional parameters include:
	 *   - maxKeys : only allow this many keys (using oldest-first eviction)
	 *
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-param array{logger?:\Psr\Log\LoggerInterface,asyncHandler?:callable,keyspace?:string,reportDupes?:bool,segmentationSize?:int,segmentedValueMaxSize?:int,maxKeys?:int} $params
	 */
	public function __construct( $params = [] ) {
		$params['segmentationSize'] ??= INF;
		parent::__construct( $params );

		$this->token = microtime( true ) . ':' . mt_rand();
		$maxKeys = $params['maxKeys'] ?? INF;
		if ( $maxKeys !== INF && ( !is_int( $maxKeys ) || $maxKeys <= 0 ) ) {
			throw new InvalidArgumentException( '$maxKeys parameter must be above zero' );
		}
		$this->maxCacheKeys = $maxKeys;

		$this->attrMap[self::ATTR_DURABILITY] = self::QOS_DURABILITY_SCRIPT;
	}

	/** @inheritDoc */
	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$getToken = ( $casToken === self::PASS_BY_REF );
		$casToken = null;

		if ( !$this->hasKey( $key ) || $this->expire( $key ) ) {
			return false;
		}

		// Refresh key position for maxCacheKeys eviction
		$temp = $this->bag[$key];
		unset( $this->bag[$key] );
		$this->bag[$key] = $temp;

		$value = $this->bag[$key][self::KEY_VAL];
		if ( $getToken && $value !== false ) {
			$casToken = $this->bag[$key][self::KEY_CAS];
		}

		return $value;
	}

	/** @inheritDoc */
	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		// Refresh key position for maxCacheKeys eviction
		unset( $this->bag[$key] );
		$this->bag[$key] = [
			self::KEY_VAL => $value,
			self::KEY_EXP => $this->getExpirationAsTimestamp( $exptime ),
			self::KEY_CAS => $this->token . ':' . ++self::$casCounter
		];

		if ( count( $this->bag ) > $this->maxCacheKeys ) {
			$evictKey = array_key_first( $this->bag );
			unset( $this->bag[$evictKey] );
		}

		return true;
	}

	/** @inheritDoc */
	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		if ( $this->hasKey( $key ) && !$this->expire( $key ) ) {
			// key already set
			return false;
		}

		return $this->doSet( $key, $value, $exptime, $flags );
	}

	/** @inheritDoc */
	protected function doDelete( $key, $flags = 0 ) {
		unset( $this->bag[$key] );

		return true;
	}

	/** @inheritDoc */
	protected function doIncrWithInit( $key, $exptime, $step, $init, $flags ) {
		$curValue = $this->doGet( $key );
		if ( $curValue === false ) {
			$newValue = $this->doSet( $key, $init, $exptime ) ? $init : false;
		} elseif ( $this->isInteger( $curValue ) ) {
			$newValue = max( $curValue + $step, 0 );
			$this->bag[$key][self::KEY_VAL] = $newValue;
		} else {
			$newValue = false;
		}

		return $newValue;
	}

	/**
	 * Clear all values in cache
	 */
	public function clear() {
		$this->bag = [];
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	protected function expire( $key ) {
		$et = $this->bag[$key][self::KEY_EXP];
		if ( $et == self::TTL_INDEFINITE || $et > $this->getCurrentTime() ) {
			return false;
		}

		$this->doDelete( $key );

		return true;
	}

	/**
	 * Does this bag have a non-null value for the given key?
	 *
	 * @param string $key
	 *
	 * @return bool
	 * @since 1.27
	 */
	public function hasKey( $key ) {
		return isset( $this->bag[$key] );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( HashBagOStuff::class, 'HashBagOStuff' );
