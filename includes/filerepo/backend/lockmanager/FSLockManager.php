<?php

/**
 * Simple version of LockManager based on using FS lock files.
 * All locks are non-blocking, which avoids deadlocks.
 *
 * This should work fine for small sites running off one server.
 * Do not use this with 'lockDirectory' set to an NFS mount unless the
 * NFS client is at least version 2.6.12. Otherwise, the BSD flock()
 * locks will be ignored; see http://nfs.sourceforge.net/#section_d.
 *
 * @ingroup LockManager
 * @since 1.19
 */
class FSLockManager extends LockManager {
	/** @var Array Mapping of lock types to the type actually used */
	protected $lockTypeMap = array(
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	);

	protected $lockDir; // global dir for all servers

	/** @var Array Map of (locked key => lock type => lock file handle) */
	protected $handles = array();

	/**
	 * Construct a new instance from configuration.
	 * 
	 * $config includes:
	 *     'lockDirectory' : Directory containing the lock files
	 *
	 * @param array $config
	 */
	function __construct( array $config ) {
		parent::__construct( $config );
		$this->lockDir = $config['lockDirectory'];
	}

	protected function doLock( array $paths, $type ) {
		$status = Status::newGood();

		$lockedPaths = array(); // files locked in this attempt
		foreach ( $paths as $path ) {
			$status->merge( $this->doSingleLock( $path, $type ) );
			if ( $status->isOK() ) {
				$lockedPaths[] = $path;
			} else {
				// Abort and unlock everything
				$status->merge( $this->doUnlock( $lockedPaths, $type ) );
				return $status;
			}
		}

		return $status;
	}

	protected function doUnlock( array $paths, $type ) {
		$status = Status::newGood();

		foreach ( $paths as $path ) {
			$status->merge( $this->doSingleUnlock( $path, $type ) );
		}

		return $status;
	}

	/**
	 * Lock a single resource key
	 *
	 * @param $path string
	 * @param $type integer
	 * @return Status 
	 */
	protected function doSingleLock( $path, $type ) {
		$status = Status::newGood();

		if ( isset( $this->locksHeld[$path][$type] ) ) {
			++$this->locksHeld[$path][$type];
		} elseif ( isset( $this->locksHeld[$path][self::LOCK_EX] ) ) {
			$this->locksHeld[$path][$type] = 1;
		} else {
			wfSuppressWarnings();
			$handle = fopen( $this->getLockPath( $path ), 'a+' );
			wfRestoreWarnings();
			if ( !$handle ) { // lock dir missing?
				wfMkdirParents( $this->lockDir );
				$handle = fopen( $this->getLockPath( $path ), 'a+' ); // try again
			}
			if ( $handle ) {
				// Either a shared or exclusive lock
				$lock = ( $type == self::LOCK_SH ) ? LOCK_SH : LOCK_EX;
				if ( flock( $handle, $lock | LOCK_NB ) ) {
					// Record this lock as active
					$this->locksHeld[$path][$type] = 1;
					$this->handles[$path][$type] = $handle;
				} else {
					fclose( $handle );
					$status->fatal( 'lockmanager-fail-acquirelock', $path );
				}
			} else {
				$status->fatal( 'lockmanager-fail-openlock', $path );
			}
		}

		return $status;
	}

	/**
	 * Unlock a single resource key
	 * 
	 * @param $path string
	 * @param $type integer
	 * @return Status 
	 */
	protected function doSingleUnlock( $path, $type ) {
		$status = Status::newGood();

		if ( !isset( $this->locksHeld[$path] ) ) {
			$status->warning( 'lockmanager-notlocked', $path );
		} elseif ( !isset( $this->locksHeld[$path][$type] ) ) {
			$status->warning( 'lockmanager-notlocked', $path );
		} else {
			$handlesToClose = array();
			--$this->locksHeld[$path][$type];
			if ( $this->locksHeld[$path][$type] <= 0 ) {
				unset( $this->locksHeld[$path][$type] );
				// If a LOCK_SH comes in while we have a LOCK_EX, we don't
				// actually add a handler, so check for handler existence.
				if ( isset( $this->handles[$path][$type] ) ) {
					// Mark this handle to be unlocked and closed
					$handlesToClose[] = $this->handles[$path][$type];
					unset( $this->handles[$path][$type] );
				}
			}
			// Unlock handles to release locks and delete
			// any lock files that end up with no locks on them...
			if ( wfIsWindows() ) {
				// Windows: for any process, including this one,
				// calling unlink() on a locked file will fail
				$status->merge( $this->closeLockHandles( $path, $handlesToClose ) );
				$status->merge( $this->pruneKeyLockFiles( $path ) );
			} else {
				// Unix: unlink() can be used on files currently open by this 
				// process and we must do so in order to avoid race conditions
				$status->merge( $this->pruneKeyLockFiles( $path ) );
				$status->merge( $this->closeLockHandles( $path, $handlesToClose ) );
			}
		}

		return $status;
	}

	private function closeLockHandles( $path, array $handlesToClose ) {
		$status = Status::newGood();
		foreach ( $handlesToClose as $handle ) {
			wfSuppressWarnings();
			if ( !flock( $handle, LOCK_UN ) ) {
				$status->fatal( 'lockmanager-fail-releaselock', $path );
			}
			if ( !fclose( $handle ) ) {
				$status->warning( 'lockmanager-fail-closelock', $path );
			}
			wfRestoreWarnings();
		}
		return $status;
	}

	private function pruneKeyLockFiles( $path ) {
		$status = Status::newGood();
		if ( !count( $this->locksHeld[$path] ) ) {
			wfSuppressWarnings();
			# No locks are held for the lock file anymore
			if ( !unlink( $this->getLockPath( $path ) ) ) {
				$status->warning( 'lockmanager-fail-deletelock', $path );
			}
			wfRestoreWarnings();
			unset( $this->locksHeld[$path] );
			unset( $this->handles[$path] );
		}
		return $status;
	}

	/**
	 * Get the path to the lock file for a key
	 * @param $path string
	 * @return string
	 */
	protected function getLockPath( $path ) {
		$hash = self::sha1Base36( $path );
		return "{$this->lockDir}/{$hash}.lock";
	}

	function __destruct() {
		// Make sure remaining locks get cleared for sanity
		foreach ( $this->locksHeld as $path => $locks ) {
			$this->doSingleUnlock( $path, self::LOCK_EX );
			$this->doSingleUnlock( $path, self::LOCK_SH );
		}
	}
}
