<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\RequestTimeout\RequestTimeout;
use Wikimedia\WaitConditionLoop;

/**
 * @defgroup LockManager Lock management
 * @ingroup FileBackend
 */

/**
 * Resource locking handling.
 *
 * Locks on resource keys can either be shared or exclusive.
 *
 * Implementations must keep track of what is locked by this process
 * in-memory and support nested locking calls (using reference counting).
 * At least LOCK_UW and LOCK_EX must be implemented. LOCK_SH can be a no-op.
 * Locks should either be non-blocking or have low wait timeouts.
 *
 * Subclasses should avoid throwing exceptions at all costs.
 *
 * @stable to extend
 * @ingroup LockManager
 * @since 1.19
 */
abstract class LockManager {
	/** @var LoggerInterface */
	protected $logger;

	/** @var array Mapping of lock types to the type actually used */
	protected $lockTypeMap = [
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_EX, // subclasses may use self::LOCK_SH
		self::LOCK_EX => self::LOCK_EX
	];

	/** @var array Map of (resource path => lock type => count) */
	protected $locksHeld = [];

	/** @var string domain (usually wiki ID) */
	protected $domain;
	/** @var int maximum time locks can be held */
	protected $lockTTL;

	/** @var string Random 32-char hex number */
	protected $session;

	/** Lock types; stronger locks have higher values */
	public const LOCK_SH = 1; // shared lock (for reads)
	public const LOCK_UW = 2; // shared lock (for reads used to write elsewhere)
	public const LOCK_EX = 3; // exclusive lock (for writes)

	/** Max expected lock expiry in any context */
	protected const MAX_LOCK_TTL = 2 * 3600; // 2 hours

	/** Minimum lock TTL. The configured lockTTL is ignored if it is less than this value. */
	protected const MIN_LOCK_TTL = 5; // seconds

	/**
	 * Construct a new instance from configuration
	 * @stable to call
	 *
	 * @param array $config Parameters include:
	 *   - domain  : [optional] Domain (usually wiki ID) that all resources are relative to.
	 *   - lockTTL : [optional] Customize the maximum duration (in seconds) that a lock
	 *               may be held for. This TTL is stored by LockManager subclasses and serves
	 *               as a recovery mechanism. If a process crashes before it can unlock, locks
	 *               are eventually released.
	 *               Default for web: 5min, or twice the length of max_execution_time (if higher).
	 *               Default for CLI: 1 hour.
	 */
	public function __construct( array $config ) {
		$this->domain = $config['domain'] ?? 'global';
		if ( isset( $config['lockTTL'] ) ) {
			$this->lockTTL = max( self::MIN_LOCK_TTL, $config['lockTTL'] );
		} elseif ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' ) {
			$this->lockTTL = 3600; // 1 hour
		} else {
			$ttl = 2 * ceil( RequestTimeout::singleton()->getWallTimeLimit() );
			$minMaxTTL = 5 * 60; // 5 minutes
			$this->lockTTL = ( $ttl === INF || $ttl < $minMaxTTL ) ? $minMaxTTL : $ttl;
		}

		$this->lockTTL = min( $this->lockTTL, self::MAX_LOCK_TTL );

		$random = [];
		for ( $i = 1; $i <= 5; ++$i ) {
			$random[] = mt_rand( 0, 0xFFFFFFF );
		}
		$this->session = md5( implode( '-', $random ) );

		$this->logger = $config['logger'] ?? new NullLogger();
	}

	/**
	 * Lock the resources at the given abstract paths
	 *
	 * @param array $paths List of resource names
	 * @param int $type LockManager::LOCK_* constant
	 * @param int $timeout Timeout in seconds (0 means non-blocking) (since 1.21)
	 * @return StatusValue
	 */
	final public function lock( array $paths, $type = self::LOCK_EX, $timeout = 0 ) {
		return $this->lockByType( [ $type => $paths ], $timeout );
	}

	/**
	 * Lock the resources at the given abstract paths
	 *
	 * @param array $pathsByType Map of LockManager::LOCK_* constants to lists of paths
	 * @param int $timeout Timeout in seconds (0 means non-blocking) (since 1.21)
	 * @return StatusValue
	 * @since 1.22
	 */
	final public function lockByType( array $pathsByType, $timeout = 0 ) {
		$pathsByType = $this->normalizePathsByType( $pathsByType );

		$status = null;
		$loop = new WaitConditionLoop(
			function () use ( &$status, $pathsByType ) {
				$status = $this->doLockByType( $pathsByType );

				return $status->isOK() ?: WaitConditionLoop::CONDITION_CONTINUE;
			},
			$timeout
		);
		$loop->invoke();

		// @phan-suppress-next-line PhanTypeMismatchReturn WaitConditionLoop throws or status is set
		return $status;
	}

	/**
	 * Unlock the resources at the given abstract paths
	 *
	 * @param array $paths List of paths
	 * @param int $type LockManager::LOCK_* constant
	 * @return StatusValue
	 */
	final public function unlock( array $paths, $type = self::LOCK_EX ) {
		return $this->unlockByType( [ $type => $paths ] );
	}

	/**
	 * Unlock the resources at the given abstract paths
	 *
	 * @param array $pathsByType Map of LockManager::LOCK_* constants to lists of paths
	 * @return StatusValue
	 * @since 1.22
	 */
	final public function unlockByType( array $pathsByType ) {
		$pathsByType = $this->normalizePathsByType( $pathsByType );
		$status = $this->doUnlockByType( $pathsByType );

		return $status;
	}

	/**
	 * Get the base 36 SHA-1 of a string, padded to 31 digits.
	 * Before hashing, the path will be prefixed with the domain ID.
	 * This should be used internally for lock key or file names.
	 *
	 * @param string $path
	 * @return string
	 */
	final protected function sha1Base36Absolute( $path ) {
		return Wikimedia\base_convert( sha1( "{$this->domain}:{$path}" ), 16, 36, 31 );
	}

	/**
	 * Normalize the $paths array by converting LOCK_UW locks into the
	 * appropriate type and removing any duplicated paths for each lock type.
	 *
	 * @param array $pathsByType Map of LockManager::LOCK_* constants to lists of paths
	 * @return array
	 * @since 1.22
	 */
	final protected function normalizePathsByType( array $pathsByType ) {
		$res = [];
		foreach ( $pathsByType as $type => $paths ) {
			foreach ( $paths as $path ) {
				if ( (string)$path === '' ) {
					throw new InvalidArgumentException( __METHOD__ . ": got empty path." );
				}
			}
			$res[$this->lockTypeMap[$type]] = array_unique( $paths );
		}

		return $res;
	}

	/**
	 * @see LockManager::lockByType()
	 * @stable to override
	 * @param array $pathsByType Map of LockManager::LOCK_* constants to lists of paths
	 * @return StatusValue
	 * @since 1.22
	 */
	protected function doLockByType( array $pathsByType ) {
		$status = StatusValue::newGood();
		$lockedByType = []; // map of (type => paths)
		foreach ( $pathsByType as $type => $paths ) {
			$status->merge( $this->doLock( $paths, $type ) );
			if ( $status->isOK() ) {
				$lockedByType[$type] = $paths;
			} else {
				// Release the subset of locks that were acquired
				foreach ( $lockedByType as $lType => $lPaths ) {
					$status->merge( $this->doUnlock( $lPaths, $lType ) );
				}
				break;
			}
		}

		return $status;
	}

	/**
	 * Lock resources with the given keys and lock type
	 *
	 * @param array $paths List of paths
	 * @param int $type LockManager::LOCK_* constant
	 * @return StatusValue
	 */
	abstract protected function doLock( array $paths, $type );

	/**
	 * @see LockManager::unlockByType()
	 * @stable to override
	 * @param array $pathsByType Map of LockManager::LOCK_* constants to lists of paths
	 * @return StatusValue
	 * @since 1.22
	 */
	protected function doUnlockByType( array $pathsByType ) {
		$status = StatusValue::newGood();
		foreach ( $pathsByType as $type => $paths ) {
			$status->merge( $this->doUnlock( $paths, $type ) );
		}

		return $status;
	}

	/**
	 * Unlock resources with the given keys and lock type
	 *
	 * @param array $paths List of paths
	 * @param int $type LockManager::LOCK_* constant
	 * @return StatusValue
	 */
	abstract protected function doUnlock( array $paths, $type );
}
