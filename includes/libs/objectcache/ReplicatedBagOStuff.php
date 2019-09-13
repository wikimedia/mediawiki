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
use Wikimedia\ObjectFactory;

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
	const MAX_WRITE_DELAY = 5;

	/**
	 * Constructor. Parameters are:
	 *   - writeFactory: ObjectFactory::getObjectFromSpec array yielding BagOStuff.
	 *      This object will be used for writes (e.g. the master DB).
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

	public function setDebug( $enabled ) {
		parent::setDebug( $enabled );
		$this->writeStore->setDebug( $enabled );
		$this->readStore->setDebug( $enabled );
	}

	public function get( $key, $flags = 0 ) {
		return (
			$this->hadRecentSessionWrite( [ $key ] ) ||
			$this->fieldHasFlags( $flags, self::READ_LATEST )
		)
			? $this->writeStore->get( $key, $flags )
			: $this->readStore->get( $key, $flags );
	}

	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->remarkRecentSessionWrite( [ $key ] );

		return $this->writeStore->set( $key, $value, $exptime, $flags );
	}

	public function delete( $key, $flags = 0 ) {
		$this->remarkRecentSessionWrite( [ $key ] );

		return $this->writeStore->delete( $key, $flags );
	}

	public function add( $key, $value, $exptime = 0, $flags = 0 ) {
		$this->remarkRecentSessionWrite( [ $key ] );

		return $this->writeStore->add( $key, $value, $exptime, $flags );
	}

	public function merge( $key, callable $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		$this->remarkRecentSessionWrite( [ $key ] );

		return $this->writeStore->merge( $key, $callback, $exptime, $attempts, $flags );
	}

	public function changeTTL( $key, $exptime = 0, $flags = 0 ) {
		$this->remarkRecentSessionWrite( [ $key ] );

		return $this->writeStore->changeTTL( $key, $exptime, $flags );
	}

	public function lock( $key, $timeout = 6, $expiry = 6, $rclass = '' ) {
		return $this->writeStore->lock( $key, $timeout, $expiry, $rclass );
	}

	public function unlock( $key ) {
		return $this->writeStore->unlock( $key );
	}

	public function deleteObjectsExpiringBefore(
		$timestamp,
		callable $progress = null,
		$limit = INF
	) {
		return $this->writeStore->deleteObjectsExpiringBefore( $timestamp, $progress, $limit );
	}

	public function getMulti( array $keys, $flags = 0 ) {
		return (
			$this->hadRecentSessionWrite( $keys ) ||
			$this->fieldHasFlags( $flags, self::READ_LATEST )
		)
			? $this->writeStore->getMulti( $keys, $flags )
			: $this->readStore->getMulti( $keys, $flags );
	}

	public function setMulti( array $data, $exptime = 0, $flags = 0 ) {
		$this->remarkRecentSessionWrite( array_keys( $data ) );

		return $this->writeStore->setMulti( $data, $exptime, $flags );
	}

	public function deleteMulti( array $keys, $flags = 0 ) {
		$this->remarkRecentSessionWrite( $keys );

		return $this->writeStore->deleteMulti( $keys, $flags );
	}

	public function changeTTLMulti( array $keys, $exptime, $flags = 0 ) {
		$this->remarkRecentSessionWrite( $keys );

		return $this->writeStore->changeTTLMulti( $keys, $exptime, $flags );
	}

	public function incr( $key, $value = 1, $flags = 0 ) {
		$this->remarkRecentSessionWrite( [ $key ] );

		return $this->writeStore->incr( $key, $value, $flags );
	}

	public function decr( $key, $value = 1, $flags = 0 ) {
		$this->remarkRecentSessionWrite( [ $key ] );

		return $this->writeStore->decr( $key, $value, $flags );
	}

	public function incrWithInit( $key, $exptime, $value = 1, $init = null, $flags = 0 ) {
		$this->remarkRecentSessionWrite( [ $key ] );

		return $this->writeStore->incrWithInit( $key, $exptime, $value, $init, $flags );
	}

	public function getLastError() {
		return ( $this->writeStore->getLastError() !== self::ERR_NONE )
			? $this->writeStore->getLastError()
			: $this->readStore->getLastError();
	}

	public function clearLastError() {
		$this->writeStore->clearLastError();
		$this->readStore->clearLastError();
	}

	public function makeKeyInternal( $keyspace, $args ) {
		return $this->writeStore->makeKeyInternal( ...func_get_args() );
	}

	public function makeKey( $class, ...$components ) {
		return $this->writeStore->makeKey( ...func_get_args() );
	}

	public function makeGlobalKey( $class, ...$components ) {
		return $this->writeStore->makeGlobalKey( ...func_get_args() );
	}

	public function addBusyCallback( callable $workCallback ) {
		$this->writeStore->addBusyCallback( $workCallback );
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
			unset( $this->lastKeyWrites[$key] ); // move to the end
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
