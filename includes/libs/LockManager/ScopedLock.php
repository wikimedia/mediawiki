<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * Self-releasing locks.
 *
 * Helper for consumers of LockManager, to create locks that automatically
 * release when an object is destroyed or goes out of scope.
 *
 * @ingroup LockManager
 * @since 1.19
 */
class ScopedLock {
	/** @var LockManager */
	protected $manager;
	/** @var StatusValue */
	protected $status;
	/** @var array Map of lock types to resource paths */
	protected $pathsByType;

	/**
	 * @param LockManager $manager
	 * @param array $pathsByType Map of lock types to path lists
	 * @param StatusValue $status
	 */
	protected function __construct(
		LockManager $manager, array $pathsByType, StatusValue $status
	) {
		$this->manager = $manager;
		$this->pathsByType = $pathsByType;
		$this->status = $status;
	}

	/**
	 * Get a ScopedLock object representing a lock on resource paths.
	 * Any locks are released once this object goes out of scope.
	 * The StatusValue object is updated with any errors or warnings.
	 *
	 * @param LockManager $manager
	 * @param array $paths List of storage paths or map of lock types to path lists
	 * @param int|string $type LockManager::LOCK_* constant or "mixed" and $paths
	 *   can be a map of types to paths (since 1.22). Otherwise $type should be an
	 *   integer and $paths should be a list of paths.
	 * @param StatusValue $status
	 * @param int $timeout Timeout in seconds (0 means non-blocking) (since 1.22)
	 * @return ScopedLock|null Returns null on failure
	 */
	public static function factory(
		LockManager $manager, array $paths, $type, StatusValue $status, $timeout = 0
	) {
		$pathsByType = is_int( $type ) ? [ $type => $paths ] : $paths;
		$lockStatus = $manager->lockByType( $pathsByType, $timeout );
		$status->merge( $lockStatus );
		if ( $lockStatus->isOK() ) {
			return new self( $manager, $pathsByType, $status );
		}

		return null;
	}

	/**
	 * Release a scoped lock and set any errors in the attached StatusValue object.
	 * This is useful for early release of locks before function scope is destroyed.
	 * This is the same as setting the lock object to null.
	 *
	 * @param ScopedLock|null &$lock
	 * @since 1.21
	 */
	public static function release( ?ScopedLock &$lock = null ) {
		$lock = null;
	}

	/**
	 * Release the locks when this goes out of scope
	 */
	public function __destruct() {
		$wasOk = $this->status->isOK();
		$this->status->merge( $this->manager->unlockByType( $this->pathsByType ) );
		if ( $wasOk ) {
			// Make sure StatusValue is OK, despite any unlockFiles() fatals
			$this->status->setResult( true, $this->status->value );
		}
	}
}
