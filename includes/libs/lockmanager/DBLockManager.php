<?php
/**
 * Version of LockManager based on using DB table locks.
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

use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\DBError;

/**
 * Version of LockManager based on using named/row DB locks.
 *
 * This is meant for multi-wiki systems that may share files.
 *
 * All lock requests for a resource, identified by a hash string, will map to one bucket.
 * Each bucket maps to one or several peer DBs, each on their own server.
 * A majority of peer DBs must agree for a lock to be acquired.
 *
 * Caching is used to avoid hitting servers that are down.
 *
 * @ingroup LockManager
 * @since 1.19
 */
abstract class DBLockManager extends QuorumLockManager {
	/** @var array[]|IDatabase[] Map of (DB names => server config or IDatabase) */
	protected $dbServers; // (DB name => server config array)
	/** @var BagOStuff */
	protected $statusCache;

	protected $lockExpiry; // integer number of seconds
	protected $safeDelay; // integer number of seconds
	/** @var IDatabase[] Map Database connections (DB name => Database) */
	protected $conns = [];

	/**
	 * Construct a new instance from configuration.
	 *
	 * @param array $config Parameters include:
	 *   - dbServers   : Associative array of DB names to server configuration.
	 *                   Configuration is an associative array that includes:
	 *                     - host        : DB server name
	 *                     - dbname      : DB name
	 *                     - type        : DB type (mysql,postgres,...)
	 *                     - user        : DB user
	 *                     - password    : DB user password
	 *                     - tablePrefix : DB table prefix
	 *                     - flags       : DB flags; bitfield of IDatabase::DBO_* constants
	 *   - dbsByBucket : Array of 1-16 consecutive integer keys, starting from 0,
	 *                   each having an odd-numbered list of DB names (peers) as values.
	 *   - lockExpiry  : Lock timeout (seconds) for dropped connections. [optional]
	 *                   This tells the DB server how long to wait before assuming
	 *                   connection failure and releasing all the locks for a session.
	 *   - srvCache    : A BagOStuff instance using APC or the like.
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		$this->dbServers = $config['dbServers'];
		// Sanitize srvsByBucket config to prevent PHP errors
		$this->srvsByBucket = array_filter( $config['dbsByBucket'], 'is_array' );
		$this->srvsByBucket = array_values( $this->srvsByBucket ); // consecutive

		if ( isset( $config['lockExpiry'] ) ) {
			$this->lockExpiry = $config['lockExpiry'];
		} else {
			$met = ini_get( 'max_execution_time' );
			$this->lockExpiry = $met ? $met : 60; // use some sane amount if 0
		}
		$this->safeDelay = ( $this->lockExpiry <= 0 )
			? 60 // pick a safe-ish number to match DB timeout default
			: $this->lockExpiry; // cover worst case

		// Tracks peers that couldn't be queried recently to avoid lengthy
		// connection timeouts. This is useless if each bucket has one peer.
		$this->statusCache = isset( $config['srvCache'] )
			? $config['srvCache']
			: new HashBagOStuff();
	}

	/**
	 * @TODO change this code to work in one batch
	 * @param string $lockSrv
	 * @param array $pathsByType
	 * @return StatusValue
	 */
	protected function getLocksOnServer( $lockSrv, array $pathsByType ) {
		$status = StatusValue::newGood();
		foreach ( $pathsByType as $type => $paths ) {
			$status->merge( $this->doGetLocksOnServer( $lockSrv, $paths, $type ) );
		}

		return $status;
	}

	abstract protected function doGetLocksOnServer( $lockSrv, array $paths, $type );

	protected function freeLocksOnServer( $lockSrv, array $pathsByType ) {
		return StatusValue::newGood();
	}

	/**
	 * @see QuorumLockManager::isServerUp()
	 * @param string $lockSrv
	 * @return bool
	 */
	protected function isServerUp( $lockSrv ) {
		if ( !$this->cacheCheckFailures( $lockSrv ) ) {
			return false; // recent failure to connect
		}
		try {
			$this->getConnection( $lockSrv );
		} catch ( DBError $e ) {
			$this->cacheRecordFailure( $lockSrv );

			return false; // failed to connect
		}

		return true;
	}

	/**
	 * Get (or reuse) a connection to a lock DB
	 *
	 * @param string $lockDb
	 * @return IDatabase
	 * @throws DBError
	 * @throws UnexpectedValueException
	 */
	protected function getConnection( $lockDb ) {
		if ( !isset( $this->conns[$lockDb] ) ) {
			if ( $this->dbServers[$lockDb] instanceof IDatabase ) {
				// Direct injected connection hande for $lockDB
				$db = $this->dbServers[$lockDb];
			} elseif ( is_array( $this->dbServers[$lockDb] ) ) {
				// Parameters to construct a new database connection
				$config = $this->dbServers[$lockDb];
				$db = Database::factory( $config['type'], $config );
			} else {
				throw new UnexpectedValueException( "No server called '$lockDb'." );
			}

			$db->clearFlag( DBO_TRX );
			# If the connection drops, try to avoid letting the DB rollback
			# and release the locks before the file operations are finished.
			# This won't handle the case of DB server restarts however.
			$options = [];
			if ( $this->lockExpiry > 0 ) {
				$options['connTimeout'] = $this->lockExpiry;
			}
			$db->setSessionOptions( $options );
			$this->initConnection( $lockDb, $db );

			$this->conns[$lockDb] = $db;
		}

		return $this->conns[$lockDb];
	}

	/**
	 * Do additional initialization for new lock DB connection
	 *
	 * @param string $lockDb
	 * @param IDatabase $db
	 * @throws DBError
	 */
	protected function initConnection( $lockDb, IDatabase $db ) {
	}

	/**
	 * Checks if the DB has not recently had connection/query errors.
	 * This just avoids wasting time on doomed connection attempts.
	 *
	 * @param string $lockDb
	 * @return bool
	 */
	protected function cacheCheckFailures( $lockDb ) {
		return ( $this->safeDelay > 0 )
			? !$this->statusCache->get( $this->getMissKey( $lockDb ) )
			: true;
	}

	/**
	 * Log a lock request failure to the cache
	 *
	 * @param string $lockDb
	 * @return bool Success
	 */
	protected function cacheRecordFailure( $lockDb ) {
		return ( $this->safeDelay > 0 )
			? $this->statusCache->set( $this->getMissKey( $lockDb ), 1, $this->safeDelay )
			: true;
	}

	/**
	 * Get a cache key for recent query misses for a DB
	 *
	 * @param string $lockDb
	 * @return string
	 */
	protected function getMissKey( $lockDb ) {
		return 'dblockmanager:downservers:' . str_replace( ' ', '_', $lockDb );
	}

	/**
	 * Make sure remaining locks get cleared for sanity
	 */
	function __destruct() {
		$this->releaseAllLocks();
		foreach ( $this->conns as $db ) {
			$db->close();
		}
	}
}
