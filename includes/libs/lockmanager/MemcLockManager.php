<?php
/**
 * Version of LockManager based on using memcached servers.
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
 * @ingroup LockManager
 */
use Wikimedia\WaitConditionLoop;

/**
 * Manage locks using memcached servers.
 *
 * Version of LockManager based on using memcached servers.
 * This is meant for multi-wiki systems that may share files.
 * All locks are non-blocking, which avoids deadlocks.
 *
 * All lock requests for a resource, identified by a hash string, will map to one
 * bucket. Each bucket maps to one or several peer servers, each running memcached.
 * A majority of peers must agree for a lock to be acquired.
 *
 * @ingroup LockManager
 * @since 1.20
 */
class MemcLockManager extends QuorumLockManager {
	/** @var array Mapping of lock types to the type actually used */
	protected $lockTypeMap = [
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	];

	/** @var MemcachedBagOStuff[] Map of (server name => MemcachedBagOStuff) */
	protected $cacheServers = [];
	/** @var MapCacheLRU Server status cache */
	protected $statusCache;

	/**
	 * Construct a new instance from configuration.
	 *
	 * @param array $config Parameters include:
	 *   - lockServers  : Associative array of server names to "<IP>:<port>" strings.
	 *   - srvsByBucket : Array of 1-16 consecutive integer keys, starting from 0,
	 *                    each having an odd-numbered list of server names (peers) as values.
	 *   - memcConfig   : Configuration array for MemcachedBagOStuff::construct() with an
	 *                    additional 'class' parameter specifying which MemcachedBagOStuff
	 *                    subclass to use. The server names will be injected. [optional]
	 * @throws Exception
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		// Sanitize srvsByBucket config to prevent PHP errors
		$this->srvsByBucket = array_filter( $config['srvsByBucket'], 'is_array' );
		$this->srvsByBucket = array_values( $this->srvsByBucket ); // consecutive

		$memcConfig = $config['memcConfig'] ?? [];
		$memcConfig += [ 'class' => MemcachedPhpBagOStuff::class ]; // default

		$class = $memcConfig['class'];
		if ( !is_subclass_of( $class, MemcachedBagOStuff::class ) ) {
			throw new InvalidArgumentException( "$class is not of type MemcachedBagOStuff." );
		}

		foreach ( $config['lockServers'] as $name => $address ) {
			$params = [ 'servers' => [ $address ] ] + $memcConfig;
			$this->cacheServers[$name] = new $class( $params );
		}

		$this->statusCache = new MapCacheLRU( 100 );
	}

	protected function getLocksOnServer( $lockSrv, array $pathsByType ) {
		$status = StatusValue::newGood();

		$memc = $this->getCache( $lockSrv );
		// List of affected paths
		$paths = array_merge( ...array_values( $pathsByType ) );
		$paths = array_unique( $paths );
		// List of affected lock record keys
		$keys = array_map( [ $this, 'recordKeyForPath' ], $paths );

		// Lock all of the active lock record keys...
		if ( !$this->acquireMutexes( $memc, $keys ) ) {
			foreach ( $paths as $path ) {
				$status->fatal( 'lockmanager-fail-acquirelock', $path );
			}

			return $status;
		}

		// Fetch all the existing lock records...
		$lockRecords = $memc->getMulti( $keys );

		$now = time();
		// Check if the requested locks conflict with existing ones...
		foreach ( $pathsByType as $type => $paths ) {
			foreach ( $paths as $path ) {
				$locksKey = $this->recordKeyForPath( $path );
				$locksHeld = isset( $lockRecords[$locksKey] )
					? self::sanitizeLockArray( $lockRecords[$locksKey] )
					: self::newLockArray(); // init
				foreach ( $locksHeld[self::LOCK_EX] as $session => $expiry ) {
					if ( $expiry < $now ) { // stale?
						unset( $locksHeld[self::LOCK_EX][$session] );
					} elseif ( $session !== $this->session ) {
						$status->fatal( 'lockmanager-fail-acquirelock', $path );
					}
				}
				if ( $type === self::LOCK_EX ) {
					foreach ( $locksHeld[self::LOCK_SH] as $session => $expiry ) {
						if ( $expiry < $now ) { // stale?
							unset( $locksHeld[self::LOCK_SH][$session] );
						} elseif ( $session !== $this->session ) {
							$status->fatal( 'lockmanager-fail-acquirelock', $path );
						}
					}
				}
				if ( $status->isOK() ) {
					// Register the session in the lock record array
					$locksHeld[$type][$this->session] = $now + $this->lockTTL;
					// We will update this record if none of the other locks conflict
					$lockRecords[$locksKey] = $locksHeld;
				}
			}
		}

		// If there were no lock conflicts, update all the lock records...
		if ( $status->isOK() ) {
			foreach ( $paths as $path ) {
				$locksKey = $this->recordKeyForPath( $path );
				$locksHeld = $lockRecords[$locksKey];
				$ok = $memc->set( $locksKey, $locksHeld, self::MAX_LOCK_TTL );
				if ( !$ok ) {
					$status->fatal( 'lockmanager-fail-acquirelock', $path );
				} else {
					$this->logger->debug( __METHOD__ . ": acquired lock on key $locksKey.\n" );
				}
			}
		}

		// Unlock all of the active lock record keys...
		$this->releaseMutexes( $memc, $keys );

		return $status;
	}

	protected function freeLocksOnServer( $lockSrv, array $pathsByType ) {
		$status = StatusValue::newGood();

		$memc = $this->getCache( $lockSrv );
		// List of affected paths
		$paths = array_merge( ...array_values( $pathsByType ) );
		$paths = array_unique( $paths );
		// List of affected lock record keys
		$keys = array_map( [ $this, 'recordKeyForPath' ], $paths );

		// Lock all of the active lock record keys...
		if ( !$this->acquireMutexes( $memc, $keys ) ) {
			foreach ( $paths as $path ) {
				$status->fatal( 'lockmanager-fail-releaselock', $path );
			}

			return $status;
		}

		// Fetch all the existing lock records...
		$lockRecords = $memc->getMulti( $keys );

		// Remove the requested locks from all records...
		foreach ( $pathsByType as $type => $paths ) {
			foreach ( $paths as $path ) {
				$locksKey = $this->recordKeyForPath( $path ); // lock record
				if ( !isset( $lockRecords[$locksKey] ) ) {
					$status->warning( 'lockmanager-fail-releaselock', $path );
					continue; // nothing to do
				}
				$locksHeld = $this->sanitizeLockArray( $lockRecords[$locksKey] );
				if ( isset( $locksHeld[$type][$this->session] ) ) {
					unset( $locksHeld[$type][$this->session] ); // unregister this session
					$lockRecords[$locksKey] = $locksHeld;
				} else {
					$status->warning( 'lockmanager-fail-releaselock', $path );
				}
			}
		}

		// Persist the new lock record values...
		foreach ( $paths as $path ) {
			$locksKey = $this->recordKeyForPath( $path );
			if ( !isset( $lockRecords[$locksKey] ) ) {
				continue; // nothing to do
			}
			$locksHeld = $lockRecords[$locksKey];
			if ( $locksHeld === $this->newLockArray() ) {
				$ok = $memc->delete( $locksKey );
			} else {
				$ok = $memc->set( $locksKey, $locksHeld, self::MAX_LOCK_TTL );
			}
			if ( $ok ) {
				$this->logger->debug( __METHOD__ . ": released lock on key $locksKey.\n" );
			} else {
				$status->fatal( 'lockmanager-fail-releaselock', $path );
			}
		}

		// Unlock all of the active lock record keys...
		$this->releaseMutexes( $memc, $keys );

		return $status;
	}

	/**
	 * @see QuorumLockManager::releaseAllLocks()
	 * @return StatusValue
	 */
	protected function releaseAllLocks() {
		return StatusValue::newGood(); // not supported
	}

	/**
	 * @see QuorumLockManager::isServerUp()
	 * @param string $lockSrv
	 * @return bool
	 */
	protected function isServerUp( $lockSrv ) {
		return (bool)$this->getCache( $lockSrv );
	}

	/**
	 * Get the MemcachedBagOStuff object for a $lockSrv
	 *
	 * @param string $lockSrv Server name
	 * @return MemcachedBagOStuff|null
	 */
	protected function getCache( $lockSrv ) {
		if ( !isset( $this->cacheServers[$lockSrv] ) ) {
			throw new InvalidArgumentException( "Invalid cache server '$lockSrv'." );
		}

		$online = $this->statusCache->get( "online:$lockSrv", 30 );
		if ( $online === null ) {
			$online = $this->cacheServers[$lockSrv]->set( __CLASS__ . ':ping', 1, 1 );
			if ( !$online ) { // server down?
				$this->logger->warning( __METHOD__ . ": Could not contact $lockSrv." );
			}
			$this->statusCache->set( "online:$lockSrv", (int)$online );
		}

		return $online ? $this->cacheServers[$lockSrv] : null;
	}

	/**
	 * @param string $path
	 * @return string
	 */
	protected function recordKeyForPath( $path ) {
		return implode( ':', [ __CLASS__, 'locks', $this->sha1Base36Absolute( $path ) ] );
	}

	/**
	 * @return array An empty lock structure for a key
	 */
	protected function newLockArray() {
		return [ self::LOCK_SH => [], self::LOCK_EX => [] ];
	}

	/**
	 * @param array $a
	 * @return array An empty lock structure for a key
	 */
	protected function sanitizeLockArray( $a ) {
		if ( is_array( $a ) && isset( $a[self::LOCK_EX] ) && isset( $a[self::LOCK_SH] ) ) {
			return $a;
		}

		$this->logger->error( __METHOD__ . ": reset invalid lock array." );

		return $this->newLockArray();
	}

	/**
	 * @param MemcachedBagOStuff $memc
	 * @param array $keys List of keys to acquire
	 * @return bool
	 */
	protected function acquireMutexes( MemcachedBagOStuff $memc, array $keys ) {
		$lockedKeys = [];

		// Acquire the keys in lexicographical order, to avoid deadlock problems.
		// If P1 is waiting to acquire a key P2 has, P2 can't also be waiting for a key P1 has.
		sort( $keys );

		// Try to quickly loop to acquire the keys, but back off after a few rounds.
		// This reduces memcached spam, especially in the rare case where a server acquires
		// some lock keys and dies without releasing them. Lock keys expire after a few minutes.
		$loop = new WaitConditionLoop(
			function () use ( $memc, $keys, &$lockedKeys ) {
				foreach ( array_diff( $keys, $lockedKeys ) as $key ) {
					if ( $memc->add( "$key:mutex", 1, 180 ) ) { // lock record
						$lockedKeys[] = $key;
					}
				}

				return array_diff( $keys, $lockedKeys )
					? WaitConditionLoop::CONDITION_CONTINUE
					: true;
			},
			3.0 // timeout
		);
		$loop->invoke();

		if ( count( $lockedKeys ) != count( $keys ) ) {
			$this->releaseMutexes( $memc, $lockedKeys ); // failed; release what was locked
			return false;
		}

		return true;
	}

	/**
	 * @param MemcachedBagOStuff $memc
	 * @param array $keys List of acquired keys
	 */
	protected function releaseMutexes( MemcachedBagOStuff $memc, array $keys ) {
		foreach ( $keys as $key ) {
			$memc->delete( "$key:mutex" );
		}
	}

	/**
	 * Make sure remaining locks get cleared for sanity
	 */
	function __destruct() {
		while ( count( $this->locksHeld ) ) {
			foreach ( $this->locksHeld as $path => $locks ) {
				$this->doUnlock( [ $path ], self::LOCK_EX );
				$this->doUnlock( [ $path ], self::LOCK_SH );
			}
		}
	}
}
