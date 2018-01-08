<?php
/**
 * @defgroup LockManager Lock management
 * @ingroup FileBackend
 */
use Psr\Log\LoggerInterface;
use Wikimedia\WaitConditionLoop;

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
 */

/**
 * @brief Class for handling resource locking.
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

	protected $domain; // string; domain (usually wiki ID)
	protected $lockTTL; // integer; maximum time locks can be held

	/** @var string Random 32-char hex number */
	protected $session;

	/** Lock types; stronger locks have higher values */
	const LOCK_SH = 1; // shared lock (for reads)
	const LOCK_UW = 2; // shared lock (for reads used to write elsewhere)
	const LOCK_EX = 3; // exclusive lock (for writes)

	/** @var int Max expected lock expiry in any context */
	const MAX_LOCK_TTL = 7200; // 2 hours

	/**
	 * Construct a new instance from configuration
	 *
	 * @param array $config Parameters include:
	 *   - domain  : Domain (usually wiki ID) that all resources are relative to [optional]
	 *   - lockTTL : Age (in seconds) at which resource locks should expire.
	 *               This only applies if locks are not tied to a connection/process.
	 */
	public function __construct( array $config ) {
		$this->domain = isset( $config['domain'] ) ? $config['domain'] : 'global';
		if ( isset( $config['lockTTL'] ) ) {
			$this->lockTTL = max( 5, $config['lockTTL'] );
		} elseif ( PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg' ) {
			$this->lockTTL = 3600;
		} else {
			$met = ini_get( 'max_execution_time' ); // this is 0 in CLI mode
			$this->lockTTL = max( 5 * 60, 2 * (int)$met );
		}

		// Upper bound on how long to keep lock structures around. This is useful when setting
		// TTLs, as the "lockTTL" value may vary based on CLI mode and app server group. This is
		// a "safe" value that can be used to avoid clobbering other locks that use high TTLs.
		$this->lockTTL = min( $this->lockTTL, self::MAX_LOCK_TTL );

		$random = [];
		for ( $i = 1; $i <= 5; ++$i ) {
			$random[] = mt_rand( 0, 0xFFFFFFF );
		}
		$this->session = md5( implode( '-', $random ) );

		$this->logger = isset( $config['logger'] ) ? $config['logger'] : new \Psr\Log\NullLogger();
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
	 * Get the base 16 SHA-1 of a string, padded to 31 digits.
	 * Before hashing, the path will be prefixed with the domain ID.
	 * This should be used internally for lock key or file names.
	 *
	 * @param string $path
	 * @return string
	 */
	final protected function sha1Base16Absolute( $path ) {
		return sha1( "{$this->domain}:{$path}" );
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
			$res[$this->lockTypeMap[$type]] = array_unique( $paths );
		}

		return $res;
	}

	/**
	 * @see LockManager::lockByType()
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
