<?php
/**
 * Version of LockManager based on using lock daemon servers.
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
 * Manage locks using a lock daemon server.
 *
 * Version of LockManager based on using lock daemon servers.
 * This is meant for multi-wiki systems that may share files.
 * All locks are non-blocking, which avoids deadlocks.
 *
 * All lock requests for a resource, identified by a hash string, will map
 * to one bucket. Each bucket maps to one or several peer servers, each
 * running LockServerDaemon.php, listening on a designated TCP port.
 * A majority of peers must agree for a lock to be acquired.
 *
 * @ingroup LockManager
 * @since 1.19
 */
class LSLockManager extends QuorumLockManager {
	/** @var Array Mapping of lock types to the type actually used */
	protected $lockTypeMap = array(
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	);

	/** @var Array Map of server names to server config */
	protected $lockServers; // (server name => server config array)

	/** @var Array Map Server connections (server name => resource) */
	protected $conns = array();

	protected $connTimeout; // float number of seconds
	protected $session = ''; // random SHA-1 string

	/**
	 * Construct a new instance from configuration.
	 *
	 * $config paramaters include:
	 *   - lockServers  : Associative array of server names to configuration.
	 *                    Configuration is an associative array that includes:
	 *                      - host    : IP address/hostname
	 *                      - port    : TCP port
	 *                      - authKey : Secret string the lock server uses
	 *   - srvsByBucket : Array of 1-16 consecutive integer keys, starting from 0,
	 *                    each having an odd-numbered list of server names (peers) as values.
	 *   - connTimeout  : Lock server connection attempt timeout. [optional]
	 *
	 * @param Array $config
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		$this->lockServers = $config['lockServers'];
		// Sanitize srvsByBucket config to prevent PHP errors
		$this->srvsByBucket = array_filter( $config['srvsByBucket'], 'is_array' );
		$this->srvsByBucket = array_values( $this->srvsByBucket ); // consecutive

		if ( isset( $config['connTimeout'] ) ) {
			$this->connTimeout = $config['connTimeout'];
		} else {
			$this->connTimeout = 3; // use some sane amount
		}

		$this->session = wfRandomString( 32 ); // 128 bits
	}

	/**
	 * @see QuorumLockManager::getLocksOnServer()
	 * @return Status
	 */
	protected function getLocksOnServer( $lockSrv, array $paths, $type ) {
		$status = Status::newGood();

		// Send out the command and get the response...
		$type = ( $type == self::LOCK_SH ) ? 'SH' : 'EX';
		$keys = array_unique( array_map( 'LockManager::sha1Base36', $paths ) );
		$response = $this->sendCommand( $lockSrv, 'ACQUIRE', $type, $keys );

		if ( $response !== 'ACQUIRED' ) {
			foreach ( $paths as $path ) {
				$status->fatal( 'lockmanager-fail-acquirelock', $path );
			}
		}

		return $status;
	}

	/**
	 * @see QuorumLockManager::freeLocksOnServer()
	 * @return Status
	 */
	protected function freeLocksOnServer( $lockSrv, array $paths, $type ) {
		$status = Status::newGood();

		// Send out the command and get the response...
		$type = ( $type == self::LOCK_SH ) ? 'SH' : 'EX';
		$keys = array_unique( array_map( 'LockManager::sha1Base36', $paths ) );
		$response = $this->sendCommand( $lockSrv, 'RELEASE', $type, $keys );

		if ( $response !== 'RELEASED' ) {
			foreach ( $paths as $path ) {
				$status->fatal( 'lockmanager-fail-releaselock', $path );
			}
		}

		return $status;
	}

	/**
	 * @see QuorumLockManager::releaseAllLocks()
	 * @return Status
	 */
	protected function releaseAllLocks() {
		$status = Status::newGood();

		foreach ( $this->conns as $lockSrv => $conn ) {
			$response = $this->sendCommand( $lockSrv, 'RELEASE_ALL', '', array() );
			if ( $response !== 'RELEASED_ALL' ) {
				$status->fatal( 'lockmanager-fail-svr-release', $lockSrv );
			}
		}

		return $status;
	}

	/**
	 * @see QuorumLockManager::isServerUp()
	 * @return bool
	 */
	protected function isServerUp( $lockSrv ) {
		return (bool)$this->getConnection( $lockSrv );
	}

	/**
	 * Send a command and get back the response
	 *
	 * @param $lockSrv string
	 * @param $action string
	 * @param $type string
	 * @param $values Array
	 * @return string|bool
	 */
	protected function sendCommand( $lockSrv, $action, $type, $values ) {
		$conn = $this->getConnection( $lockSrv );
		if ( !$conn ) {
			return false; // no connection
		}
		$authKey = $this->lockServers[$lockSrv]['authKey'];
		// Build of the command as a flat string...
		$values = implode( '|', $values );
		$key = sha1( $this->session . $action . $type . $values . $authKey );
		// Send out the command...
		if ( fwrite( $conn, "{$this->session}:$key:$action:$type:$values\n" ) === false ) {
			return false;
		}
		// Get the response...
		$response = fgets( $conn );
		if ( $response === false ) {
			return false;
		}
		return trim( $response );
	}

	/**
	 * Get (or reuse) a connection to a lock server
	 *
	 * @param $lockSrv string
	 * @return resource
	 */
	protected function getConnection( $lockSrv ) {
		if ( !isset( $this->conns[$lockSrv] ) ) {
			$cfg = $this->lockServers[$lockSrv];
			wfSuppressWarnings();
			$errno = $errstr = '';
			$conn = fsockopen( $cfg['host'], $cfg['port'], $errno, $errstr, $this->connTimeout );
			wfRestoreWarnings();
			if ( $conn === false ) {
				return null;
			}
			$sec = floor( $this->connTimeout );
			$usec = floor( ( $this->connTimeout - floor( $this->connTimeout ) ) * 1e6 );
			stream_set_timeout( $conn, $sec, $usec );
			$this->conns[$lockSrv] = $conn;
		}
		return $this->conns[$lockSrv];
	}

	/**
	 * Make sure remaining locks get cleared for sanity
	 */
	function __destruct() {
		$this->releaseAllLocks();
		foreach ( $this->conns as $conn ) {
			fclose( $conn );
		}
	}
}
