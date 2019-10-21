<?php
/**
 * Simple version of LockManager based on using FS lock files.
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
	/** @var array Mapping of lock types to the type actually used */
	protected $lockTypeMap = [
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	];

	/** @var string Global dir for all servers */
	protected $lockDir;

	/** @var array Map of (locked key => lock file handle) */
	protected $handles = [];

	/** @var bool */
	protected $isWindows;

	/**
	 * Construct a new instance from configuration.
	 *
	 * @param array $config Includes:
	 *   - lockDirectory : Directory containing the lock files
	 */
	function __construct( array $config ) {
		parent::__construct( $config );

		$this->lockDir = $config['lockDirectory'];
		$this->isWindows = ( strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN' );
	}

	/**
	 * @see LockManager::doLock()
	 * @param array $paths
	 * @param int $type
	 * @return StatusValue
	 */
	protected function doLock( array $paths, $type ) {
		$status = StatusValue::newGood();

		$lockedPaths = []; // files locked in this attempt
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

	/**
	 * @see LockManager::doUnlock()
	 * @param array $paths
	 * @param int $type
	 * @return StatusValue
	 */
	protected function doUnlock( array $paths, $type ) {
		$status = StatusValue::newGood();

		foreach ( $paths as $path ) {
			$status->merge( $this->doSingleUnlock( $path, $type ) );
		}

		return $status;
	}

	/**
	 * Lock a single resource key
	 *
	 * @param string $path
	 * @param int $type
	 * @return StatusValue
	 */
	protected function doSingleLock( $path, $type ) {
		$status = StatusValue::newGood();

		if ( isset( $this->locksHeld[$path][$type] ) ) {
			++$this->locksHeld[$path][$type];
		} elseif ( isset( $this->locksHeld[$path][self::LOCK_EX] ) ) {
			$this->locksHeld[$path][$type] = 1;
		} else {
			if ( isset( $this->handles[$path] ) ) {
				$handle = $this->handles[$path];
			} else {
				Wikimedia\suppressWarnings();
				$handle = fopen( $this->getLockPath( $path ), 'a+' );
				if ( !$handle && !is_dir( $this->lockDir ) ) {
					// Create the lock directory in case it is missing
					if ( mkdir( $this->lockDir, 0777, true ) ) {
						$handle = fopen( $this->getLockPath( $path ), 'a+' ); // try again
					} else {
						$this->logger->error( "Cannot create directory '{$this->lockDir}'." );
					}
				}
				Wikimedia\restoreWarnings();
			}
			if ( $handle ) {
				// Either a shared or exclusive lock
				$lock = ( $type == self::LOCK_SH ) ? LOCK_SH : LOCK_EX;
				if ( flock( $handle, $lock | LOCK_NB ) ) {
					// Record this lock as active
					$this->locksHeld[$path][$type] = 1;
					$this->handles[$path] = $handle;
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
	 * @param string $path
	 * @param int $type
	 * @return StatusValue
	 */
	protected function doSingleUnlock( $path, $type ) {
		$status = StatusValue::newGood();

		if ( !isset( $this->locksHeld[$path] ) ) {
			$status->warning( 'lockmanager-notlocked', $path );
		} elseif ( !isset( $this->locksHeld[$path][$type] ) ) {
			$status->warning( 'lockmanager-notlocked', $path );
		} else {
			$handlesToClose = [];
			--$this->locksHeld[$path][$type];
			if ( $this->locksHeld[$path][$type] <= 0 ) {
				unset( $this->locksHeld[$path][$type] );
			}
			if ( $this->locksHeld[$path] === [] ) {
				unset( $this->locksHeld[$path] ); // no locks on this path
				if ( isset( $this->handles[$path] ) ) {
					$handlesToClose[] = $this->handles[$path];
					unset( $this->handles[$path] );
				}
			}
			// Unlock handles to release locks and delete
			// any lock files that end up with no locks on them...
			if ( $this->isWindows ) {
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

	/**
	 * @param string $path
	 * @param array $handlesToClose
	 * @return StatusValue
	 */
	private function closeLockHandles( $path, array $handlesToClose ) {
		$status = StatusValue::newGood();
		foreach ( $handlesToClose as $handle ) {
			if ( !flock( $handle, LOCK_UN ) ) {
				$status->fatal( 'lockmanager-fail-releaselock', $path );
			}
			if ( !fclose( $handle ) ) {
				$status->warning( 'lockmanager-fail-closelock', $path );
			}
		}

		return $status;
	}

	/**
	 * @param string $path
	 * @return StatusValue
	 */
	private function pruneKeyLockFiles( $path ) {
		$status = StatusValue::newGood();
		if ( !isset( $this->locksHeld[$path] ) ) {
			# No locks are held for the lock file anymore
			if ( !unlink( $this->getLockPath( $path ) ) ) {
				$status->warning( 'lockmanager-fail-deletelock', $path );
			}
			unset( $this->handles[$path] );
		}

		return $status;
	}

	/**
	 * Get the path to the lock file for a key
	 * @param string $path
	 * @return string
	 */
	protected function getLockPath( $path ) {
		return "{$this->lockDir}/{$this->sha1Base36Absolute( $path )}.lock";
	}

	/**
	 * Make sure remaining locks get cleared for sanity
	 */
	function __destruct() {
		while ( count( $this->locksHeld ) ) {
			foreach ( $this->locksHeld as $path => $locks ) {
				$this->doSingleUnlock( $path, self::LOCK_EX );
				$this->doSingleUnlock( $path, self::LOCK_SH );
			}
		}
	}
}
