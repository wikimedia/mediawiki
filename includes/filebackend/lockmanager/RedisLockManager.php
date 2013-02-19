<?php
/**
 * Version of LockManager based on using redis servers.
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
 * Manage locks using redis servers.
 *
 * Version of LockManager based on using redis servers.
 * This is meant for multi-wiki systems that may share files.
 * All locks are non-blocking, which avoids deadlocks.
 *
 * All lock requests for a resource, identified by a hash string, will map to one
 * bucket. Each bucket maps to one or several peer servers, each running redis.
 * A majority of peers must agree for a lock to be acquired.
 *
 * @ingroup LockManager
 * @since 1.21
 */
class RedisLockManager extends QuorumLockManager {
	/** @var Array Mapping of lock types to the type actually used */
	protected $lockTypeMap = array(
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	);

	/** @var RedisConnectionPool */
	protected $redisPool = array();
	/** @var Array Map server names to hostname/IP and port numbers */
	protected $lockServers = array();
	/** @var Array */
	protected $serversUp = array(); // (server name => bool)

	protected $lockExpiry; // integer; maximum time locks can be held
	protected $session = ''; // string; random UUID

	/**
	 * Construct a new instance from configuration.
	 *
	 * $config paramaters include:
	 *   - lockServers  : Associative array of server names to "<IP>:<port>" strings.
	 *   - srvsByBucket : Array of 1-16 consecutive integer keys, starting from 0,
	 *                    each having an odd-numbered list of server names (peers) as values.
	 *   - redisConfig  : Configuration for RedisConnectionPool::__construct().
	 *
	 * @param Array $config
	 * @throws MWException
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		$this->lockServers = $config['lockServers'];
		// Sanitize srvsByBucket config to prevent PHP errors
		$this->srvsByBucket = array_filter( $config['srvsByBucket'], 'is_array' );
		$this->srvsByBucket = array_values( $this->srvsByBucket ); // consecutive

		$this->redisPool = RedisConnectionPool::singleton( $config['redisConfig'] );

		$met = ini_get( 'max_execution_time' ); // this is 0 in CLI mode
		$this->lockExpiry = $met ? 2*(int)$met : 2*3600;

		$this->session = wfRandomString( 32 );
	}

	/**
	 * @see QuorumLockManager::getLocksOnServer()
	 * @return Status
	 */
	protected function getLocksOnServer( $lockSrv, array $paths, $type ) {
		$status = Status::newGood();

		$server = $this->lockServers[$lockSrv];
		$conn = $this->redisPool->getConnection( $server );
		if ( !$conn ) {
			foreach ( $paths as $path ) {
				$status->fatal( 'lockmanager-fail-acquirelock', $path );
			}
			return $status;
		}

		$keys = array_map( array( $this, 'recordKeyForPath' ), $paths ); // lock records
		try {
			$conn->watch( $keys );
			// Fetch all the existing lock records...
			$lockRecords = array_combine( $keys, $conn->mGet( $keys ) );

			$now = time();
			// Check if the requested locks conflict with existing ones...
			foreach ( $paths as $path ) {
				$locksKey = $this->recordKeyForPath( $path );
				$locksHeld = is_array( $lockRecords[$locksKey] )
					? $lockRecords[$locksKey]
					: array( self::LOCK_SH => array(), self::LOCK_EX => array() ); // init
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
					$locksHeld[$type][$this->session] = $now + $this->lockExpiry;
					// We will update this record if none of the other locks conflict
					$lockRecords[$locksKey] = $locksHeld;
				}
			}

			// If there were no lock conflicts, update all the lock records...
			if ( $status->isOK() ) {
				$conn->multi(); // begin (atomic trx)
				$conn->mSet( $lockRecords );
				$res = $conn->exec(); // commit (atomic trx)
				if ( $res === false ) {
					foreach ( $paths as $path ) {
						$status->fatal( 'lockmanager-fail-acquirelock', $path );
					}
				} else {
					foreach ( $lockRecords as $locksKey => $locksHeld ) {
						wfDebug( __METHOD__ . ": acquired lock on key $locksKey.\n" );
					}
				}
			}
		} catch ( RedisException $e ) {
			$this->redisPool->handleException( $server, $conn, $e );
		}

		return $status;
	}

	/**
	 * @see QuorumLockManager::freeLocksOnServer()
	 * @return Status
	 */
	protected function freeLocksOnServer( $lockSrv, array $paths, $type ) {
		$status = Status::newGood();

		$server = $this->lockServers[$lockSrv];
		$conn = $this->redisPool->getConnection( $server );
		if ( !$conn ) {
			foreach ( $paths as $path ) {
				$status->fatal( 'lockmanager-fail-releaselock', $path );
			}
			return $status;
		}

		$keys = array_map( array( $this, 'recordKeyForPath' ), $paths ); // lock records
		try {
			$conn->watch( $keys );
			// Fetch all the existing lock records...
			$lockRecords = array_combine( $keys, $conn->mGet( $keys ) );

			// Remove the requested locks from all records...
			foreach ( $paths as $path ) {
				$locksKey = $this->recordKeyForPath( $path ); // lock record
				if ( !is_array( $lockRecords[$locksKey] ) ) {
					unset( $lockRecords[$locksKey] );
					continue; // nothing to do
				}
				$locksHeld = $lockRecords[$locksKey];
				if ( is_array( $locksHeld ) && isset( $locksHeld[$type] ) ) {
					unset( $locksHeld[$type][$this->session] );
				}
			}

			// Update all lock records...
			$conn->multi(); // begin (atomic trx)
			$conn->mSet( $lockRecords );
			$res = $conn->exec(); // commit (atomic trx)
			if ( $res === false ) {
				foreach ( $paths as $path ) {
					$status->fatal( 'lockmanager-fail-releaselock', $path );
				}
			} else {
				foreach ( $lockRecords as $locksKey => $locksHeld ) {
					wfDebug( __METHOD__ . ": released lock on key $locksKey.\n" );
				}
			}
		} catch ( RedisException $e ) {
			$this->redisPool->handleException( $server, $conn, $e );
		}

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
	 * @return bool
	 */
	protected function isServerUp( $lockSrv ) {
		return (bool)$this->redisPool->getConnection( $this->lockServers[$lockSrv] );
	}

	/**
	 * @param $path string
	 * @return string
	 */
	protected function recordKeyForPath( $path ) {
		return implode( ':', array( __CLASS__, 'locks', $this->sha1Base36Absolute( $path ) ) );
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
