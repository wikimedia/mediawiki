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
	/** @var Array List of resource paths*/
	protected $paths;

	protected $type; // integer lock type

	/**
	 * @param $manager LockManager
	 * @param array $paths List of storage paths
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
	 * @param array $paths List of storage paths
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

	/**
	 * Release a scoped lock and set any errors in the attatched Status object.
	 * This is useful for early release of locks before function scope is destroyed.
	 * This is the same as setting the lock object to null.
	 *
	 * @param ScopedLock $lock
	 * @return void
	 * @since 1.21
	 */
	public static function release( ScopedLock &$lock = null ) {
		$lock = null;
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
