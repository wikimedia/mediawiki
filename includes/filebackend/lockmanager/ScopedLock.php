<?php
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

	/** @var array Map of lock types to resource paths */
	protected $pathsByType;

	/**
	 * @param LockManager $manager
	 * @param array $pathsByType Map of lock types to path lists
	 * @param Status $status
	 */
	protected function __construct( LockManager $manager, array $pathsByType, Status $status ) {
		$this->manager = $manager;
		$this->pathsByType = $pathsByType;
		$this->status = $status;
	}

	/**
	 * Get a ScopedLock object representing a lock on resource paths.
	 * Any locks are released once this object goes out of scope.
	 * The status object is updated with any errors or warnings.
	 *
	 * @param LockManager $manager
	 * @param array $paths List of storage paths or map of lock types to path lists
	 * @param int|string $type LockManager::LOCK_* constant or "mixed" and $paths
	 *   can be a map of types to paths (since 1.22). Otherwise $type should be an
	 *   integer and $paths should be a list of paths.
	 * @param Status $status
	 * @param int $timeout Timeout in seconds (0 means non-blocking) (since 1.22)
	 * @return ScopedLock|null Returns null on failure
	 */
	public static function factory(
		LockManager $manager, array $paths, $type, Status $status, $timeout = 0
	) {
		$pathsByType = is_integer( $type ) ? [ $type => $paths ] : $paths;
		$lockStatus = $manager->lockByType( $pathsByType, $timeout );
		$status->merge( $lockStatus );
		if ( $lockStatus->isOK() ) {
			return new self( $manager, $pathsByType, $status );
		}

		return null;
	}

	/**
	 * Release a scoped lock and set any errors in the attatched Status object.
	 * This is useful for early release of locks before function scope is destroyed.
	 * This is the same as setting the lock object to null.
	 *
	 * @param ScopedLock $lock
	 * @since 1.21
	 */
	public static function release( ScopedLock &$lock = null ) {
		$lock = null;
	}

	/**
	 * Release the locks when this goes out of scope
	 */
	function __destruct() {
		$wasOk = $this->status->isOK();
		$this->status->merge( $this->manager->unlockByType( $this->pathsByType ) );
		if ( $wasOk ) {
			// Make sure status is OK, despite any unlockFiles() fatals
			$this->status->setResult( true, $this->status->value );
		}
	}
}
