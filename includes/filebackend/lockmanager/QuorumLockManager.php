<?php
/**
 * Version of LockManager that uses a quorum from peer servers for locks.
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
			} else {
				$bucket = $this->getBucketFromPath( $path );
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
					$bucket = $this->getBucketFromPath( $path );
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
	 * @param array $paths List of resource keys to lock
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
	 * @param array $paths List of resource keys to lock
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
	protected function getBucketFromPath( $path ) {
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
