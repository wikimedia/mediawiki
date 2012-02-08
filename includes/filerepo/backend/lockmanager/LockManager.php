<?php
/**
 * @defgroup LockManager Lock management
 * @ingroup FileBackend
 */

/**
 * @file
 * @ingroup LockManager
 * @author Aaron Schulz
 */

/**
 * Class for handling resource locking.
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
		return $this->doLock( array_unique( $paths ), $this->lockTypeMap[$type] );
	}

	/**
	 * Unlock the resources at the given abstract paths
	 * 
	 * @param $paths Array List of storage paths
	 * @param $type integer LockManager::LOCK_* constant
	 * @return Status 
	 */
	final public function unlock( array $paths, $type = self::LOCK_EX ) {
		return $this->doUnlock( array_unique( $paths ), $this->lockTypeMap[$type] );
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
 * Self releasing locks
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

	protected function __clone() {}

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
 * Simple version of LockManager that does nothing
 * @since 1.19
 */
class NullLockManager extends LockManager {
	protected function doLock( array $paths, $type ) {
		return Status::newGood();
	}

	protected function doUnlock( array $paths, $type ) {
		return Status::newGood();
	}
}
