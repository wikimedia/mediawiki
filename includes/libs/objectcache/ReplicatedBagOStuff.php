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
 * @ingroup Cache
 */
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * A cache class that directs writes to one set of servers and reads to
 * another. This assumes that the servers used for reads are setup to replica DB
 * those that writes go to. This can easily be used with redis for example.
 *
 * In the WAN scenario (e.g. multi-datacenter case), this is useful when
 * writes are rare or they usually take place in the primary datacenter.
 *
 * @ingroup Cache
 * @since 1.26
 */
class ReplicatedBagOStuff extends BagOStuff {
	/** @var BagOStuff */
	private $writeStore;
	/** @var BagOStuff */
	private $readStore;

	/** @var int Seconds to read from the master source for a key after writing to it */
	private $consistencyWindow;
	/** @var float[] Map of (key => UNIX timestamp) */
	private $lastKeyWrites = [];

	/** @var int Max expected delay (in seconds) for writes to reach replicas */
	private const MAX_WRITE_DELAY = 5;

	/**
	 * Constructor. Parameters are:
	 *   - writeFactory: ObjectFactory::getObjectFromSpec array yielding BagOStuff.
	 *      This object will be used for writes (e.g. the primary DB).
	 *   - readFactory: ObjectFactory::getObjectFromSpec array yielding BagOStuff.
	 *      This object will be used for reads (e.g. a replica DB).
	 *   - sessionConsistencyWindow: Seconds to read from the master source for a key
	 *      after writing to it. [Default: ReplicatedBagOStuff::MAX_WRITE_DELAY]
	 *
	 * @param array $params
	 * @throws InvalidArgumentException
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		if ( !isset( $params['writeFactory'] ) ) {
			throw new InvalidArgumentException(
				__METHOD__ . ': the "writeFactory" parameter is required' );
		} elseif ( !isset( $params['readFactory'] ) ) {
			throw new InvalidArgumentException(
				__METHOD__ . ': the "readFactory" parameter is required' );
		}

		$this->consistencyWindow = $params['sessionConsistencyWindow'] ?? self::MAX_WRITE_DELAY;
		$this->writeStore = ( $params['writeFactory'] instanceof BagOStuff )
			? $params['writeFactory']
			: ObjectFactory::getObjectFromSpec( $params['writeFactory'] );
		$this->readStore = ( $params['readFactory'] instanceof BagOStuff )
			? $params['readFactory']
			: ObjectFactory::getObjectFromSpec( $params['readFactory'] );
		$this->attrMap = $this->mergeFlagMaps( [ $this->readStore, $this->writeStore ] );
	}

	public function get( $key, $flags = 0 ) {
		$store = (
			$this->hadRecentSessionWrite( [ $key ] ) ||
			$this->fieldHasFlags( $flags, self::READ_LATEST )
		)
			// Try to maintain session consistency and respect READ_LATEST
			? $this->writeStore
			// Otherwise, just use the default "read" store
			: $this->readStore;

		return $store->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->remarkRecentSessionWrite( [ $key ] );

		return $this->writeStore->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	public function delete( $key, $flags = 0 ) {
		$this->remarkRecentSessionWrite( [ $key ] );

		return $this->writeStore->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	public function add( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->remarkRecentSessionWrite( [ $key ] );

		return $this->writeStore->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	public function merge( $key, callable $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		$this->remarkRecentSessionWrite( [ $key ] );

		return $this->writeStore->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	public function changeTTL( $key, $exptime = 0, $flags = 0 ) {
		$this->remarkRecentSessionWrite( [ $key ] );

		return $this->writeStore->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	public function lock( $key, $timeout = 6, $exptime = 6, $rclass = '' ) {
		return $this->writeStore->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	public function unlock( $key ) {
		return $this->writeStore->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	public function deleteObjectsExpiringBefore(
		$timestamp,
		callable $progress = null,
		$limit = INF,
		string $tag = null
	) {
		return $this->writeStore->proxyCall(
			__FUNCTION__,
			self::ARG0_NONKEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	public function getMulti( array $keys, $flags = 0 ) {
		$store = (
			$this->hadRecentSessionWrite( $keys ) ||
			$this->fieldHasFlags( $flags, self::READ_LATEST )
		)
			// Try to maintain session consistency and respect READ_LATEST
			? $this->writeStore
			// Otherwise, just use the default "read" store
			: $this->readStore;

		return $store->proxyCall(
			__FUNCTION__,
			self::ARG0_KEYARR,
			self::RES_KEYMAP,
			func_get_args(),
			$this
		);
	}

	public function setMulti( array $valueByKey, $exptime = 0, $flags = 0 ) {
		$this->remarkRecentSessionWrite( array_keys( $valueByKey ) );

		return $this->writeStore->proxyCall(
			__FUNCTION__,
			self::ARG0_KEYMAP,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	public function deleteMulti( array $keys, $flags = 0 ) {
		$this->remarkRecentSessionWrite( $keys );

		return $this->writeStore->proxyCall(
			__FUNCTION__,
			self::ARG0_KEYARR,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	public function changeTTLMulti( array $keys, $exptime, $flags = 0 ) {
		$this->remarkRecentSessionWrite( $keys );

		return $this->writeStore->proxyCall(
			__FUNCTION__,
			self::ARG0_KEYARR,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	public function incrWithInit( $key, $exptime, $step = 1, $init = null, $flags = 0 ) {
		$this->remarkRecentSessionWrite( [ $key ] );

		return $this->writeStore->proxyCall(
			__FUNCTION__,
			self::ARG0_KEY,
			self::RES_NONKEY,
			func_get_args(),
			$this
		);
	}

	public function setMockTime( &$time ) {
		parent::setMockTime( $time );
		$this->writeStore->setMockTime( $time );
		$this->readStore->setMockTime( $time );
	}

	/**
	 * @param string[] $keys
	 * @return bool
	 */
	private function hadRecentSessionWrite( array $keys ) {
		$now = $this->getCurrentTime();
		foreach ( $keys as $key ) {
			$ts = $this->lastKeyWrites[$key] ?? 0;
			if ( $ts && ( $now - $ts ) <= $this->consistencyWindow ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string[] $keys
	 */
	private function remarkRecentSessionWrite( array $keys ) {
		$now = $this->getCurrentTime();
		foreach ( $keys as $key ) {
			// move to the end
			unset( $this->lastKeyWrites[$key] );
			$this->lastKeyWrites[$key] = $now;
		}
		// Prune out the map if the first key is obsolete
		if ( ( $now - reset( $this->lastKeyWrites ) ) > $this->consistencyWindow ) {
			$this->lastKeyWrites = array_filter(
				$this->lastKeyWrites,
				function ( $timestamp ) use ( $now ) {
					return ( ( $now - $timestamp ) <= $this->consistencyWindow );
				}
			);
		}
	}
}
