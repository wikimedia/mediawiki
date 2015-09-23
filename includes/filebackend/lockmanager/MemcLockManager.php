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
	protected $lockTypeMap = array(
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	);

	/** @var array Map server names to MemcachedBagOStuff objects */
	protected $bagOStuffs = array();

	/** @var array (server name => bool) */
	protected $serversUp = array();

	/** @var string Random UUID */
	protected $session = '';

	/**
	 * Construct a new instance from configuration.
	 *
	 * @param array $config Parameters include:
	 *   - lockServers  : Associative array of server names to "<IP>:<port>" strings.
	 *   - srvsByBucket : Array of 1-16 consecutive integer keys, starting from 0,
	 *                    each having an odd-numbered list of server names (peers) as values.
	 *   - memcConfig   : Configuration array for ObjectCache::newFromParams. [optional]
	 *                    If set, this must use one of the memcached classes.
	 * @throws Exception
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		// Sanitize srvsByBucket config to prevent PHP errors
		$this->srvsByBucket = array_filter( $config['srvsByBucket'], 'is_array' );
		$this->srvsByBucket = array_values( $this->srvsByBucket ); // consecutive

		$memcConfig = isset( $config['memcConfig'] )
			? $config['memcConfig']
			: array( 'class' => 'MemcachedPhpBagOStuff' );

		foreach ( $config['lockServers'] as $name => $address ) {
			$params = array( 'servers' => array( $address ) ) + $memcConfig;
			$cache = ObjectCache::newFromParams( $params );
			if ( $cache instanceof MemcachedBagOStuff ) {
				$this->bagOStuffs[$name] = $cache;
			} else {
				throw new Exception(
					'Only MemcachedBagOStuff classes are supported by MemcLockManager.' );
			}
		}

		$this->session = wfRandomString( 32 );
	}

	// @todo Change this code to work in one batch
	protected function getLocksOnServer( $lockSrv, array $pathsByType ) {
		$status = Status::newGood();

		$lockedPaths = array();
		foreach ( $pathsByType as $type => $paths ) {
			$status->merge( $this->doGetLocksOnServer( $lockSrv, $paths, $type ) );
			if ( $status->isOK() ) {
				$lockedPaths[$type] = isset( $lockedPaths[$type] )
					? array_merge( $lockedPaths[$type], $paths )
					: $paths;
			} else {
				foreach ( $lockedPaths as $lType => $lPaths ) {
					$status->merge( $this->doFreeLocksOnServer( $lockSrv, $lPaths, $lType ) );
				}
				break;
			}
		}

		return $status;
	}

	// @todo Change this code to work in one batch
	protected function freeLocksOnServer( $lockSrv, array $pathsByType ) {
		$status = Status::newGood();

		foreach ( $pathsByType as $type => $paths ) {
			$status->merge( $this->doFreeLocksOnServer( $lockSrv, $paths, $type ) );
		}

		return $status;
	}

	/**
	 * @see QuorumLockManager::getLocksOnServer()
	 * @param string $lockSrv
	 * @param array $paths
	 * @param string $type
	 * @return Status
	 */
	protected function doGetLocksOnServer( $lockSrv, array $paths, $type ) {
		$status = Status::newGood();

		$memc = $this->getCache( $lockSrv );
		$keys = array_map( array( $this, 'recordKeyForPath' ), $paths ); // lock records

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

		// If there were no lock conflicts, update all the lock records...
		if ( $status->isOK() ) {
			foreach ( $paths as $path ) {
				$locksKey = $this->recordKeyForPath( $path );
				$locksHeld = $lockRecords[$locksKey];
				$ok = $memc->set( $locksKey, $locksHeld, 7 * 86400 );
				if ( !$ok ) {
					$status->fatal( 'lockmanager-fail-acquirelock', $path );
				} else {
					wfDebug( __METHOD__ . ": acquired lock on key $locksKey.\n" );
				}
			}
		}

		// Unlock all of the active lock record keys...
		$this->releaseMutexes( $memc, $keys );

		return $status;
	}

	/**
	 * @see QuorumLockManager::freeLocksOnServer()
	 * @param string $lockSrv
	 * @param array $paths
	 * @param string $type
	 * @return Status
	 */
	protected function doFreeLocksOnServer( $lockSrv, array $paths, $type ) {
		$status = Status::newGood();

		$memc = $this->getCache( $lockSrv );
		$keys = array_map( array( $this, 'recordKeyForPath' ), $paths ); // lock records

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
		foreach ( $paths as $path ) {
			$locksKey = $this->recordKeyForPath( $path ); // lock record
			if ( !isset( $lockRecords[$locksKey] ) ) {
				$status->warning( 'lockmanager-fail-releaselock', $path );
				continue; // nothing to do
			}
			$locksHeld = self::sanitizeLockArray( $lockRecords[$locksKey] );
			if ( isset( $locksHeld[$type][$this->session] ) ) {
				unset( $locksHeld[$type][$this->session] ); // unregister this session
				if ( $locksHeld === self::newLockArray() ) {
					$ok = $memc->delete( $locksKey );
				} else {
					$ok = $memc->set( $locksKey, $locksHeld );
				}
				if ( !$ok ) {
					$status->fatal( 'lockmanager-fail-releaselock', $path );
				}
			} else {
				$status->warning( 'lockmanager-fail-releaselock', $path );
			}
			wfDebug( __METHOD__ . ": released lock on key $locksKey.\n" );
		}

		// Unlock all of the active lock record keys...
		$this->releaseMutexes( $memc, $keys );

		return $status;
	}

	/**
	 * @see QuorumLockManager::releaseAllLocks()
	 * @return Status
	 */
	protected function releaseAllLocks() {
		return Status::newGood(); // not supported
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
		$memc = null;
		if ( isset( $this->bagOStuffs[$lockSrv] ) ) {
			$memc = $this->bagOStuffs[$lockSrv];
			if ( !isset( $this->serversUp[$lockSrv] ) ) {
				$this->serversUp[$lockSrv] = $memc->set( __CLASS__ . ':ping', 1, 1 );
				if ( !$this->serversUp[$lockSrv] ) {
					trigger_error( __METHOD__ . ": Could not contact $lockSrv.", E_USER_WARNING );
				}
			}
			if ( !$this->serversUp[$lockSrv] ) {
				return null; // server appears to be down
			}
		}

		return $memc;
	}

	/**
	 * @param string $path
	 * @return string
	 */
	protected function recordKeyForPath( $path ) {
		return implode( ':', array( __CLASS__, 'locks', $this->sha1Base36Absolute( $path ) ) );
	}

	/**
	 * @return array An empty lock structure for a key
	 */
	protected static function newLockArray() {
		return array( self::LOCK_SH => array(), self::LOCK_EX => array() );
	}

	/**
	 * @param array $a
	 * @return array An empty lock structure for a key
	 */
	protected static function sanitizeLockArray( $a ) {
		if ( is_array( $a ) && isset( $a[self::LOCK_EX] ) && isset( $a[self::LOCK_SH] ) ) {
			return $a;
		} else {
			trigger_error( __METHOD__ . ": reset invalid lock array.", E_USER_WARNING );

			return self::newLockArray();
		}
	}

	/**
	 * @param MemcachedBagOStuff $memc
	 * @param array $keys List of keys to acquire
	 * @return bool
	 */
	protected function acquireMutexes( MemcachedBagOStuff $memc, array $keys ) {
		$lockedKeys = array();

		// Acquire the keys in lexicographical order, to avoid deadlock problems.
		// If P1 is waiting to acquire a key P2 has, P2 can't also be waiting for a key P1 has.
		sort( $keys );

		// Try to quickly loop to acquire the keys, but back off after a few rounds.
		// This reduces memcached spam, especially in the rare case where a server acquires
		// some lock keys and dies without releasing them. Lock keys expire after a few minutes.
		$rounds = 0;
		$start = microtime( true );
		do {
			if ( ( ++$rounds % 4 ) == 0 ) {
				usleep( 1000 * 50 ); // 50 ms
			}
			foreach ( array_diff( $keys, $lockedKeys ) as $key ) {
				if ( $memc->add( "$key:mutex", 1, 180 ) ) { // lock record
					$lockedKeys[] = $key;
				} else {
					continue; // acquire in order
				}
			}
		} while ( count( $lockedKeys ) < count( $keys ) && ( microtime( true ) - $start ) <= 3 );

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
				$this->doUnlock( array( $path ), self::LOCK_EX );
				$this->doUnlock( array( $path ), self::LOCK_SH );
			}
		}
	}
}
