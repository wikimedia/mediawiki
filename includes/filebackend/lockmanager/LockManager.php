<?php
/**
 * @defgroup LockManager Lock management
 * @ingroup FileBackend
 */

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
 * @brief Class for handling resource locking.
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

	protected $domain; // string; domain (usually wiki ID)
	protected $lockTTL; // integer; maximum time locks can be held

	/* Lock types; stronger locks have higher values */
	const LOCK_SH = 1; // shared lock (for reads)
	const LOCK_UW = 2; // shared lock (for reads used to write elsewhere)
	const LOCK_EX = 3; // exclusive lock (for writes)

	/**
	 * Construct a new instance from configuration
	 *
	 * $config paramaters include:
	 *   - domain  : Domain (usually wiki ID) that all resources are relative to [optional]
	 *   - lockTTL : Age (in seconds) at which resource locks should expire.
	 *               This only applies if locks are not tied to a connection/process.
	 *
	 * @param $config Array
	 */
	public function __construct( array $config ) {
		$this->domain = isset( $config['domain'] ) ? $config['domain'] : wfWikiID();
		if ( isset( $config['lockTTL'] ) ) {
			$this->lockTTL = max( 1, $config['lockTTL'] );
		} elseif ( PHP_SAPI === 'cli' ) {
			$this->lockTTL = 2*3600;
		} else {
			$met = ini_get( 'max_execution_time' ); // this is 0 in CLI mode
			$this->lockTTL = max( 5*60, 2*(int)$met );
		}
	}

	/**
	 * Lock the resources at the given abstract paths
	 *
	 * @param array $paths List of resource names
	 * @param $type integer LockManager::LOCK_* constant
	 * @return Status
	 */
	final public function lock( array $paths, $type = self::LOCK_EX ) {
		wfProfileIn( __METHOD__ );
		$status = $this->doLock( array_unique( $paths ), $this->lockTypeMap[$type] );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * Unlock the resources at the given abstract paths
	 *
	 * @param array $paths List of storage paths
	 * @param $type integer LockManager::LOCK_* constant
	 * @return Status
	 */
	final public function unlock( array $paths, $type = self::LOCK_EX ) {
		wfProfileIn( __METHOD__ );
		$status = $this->doUnlock( array_unique( $paths ), $this->lockTypeMap[$type] );
		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * Get the base 36 SHA-1 of a string, padded to 31 digits.
	 * Before hashing, the path will be prefixed with the domain ID.
	 * This should be used interally for lock key or file names.
	 *
	 * @param $path string
	 * @return string
	 */
	final protected function sha1Base36Absolute( $path ) {
		return wfBaseConvert( sha1( "{$this->domain}:{$path}" ), 16, 36, 31 );
	}

	/**
	 * Get the base 16 SHA-1 of a string, padded to 31 digits.
	 * Before hashing, the path will be prefixed with the domain ID.
	 * This should be used interally for lock key or file names.
	 *
	 * @param $path string
	 * @return string
	 */
	final protected function sha1Base16Absolute( $path ) {
		return sha1( "{$this->domain}:{$path}" );
	}

	/**
	 * Lock resources with the given keys and lock type
	 *
	 * @param array $paths List of storage paths
	 * @param $type integer LockManager::LOCK_* constant
	 * @return string
	 */
	abstract protected function doLock( array $paths, $type );

	/**
	 * Unlock resources with the given keys and lock type
	 *
	 * @param array $paths List of storage paths
	 * @param $type integer LockManager::LOCK_* constant
	 * @return string
	 */
	abstract protected function doUnlock( array $paths, $type );
}

/**
 * Simple version of LockManager that does nothing
 * @since 1.19
 */
class NullLockManager extends LockManager {
	/**
	 * @see LockManager::doLock()
	 * @param $paths array
	 * @param $type int
	 * @return Status
	 */
	protected function doLock( array $paths, $type ) {
		return Status::newGood();
	}

	/**
	 * @see LockManager::doUnlock()
	 * @param $paths array
	 * @param $type int
	 * @return Status
	 */
	protected function doUnlock( array $paths, $type ) {
		return Status::newGood();
	}
}
