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
 * All lock requests for a resource, identified by a hash string, will map
 * to one bucket. Each bucket maps to one or several peer servers, each running memcached.
 * A majority of peers must agree for a lock to be acquired.
 *
 * @ingroup LockManager
 * @since 1.20
 */
class MemcLockManager extends LockManager {
	/** @var Array Mapping of lock types to the type actually used */
	protected $lockTypeMap = array(
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	);

	/** @var Array Map server names to MemcachedBagOStuff objects */
	protected $bagOStuffs = array();
	/** @var Array Map of bucket indexes to peer server lists */
	protected $srvsByBucket; // (bucket index => (lsrv1, lsrv2, ...))

	protected $lockExpiry; // integer; maximum time locks can be held
	protected $session = ''; // string; random SHA-1 UUID

	/**
	 * Construct a new instance from configuration.
	 *
	 * $config paramaters include:
	 *     'lockServers'  : Associative array of server names to <IP>:<port> strings.
	 *     'srvsByBucket' : Array of 1-16 consecutive integer keys, starting from 0,
	 *                      each having an odd-numbered list of server names (peers) as values.
	 *     'memcConfig'   : Configuration array for MemcachedBagOStuff::__construct(). [optional]
	 *
	 * @param Array $config
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		// Sanitize srvsByBucket config to prevent PHP errors
		$this->srvsByBucket = array_filter( $config['srvsByBucket'], 'is_array' );
		$this->srvsByBucket = array_values( $this->srvsByBucket ); // consecutive

		$memcConfig = isset( $config['memcConfig'] ) ? $config['memcConfig'] : array();
		foreach ( $config['lockServers'] as $name => $address ) {
			$params = array( 'servers' => array( $address ) ) + $memcConfig;
			$this->bagOStuffs[$name] = ObjectCache::newMemcached( $params );
		}

		$this->session = '';
		for ( $i = 0; $i < 5; $i++ ) {
			$this->session .= mt_rand( 0, 2147483647 );
		}
		$this->session = wfBaseConvert( sha1( $this->session ), 16, 36, 31 );

		$met = ini_get( 'max_execution_time' );
		$this->lockExpiry = $met ? 2*(int)$met : 86400;
	}

	/**
	 * @see LockManager::doLock()
	 * @param $paths array
	 * @param $type int
	 * @return Status
	 */
	protected function doLock( array $paths, $type ) {
		$status = Status::newGood();

		$pathsToLock = array();
		// Get locks that need to be acquired (buckets => locks)...
		foreach ( $paths as $path ) {
			if ( isset( $this->locksHeld[$path][$type] ) ) {
				++$this->locksHeld[$path][$type];
			} elseif ( isset( $this->locksHeld[$path][self::LOCK_EX] ) ) {
				$this->locksHeld[$path][$type] = 1;
			} else {
				$bucket = $this->getBucketFromKey( $path );
				$pathsToLock[$bucket][] = $path;
			}
		}

		$lockedPaths = array(); // files locked in this attempt
		// Attempt to acquire these locks...
		foreach ( $pathsToLock as $bucket => $paths ) {
			// Try to acquire the locks for this bucket
			$res = $this->doLockingRequestAll( $bucket, $paths, $type );
			if ( $res === 'cantacquire' ) {
				// Resources already locked by another process.
				// Abort and unlock everything we just locked.
				foreach ( $paths as $path ) {
					$status->fatal( 'lockmanager-fail-acquirelock', $path );
				}
				$status->merge( $this->doUnlock( $lockedPaths, $type ) );
				return $status;
			} elseif ( $res !== true ) {
				// Couldn't contact any servers for this bucket.
				// Abort and unlock everything we just locked.
				foreach ( $paths as $path ) {
					$status->fatal( 'lockmanager-fail-acquirelock', $path );
				}
				$status->merge( $this->doUnlock( $lockedPaths, $type ) );
				return $status;
			}
			// Record these locks as active
			foreach ( $paths as $path ) {
				$this->locksHeld[$path][$type] = 1; // locked
			}
			// Keep track of what locks were made in this attempt
			$lockedPaths = array_merge( $lockedPaths, $paths );
		}

		return $status;
	}

	/**
	 * @see LockManager::doUnlock()
	 * @param $paths array
	 * @param $type int
	 * @return Status
	 */
	protected function doUnlock( array $paths, $type ) {
		$status = Status::newGood();

		$pathsToUnlock = array();
		foreach ( $paths as $path ) {
			if ( !isset( $this->locksHeld[$path] ) ) {
				$status->warning( 'lockmanager-notlocked', $path );
			} elseif ( !isset( $this->locksHeld[$path][$type] ) ) {
				$status->warning( 'lockmanager-notlocked', $path );
			} else {
				--$this->locksHeld[$path][$type];
				// Reference count the locks held and release locks when zero
				if ( $this->locksHeld[$path][$type] <= 0 ) {
					unset( $this->locksHeld[$path][$type] );
					$bucket = $this->getBucketFromKey( $path );
					$pathsToUnlock[$bucket][] = $path;
				}
				if ( !count( $this->locksHeld[$path] ) ) {
					unset( $this->locksHeld[$path] ); // no SH or EX locks left for key
				}
			}
		}

		foreach ( $pathsToUnlock as $bucket => $paths ) {
			$status->merge( $this->doUnlockingRequestAll( $bucket, $paths, $type ) );
		}

		return $status;
	}

	/**
	 * Get a connection to a lock server and acquire locks on $paths
	 *
	 * @param $lockSrv string
	 * @param $paths Array
	 * @param $type integer LockManager::LOCK_EX or LockManager::LOCK_SH
	 * @return bool Resources able to be locked
	 */
	protected function doLockingRequest( $lockSrv, array $paths, $type ) {
		if ( $type !== self::LOCK_SH && $type !== self::LOCK_EX ) {
			return true; // ok...
		}

		$now = time();
		foreach ( $paths as $path ) {
			$locksKey = $this->recordKeyForPath( $path ); // lock record

			$memc = $this->bagOStuffs[$lockSrv];
			if ( !$this->acquireMutex( $memc, $locksKey ) ) { // lock record
				return false; // can't acquire record
			}
			$locksHeld = $memc->get( $locksKey );
			if ( !is_array( $locksHeld ) ) {
				$locksHeld = array( self::LOCK_SH => array(), self::LOCK_EX => array() ); // init
			} else {
				foreach ( $locksHeld[self::LOCK_EX] as $session => $expiry ) {
					if ( $expiry < $now ) { // stale?
						unset( $locksHeld[self::LOCK_EX][$session] );
					} elseif ( $session !== $this->session ) {
						$this->releaseMutex( $memc, $key ); // release record
						return false;
					}
				}
				if ( $type === self::LOCK_EX ) {
					foreach ( $locksHeld[self::LOCK_SH] as $session => $expiry ) {
						if ( $expiry < $now ) { // stale?
							unset( $locksHeld[self::LOCK_SH][$session] );
						} elseif ( $session !== $this->session ) {
							$this->releaseMutex( $memc, $key ); // release record
							return false;
						}
					}
				}
			}
			$locksHeld[$type][$this->session] = $now + $this->lockExpiry;
			$ok = $memc->set( $locksKey, $locksHeld );
			$this->releaseMutex( $memc, $locksKey ); // release record
			if ( !$ok ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Attempt to acquire locks with the peers for a bucket
	 *
	 * @param $bucket integer
	 * @param $paths Array List of resource keys to lock
	 * @param $type integer LockManager::LOCK_EX or LockManager::LOCK_SH
	 * @return bool|string One of (true, 'cantacquire', 'srverrors')
	 */
	protected function doLockingRequestAll( $bucket, array $paths, $type ) {
		$yesVotes = 0; // locks made on trustable servers
		$votesLeft = count( $this->srvsByBucket[$bucket] ); // remaining peers
		$quorum = floor( $votesLeft/2 + 1 ); // simple majority
		// Get votes for each peer, in order, until we have enough...
		foreach ( $this->srvsByBucket[$bucket] as $lockSrv ) {
			// Attempt to acquire the lock on this peer
			if ( !$this->doLockingRequest( $lockSrv, $paths, $type ) ) {
				return 'cantacquire'; // vetoed; resource locked
			}
			++$yesVotes; // success for this peer
			if ( $yesVotes >= $quorum ) {
				return true; // lock obtained
			}
			--$votesLeft;
			$votesNeeded = $quorum - $yesVotes;
			if ( $votesNeeded > $votesLeft ) {
				// In "trust cache" mode we don't have to meet the quorum
				break; // short-circuit
			}
		}
		// At this point, we must not have meet the quorum
		return 'srverrors'; // not enough votes to ensure correctness
	}

	/**
	 * Get a connection to a lock server and release locks on $paths
	 *
	 * @param $lockSrv string
	 * @param $paths Array
	 * @param $type integer LockManager::LOCK_EX or LockManager::LOCK_SH
	 * @return bool Resources able to be unlocked
	 */
	protected function doUnlockingRequest( $lockSrv, array $paths, $type ) {
		if ( $type !== self::LOCK_SH && $type !== self::LOCK_EX ) {
			return true; // ok...
		}

		foreach ( $paths as $path ) {
			$locksKey = $this->recordKeyForPath( $path ); // lock record

			$memc = $this->bagOStuffs[$lockSrv];
			if ( !$this->acquireMutex( $memc, $locksKey ) ) { // lock record
				return false; // can't acquire record
			}
			$locksHeld = $memc->get( $locksKey );
			if ( is_array( $locksHeld ) && isset( $locksHeld[$type] ) ) {
				unset( $locksHeld[$type][$this->session] );
				$ok = $memc->set( $locksKey, $locksHeld );
			} else {
				$ok = true;
			}
			$this->releaseMutex( $memc, $locksKey ); // release record
			if ( !$ok ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Attempt to release locks with the peers for a bucket
	 *
	 * @param $bucket integer
	 * @param $paths Array List of resource keys to lock
	 * @param $type integer LockManager::LOCK_EX or LockManager::LOCK_SH
	 * @return Status
	 */
	protected function doUnlockingRequestAll( $bucket, array $paths, $type ) {
		$status = Status::newGood();

		foreach ( $this->srvsByBucket[$bucket] as $lockSrv ) {
			// Attempt to release the lock on this peer
			if ( !$this->doUnlockingRequest( $lockSrv, $paths, $type ) ) {
				$status->fatal( 'lockmanager-fail-svr-release', $lockSrv );
			}
		}

		return $status;
	}

	/**
	 * @param $path string
	 * @return string
	 */
	protected function recordKeyForPath( $path ) {
		return "MemcLockManager:resourcelocks:" . LockManager::sha1Base36( $path );
	}

	/**
	 * @param $memc MemcachedBagOStuff
	 * @param $key string
	 * @return bool
	 */
	protected function acquireMutex( MemcachedBagOStuff $memc, $key ) {
		$attempts = 0;
		$start = microtime( true );
		while ( ( microtime( true ) - $start ) <= 6 && ++$attempts <= 25 ) {
			if ( $memc->add( "$key:mutex", 1, 300 ) ) { // lock record
				return true;
			}
		}
		return false;
	}

	/**
	 * @param $memc MemcachedBagOStuff
	 * @param $key string
	 * @return bool
	 */
	protected function releaseMutex( MemcachedBagOStuff $memc, $key ) {
		return $memc->delete( "$key:mutex" );
	}

	/**
	 * Get the bucket for resource path.
	 * This should avoid throwing any exceptions.
	 *
	 * @param $path string
	 * @return integer
	 */
	protected function getBucketFromKey( $path ) {
		$prefix = substr( sha1( $path ), 0, 2 ); // first 2 hex chars (8 bits)
		return intval( base_convert( $prefix, 16, 10 ) ) % count( $this->srvsByBucket );
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
