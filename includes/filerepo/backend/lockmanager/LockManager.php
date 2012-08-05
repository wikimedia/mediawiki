<?php
/**
 * @defgroup LockManager Lock management
 * @ingroup FileBackend
 */

/**
 * Resource locking handling.
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
 * @author Aaron Schulz
 */

/**
 * @brief Class for handling resource locking.
 *
 * Locks on resource keys can either be shared or exclusive.
 *
 * Implementations must keep track of what is locked by this proccess
 * in-memory and support nested locking calls (using reference counting).
 * At least LOCK_UW and LOCK_EX must be implemented. LOCK_SH can be a no-op.
 * Locks should either be non-blocking or have low wait timeouts.
 *
 * Subclasses should avoid throwing exceptions at all costs.
 *
 * @ingroup LockManager
 * @since 1.19
 */
abstract class LockManager {
	/** @var Array Mapping of lock types to the type actually used */
	protected $lockTypeMap = array(
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_EX, // subclasses may use self::LOCK_SH
		self::LOCK_EX => self::LOCK_EX
	);

	/** @var Array Map of (resource path => lock type => count) */
	protected $locksHeld = array();

	/* Lock types; stronger locks have higher values */
	const LOCK_SH = 1; // shared lock (for reads)
	const LOCK_UW = 2; // shared lock (for reads used to write elsewhere)
	const LOCK_EX = 3; // exclusive lock (for writes)

	/**
	 * Construct a new instance from configuration
	 *
	 * @param $config Array
	 */
	public function __construct( array $config ) {}

	/**
	 * Lock the resources at the given abstract paths
	 *
	 * @param $paths Array List of resource names
	 * @param $type integer LockManager::LOCK_* constant
	 * @return Status
	 */
	final public function lock( array $paths, $type = self::LOCK_EX ) {
		wfProfileIn( __METHOD__ );
		$status = $this->doLock( array_unique( $paths ), $this->lockTypeMap[$type] );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * Unlock the resources at the given abstract paths
	 *
	 * @param $paths Array List of storage paths
	 * @param $type integer LockManager::LOCK_* constant
	 * @return Status
	 */
	final public function unlock( array $paths, $type = self::LOCK_EX ) {
		wfProfileIn( __METHOD__ );
		$status = $this->doUnlock( array_unique( $paths ), $this->lockTypeMap[$type] );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * Get the base 36 SHA-1 of a string, padded to 31 digits
	 *
	 * @param $path string
	 * @return string
	 */
	final protected static function sha1Base36( $path ) {
		return wfBaseConvert( sha1( $path ), 16, 36, 31 );
	}

	/**
	 * Lock resources with the given keys and lock type
	 *
	 * @param $paths Array List of storage paths
	 * @param $type integer LockManager::LOCK_* constant
	 * @return string
	 */
	abstract protected function doLock( array $paths, $type );

	/**
	 * Unlock resources with the given keys and lock type
	 *
	 * @param $paths Array List of storage paths
	 * @param $type integer LockManager::LOCK_* constant
	 * @return string
	 */
	abstract protected function doUnlock( array $paths, $type );
}

/**
 * Self-releasing locks
 *
 * LockManager helper class to handle scoped locks, which
 * release when an object is destroyed or goes out of scope.
 *
 * @ingroup LockManager
 * @since 1.19
 */
class ScopedLock {
	/** @var LockManager */
	protected $manager;
	/** @var Status */
	protected $status;
	/** @var Array List of resource paths*/
	protected $paths;

	protected $type; // integer lock type

	/**
	 * @param $manager LockManager
	 * @param $paths Array List of storage paths
	 * @param $type integer LockManager::LOCK_* constant
	 * @param $status Status
	 */
	protected function __construct(
		LockManager $manager, array $paths, $type, Status $status
	) {
		$this->manager = $manager;
		$this->paths = $paths;
		$this->status = $status;
		$this->type = $type;
	}

	/**
	 * Get a ScopedLock object representing a lock on resource paths.
	 * Any locks are released once this object goes out of scope.
	 * The status object is updated with any errors or warnings.
	 *
	 * @param $manager LockManager
	 * @param $paths Array List of storage paths
	 * @param $type integer LockManager::LOCK_* constant
	 * @param $status Status
	 * @return ScopedLock|null Returns null on failure
	 */
	public static function factory(
		LockManager $manager, array $paths, $type, Status $status
	) {
		$lockStatus = $manager->lock( $paths, $type );
		$status->merge( $lockStatus );
		if ( $lockStatus->isOK() ) {
			return new self( $manager, $paths, $type, $status );
		}
		return null;
	}

	function __destruct() {
		$wasOk = $this->status->isOK();
		$this->status->merge( $this->manager->unlock( $this->paths, $this->type ) );
		if ( $wasOk ) {
			// Make sure status is OK, despite any unlockFiles() fatals
			$this->status->setResult( true, $this->status->value );
		}
	}
}

/**
 * Version of LockManager that uses a quorum from peer servers for locks.
 * The resource space can also be sharded into separate peer groups.
 *
 * @ingroup LockManager
 * @since 1.20
 */
abstract class QuorumLockManager extends LockManager {
	/** @var Array Map of bucket indexes to peer server lists */
	protected $srvsByBucket = array(); // (bucket index => (lsrv1, lsrv2, ...))

	/**
	 * @see LockManager::doLock()
	 * @param $paths array
	 * @param $type int
	 * @return Status
	 */
	final protected function doLock( array $paths, $type ) {
		$status = Status::newGood();

		$pathsToLock = array(); // (bucket => paths)
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
			$status->merge( $this->doLockingRequestBucket( $bucket, $paths, $type ) );
			if ( !$status->isOK() ) {
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
	final protected function doUnlock( array $paths, $type ) {
		$status = Status::newGood();

		$pathsToUnlock = array();
		foreach ( $paths as $path ) {
			if ( !isset( $this->locksHeld[$path][$type] ) ) {
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

		// Remove these specific locks if possible, or at least release
		// all locks once this process is currently not holding any locks.
		foreach ( $pathsToUnlock as $bucket => $paths ) {
			$status->merge( $this->doUnlockingRequestBucket( $bucket, $paths, $type ) );
		}
		if ( !count( $this->locksHeld ) ) {
			$status->merge( $this->releaseAllLocks() );
		}

		return $status;
	}

	/**
	 * Attempt to acquire locks with the peers for a bucket.
	 * This is all or nothing; if any key is locked then this totally fails.
	 *
	 * @param $bucket integer
	 * @param $paths Array List of resource keys to lock
	 * @param $type integer LockManager::LOCK_EX or LockManager::LOCK_SH
	 * @return Status
	 */
	final protected function doLockingRequestBucket( $bucket, array $paths, $type ) {
		$status = Status::newGood();

		$yesVotes = 0; // locks made on trustable servers
		$votesLeft = count( $this->srvsByBucket[$bucket] ); // remaining peers
		$quorum = floor( $votesLeft/2 + 1 ); // simple majority
		// Get votes for each peer, in order, until we have enough...
		foreach ( $this->srvsByBucket[$bucket] as $lockSrv ) {
			if ( !$this->isServerUp( $lockSrv ) ) {
				--$votesLeft;
				$status->warning( 'lockmanager-fail-svr-acquire', $lockSrv );
				continue; // server down?
			}
			// Attempt to acquire the lock on this peer
			$status->merge( $this->getLocksOnServer( $lockSrv, $paths, $type ) );
			if ( !$status->isOK() ) {
				return $status; // vetoed; resource locked
			}
			++$yesVotes; // success for this peer
			if ( $yesVotes >= $quorum ) {
				return $status; // lock obtained
			}
			--$votesLeft;
			$votesNeeded = $quorum - $yesVotes;
			if ( $votesNeeded > $votesLeft ) {
				break; // short-circuit
			}
		}
		// At this point, we must not have met the quorum
		$status->setResult( false );

		return $status;
	}

	/**
	 * Attempt to release locks with the peers for a bucket
	 *
	 * @param $bucket integer
	 * @param $paths Array List of resource keys to lock
	 * @param $type integer LockManager::LOCK_EX or LockManager::LOCK_SH
	 * @return Status
	 */
	final protected function doUnlockingRequestBucket( $bucket, array $paths, $type ) {
		$status = Status::newGood();

		foreach ( $this->srvsByBucket[$bucket] as $lockSrv ) {
			if ( !$this->isServerUp( $lockSrv ) ) {
				$status->fatal( 'lockmanager-fail-svr-release', $lockSrv );
			// Attempt to release the lock on this peer
			} else {
				$status->merge( $this->freeLocksOnServer( $lockSrv, $paths, $type ) );
			}
		}

		return $status;
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
		return (int)base_convert( $prefix, 16, 10 ) % count( $this->srvsByBucket );
	}

	/**
	 * Check if a lock server is up
	 *
	 * @param $lockSrv string
	 * @return bool
	 */
	abstract protected function isServerUp( $lockSrv );

	/**
	 * Get a connection to a lock server and acquire locks on $paths
	 *
	 * @param $lockSrv string
	 * @param $paths array
	 * @param $type integer
	 * @return Status
	 */
	abstract protected function getLocksOnServer( $lockSrv, array $paths, $type );

	/**
	 * Get a connection to a lock server and release locks on $paths.
	 *
	 * Subclasses must effectively implement this or releaseAllLocks().
	 *
	 * @param $lockSrv string
	 * @param $paths array
	 * @param $type integer
	 * @return Status
	 */
	abstract protected function freeLocksOnServer( $lockSrv, array $paths, $type );

	/**
	 * Release all locks that this session is holding.
	 *
	 * Subclasses must effectively implement this or freeLocksOnServer().
	 *
	 * @return Status
	 */
	abstract protected function releaseAllLocks();
}

/**
 * Simple version of LockManager that does nothing
 * @since 1.19
 */
class NullLockManager extends LockManager {
	/**
	 * @see LockManager::doLock()
	 * @param $paths array
	 * @param $type int
	 * @return Status
	 */
	protected function doLock( array $paths, $type ) {
		return Status::newGood();
	}

	/**
	 * @see LockManager::doUnlock()
	 * @param $paths array
	 * @param $type int
	 * @return Status
	 */
	protected function doUnlock( array $paths, $type ) {
		return Status::newGood();
	}
}
