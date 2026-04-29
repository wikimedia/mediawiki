<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace Wikimedia\LockManager;

use StatusValue;
use Wikimedia\ArrayUtils\ArrayUtils;

/**
 * Base class for lock managers that use a quorum of peer servers for locks.
 *
 * The resource space can also be sharded into separate peer groups.
 *
 * See MemcLockManager and RedisLockManager.
 *
 * @stable to extend
 * @ingroup LockManager
 * @since 1.20
 */
abstract class QuorumLockManager extends LockManager {
	/** @var array Map server names to data used by subclasses */
	protected $lockServers = [];
	/** @var array list of down server names */
	protected $downServers = [];
	/** @var int Minimum number of success to consider lock/unlock successful */
	protected $minVotes = 2;

	/** @inheritDoc */
	final protected function doLockByType( array $pathsByType ) {
		$status = StatusValue::newGood();
		$lockedPaths = []; // files locked in this attempt (type => paths)
		foreach ( $pathsByType as $type => $paths ) {
			foreach ( $paths as $path ) {
				if ( isset( $this->locksHeld[$path][$type] ) ) {
					++$this->locksHeld[$path][$type];
				} else {
					$lockedPaths[$type][] = $path;
					$status->merge( $this->doLockingRequest( $path, $type ) );
					if ( !$status->isOK() ) {
						$status->merge( $this->doUnlockByType( $lockedPaths ) );
						return $status;
					} else {
						$this->locksHeld[$path][$type] = 1; // locked
					}
				}
			}
		}

		return $status;
	}

	/**
	 * @stable to override
	 *
	 * @param array $pathsByType
	 *
	 * @return StatusValue
	 */
	protected function doUnlockByType( array $pathsByType ) {
		$status = StatusValue::newGood();

		foreach ( $pathsByType as $type => $paths ) {
			foreach ( $paths as $path ) {
				if ( !isset( $this->locksHeld[$path][$type] ) ) {
					$status->warning( 'lockmanager-notlocked', $path );
				} else {
					--$this->locksHeld[$path][$type];
					// Reference count the locks held and release locks when zero
					if ( $this->locksHeld[$path][$type] <= 0 ) {
						$status->merge( $this->doUnlockingRequest( $path, $type ) );
						unset( $this->locksHeld[$path][$type] );
					}
					if ( $this->locksHeld[$path] === [] ) {
						unset( $this->locksHeld[$path] ); // no SH or EX locks left for key
					}
				}
			}
		}

		if ( $this->locksHeld === [] ) {
			$status->merge( $this->releaseAllLocks() );
		}

		return $status;
	}

	/**
	 * Attempt to acquire locks with the peers.
	 * This is all or nothing; if any key is locked then this totally fails.
	 *
	 * @param string $path path to be locked
	 * @param string $type type of lock to be passed to ::getLocksOnServer()
	 * @return StatusValue
	 */
	final protected function doLockingRequest( string $path, string $type ) {
		$status = StatusValue::newGood();
		$yesVotes = 0; // locks made on trustable servers
		$votesLeft = count( $this->lockServers ); // remaining peers
		$sortedServers = array_keys( $this->lockServers );
		// shuffle the servers based on hashing of the keys
		ArrayUtils::consistentHashSort( $sortedServers, $path );
		$minVotes = min( $this->minVotes, count( $this->lockServers ) );
		foreach ( $sortedServers as $lockServer ) {
			if ( !$this->isServerUp( $lockServer ) ) {
				--$votesLeft;
				$status->warning( 'lockmanager-fail-svr-acquire', $lockServer );
				$this->downServers[$lockServer] = time();
				continue;
			}
			$status->merge( $this->getLocksOnServer( $lockServer, [ $type => [ $path ] ] ) );
			if ( !$status->isOK() ) {
				return $status; // vetoed; resource locked
			}
			++$yesVotes; // success for this peer
			if ( $yesVotes >= $minVotes ) {
				return $status; // lock obtained
			}
			--$votesLeft;
			$votesNeeded = $minVotes - $yesVotes;
			if ( $votesNeeded > $votesLeft ) {
				break; // short-circuit
			}
		}
		// At this point, we must not have met the quorum
		$status->setResult( false );

		return $status;
	}

	/**
	 * Attempt to release locks with the peers
	 *
	 * @param string $path the path to be unlocked
	 * @param string $type the type to be passed to ::freeLocksOnServer()
	 * @return StatusValue
	 */
	final protected function doUnlockingRequest( string $path, string $type ) {
		$status = StatusValue::newGood();

		$yesVotes = 0; // locks freed on trustable servers
		$isDegraded = count( $this->downServers ); // not the normal quorum?
		$sortedServers = array_keys( $this->lockServers );
		// shuffle the servers based on hashing of the keys
		ArrayUtils::consistentHashSort( $sortedServers, $path );
		$minVotes = min( $this->minVotes, count( $this->lockServers ) );
		foreach ( $sortedServers as $lockServer ) {
			if ( !$this->isServerUp( $lockServer ) ) {
				$status->warning( 'lockmanager-fail-svr-release', $lockServer );
			} else {
				// Attempt to release the lock on this peer
				$status->merge( $this->freeLocksOnServer( $lockServer, [ $type => [ $path ] ] ) );
				++$yesVotes; // success for this peer
				// Normally the first peers form the quorum, and the others are ignored.
				// Ignore them in this case, but not when an alternative quorum was used.
				if ( $yesVotes >= $minVotes && !$isDegraded ) {
					break; // lock released
				}
			}
		}

		// Set a bad StatusValue if the quorum was not met.
		// Assumes the same "up" servers as during the acquire step.
		$status->setResult( $yesVotes >= $minVotes );

		return $status;
	}

	/**
	 * Check if a lock server is up.
	 * This should process cache results to reduce RTT.
	 *
	 * @param string $lockServer
	 * @return bool
	 */
	abstract protected function isServerUp( $lockServer );

	/**
	 * Get a connection to a lock server and acquire locks
	 *
	 * @param string $lockServer
	 * @param array $pathsByType Map of LockManager::LOCK_* constants to lists of paths
	 * @return StatusValue
	 */
	abstract protected function getLocksOnServer( $lockServer, array $pathsByType );

	/**
	 * Get a connection to a lock server and release locks on $paths.
	 *
	 * Subclasses must effectively implement this or releaseAllLocks().
	 *
	 * @param string $lockServer
	 * @param array $pathsByType Map of LockManager::LOCK_* constants to lists of paths
	 * @return StatusValue
	 */
	abstract protected function freeLocksOnServer( $lockServer, array $pathsByType );

	/**
	 * Release all locks that this session is holding.
	 *
	 * Subclasses must effectively implement this or freeLocksOnServer().
	 *
	 * @return StatusValue
	 */
	abstract protected function releaseAllLocks();
}
/** @deprecated class alias since 1.46 */
class_alias( QuorumLockManager::class, 'QuorumLockManager' );
