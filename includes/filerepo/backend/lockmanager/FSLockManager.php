<?php

/**
 * Simple version of LockManager based on using FS lock files.
 * All locks are non-blocking, which avoids deadlocks.
 *
 * This should work fine for small sites running off one server.
 * Do not use this with 'lockDir' set to an NFS mount unless the
 * NFS client is at least version 2.6.12. Otherwise, the BSD flock()
 * locks will be ignored; see http://nfs.sourceforge.net/#section_d.
 *
 * @ingroup LockManager
 */
class FSLockManager extends LockManager {
	/** @var Array Mapping of lock types to the type actually used */
	protected $lockTypeMap = array(
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	);

	protected $lockDir; // global dir for all servers

	/** @var Array Map of (locked key => lock type => count) */
	protected $locksHeld = array();
	/** @var Array Map of (locked key => lock type => lock file handle) */
	protected $handles = array();

	function __construct( array $config ) {
		$this->lockDir = $config['lockDirectory'];
	}

	protected function doLock( array $keys, $type ) {
		$status = Status::newGood();

		$lockedKeys = array(); // files locked in this attempt
		foreach ( $keys as $key ) {
			$subStatus = $this->doSingleLock( $key, $type );
			$status->merge( $subStatus );
			if ( $status->isOK() ) {
				// Don't append to $lockedKeys if $key is already locked.
				// We do NOT want to unlock the key if we have to rollback.
				if ( $subStatus->isGood() ) { // no warnings/fatals?
					$lockedKeys[] = $key;
				}
			} else {
				// Abort and unlock everything
				$status->merge( $this->doUnlock( $lockedKeys, $type ) );
				return $status;
			}
		}

		return $status;
	}

	protected function doUnlock( array $keys, $type ) {
		$status = Status::newGood();

		foreach ( $keys as $key ) {
			$status->merge( $this->doSingleUnlock( $key, $type ) );
		}

		return $status;
	}

	/**
	 * Lock a single resource key
	 *
	 * @param $key string
	 * @param $type integer
	 * @return Status 
	 */
	protected function doSingleLock( $key, $type ) {
		$status = Status::newGood();

		if ( isset( $this->locksHeld[$key][$type] ) ) {
			++$this->locksHeld[$key][$type];
		} elseif ( isset( $this->locksHeld[$key][self::LOCK_EX] ) ) {
			$this->locksHeld[$key][$type] = 1;
		} else {
			wfSuppressWarnings();
			$handle = fopen( $this->getLockPath( $key ), 'a+' );
			wfRestoreWarnings();
			if ( !$handle ) { // lock dir missing?
				wfMkdirParents( $this->lockDir );
				$handle = fopen( $this->getLockPath( $key ), 'a+' ); // try again
			}
			if ( $handle ) {
				// Either a shared or exclusive lock
				$lock = ( $type == self::LOCK_SH ) ? LOCK_SH : LOCK_EX;
				if ( flock( $handle, $lock | LOCK_NB ) ) {
					// Record this lock as active
					$this->locksHeld[$key][$type] = 1;
					$this->handles[$key][$type] = $handle;
				} else {
					fclose( $handle );
					$status->fatal( 'lockmanager-fail-acquirelock', $key );
				}
			} else {
				$status->fatal( 'lockmanager-fail-openlock', $key );
			}
		}

		return $status;
	}

	/**
	 * Unlock a single resource key
	 * 
	 * @param $key string
	 * @param $type integer
	 * @return Status 
	 */
	protected function doSingleUnlock( $key, $type ) {
		$status = Status::newGood();

		if ( !isset( $this->locksHeld[$key] ) ) {
			$status->warning( 'lockmanager-notlocked', $key );
		} elseif ( !isset( $this->locksHeld[$key][$type] ) ) {
			$status->warning( 'lockmanager-notlocked', $key );
		} else {
			$handlesToClose = array();
			--$this->locksHeld[$key][$type];
			if ( $this->locksHeld[$key][$type] <= 0 ) {
				unset( $this->locksHeld[$key][$type] );
				// If a LOCK_SH comes in while we have a LOCK_EX, we don't
				// actually add a handler, so check for handler existence.
				if ( isset( $this->handles[$key][$type] ) ) {
					// Mark this handle to be unlocked and closed
					$handlesToClose[] = $this->handles[$key][$type];
					unset( $this->handles[$key][$type] );
				}
			}
			// Unlock handles to release locks and delete
			// any lock files that end up with no locks on them...
			if ( wfIsWindows() ) {
				// Windows: for any process, including this one,
				// calling unlink() on a locked file will fail
				$status->merge( $this->closeLockHandles( $key, $handlesToClose ) );
				$status->merge( $this->pruneKeyLockFiles( $key ) );
			} else {
				// Unix: unlink() can be used on files currently open by this 
				// process and we must do so in order to avoid race conditions
				$status->merge( $this->pruneKeyLockFiles( $key ) );
				$status->merge( $this->closeLockHandles( $key, $handlesToClose ) );
			}
		}

		return $status;
	}

	private function closeLockHandles( $key, array $handlesToClose ) {
		$status = Status::newGood();
		foreach ( $handlesToClose as $handle ) {
			wfSuppressWarnings();
			if ( !flock( $handle, LOCK_UN ) ) {
				$status->fatal( 'lockmanager-fail-releaselock', $key );
			}
			if ( !fclose( $handle ) ) {
				$status->warning( 'lockmanager-fail-closelock', $key );
			}
			wfRestoreWarnings();
		}
		return $status;
	}

	private function pruneKeyLockFiles( $key ) {
		$status = Status::newGood();
		if ( !count( $this->locksHeld[$key] ) ) {
			wfSuppressWarnings();
			# No locks are held for the lock file anymore
			if ( !unlink( $this->getLockPath( $key ) ) ) {
				$status->warning( 'lockmanager-fail-deletelock', $key );
			}
			wfRestoreWarnings();
			unset( $this->locksHeld[$key] );
			unset( $this->handles[$key] );
		}
		return $status;
	}

	/**
	 * Get the path to the lock file for a key
	 * @param $key string
	 * @return string
	 */
	protected function getLockPath( $key ) {
		return "{$this->lockDir}/{$key}.lock";
	}

	function __destruct() {
		// Make sure remaining locks get cleared for sanity
		foreach ( $this->locksHeld as $key => $locks ) {
			$this->doSingleUnlock( $key, 0 );
		}
	}
}
