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

/**
 * Version of LockManager based on using named/row DB locks.
 *
 * This is meant for multi-wiki systems that may share files.
 *
 * All lock requests for a resource, identified by a hash string, will map
 * to one bucket. Each bucket maps to one or several peer DBs, each on their
 * own server, all having the filelocks.sql tables (with row-level locking).
 * A majority of peer DBs must agree for a lock to be acquired.
 *
 * Caching is used to avoid hitting servers that are down.
 *
 * @ingroup LockManager
 * @since 1.19
 */
abstract class DBLockManager extends QuorumLockManager {
	/** @var array Map of DB names to server config */
	protected $dbServers; // (DB name => server config array)
	/** @var BagOStuff */
	protected $statusCache;

	protected $lockExpiry; // integer number of seconds
	protected $safeDelay; // integer number of seconds

	protected $session = 0; // random integer
	/** @var array Map Database connections (DB name => Database) */
	protected $conns = array();

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
	 *                     - flags       : DB flags (see DatabaseBase)
	 *   - dbsByBucket : Array of 1-16 consecutive integer keys, starting from 0,
	 *                   each having an odd-numbered list of DB names (peers) as values.
	 *                   Any DB named 'localDBMaster' will automatically use the DB master
	 *                   settings for this wiki (without the need for a dbServers entry).
	 *                   Only use 'localDBMaster' if the domain is a valid wiki ID.
	 *   - lockExpiry  : Lock timeout (seconds) for dropped connections. [optional]
	 *                   This tells the DB server how long to wait before assuming
	 *                   connection failure and releasing all the locks for a session.
	 */
	public function __construct( array $config ) {
		parent::__construct( $config );

		$this->dbServers = isset( $config['dbServers'] )
			? $config['dbServers']
			: array(); // likely just using 'localDBMaster'
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

		foreach ( $this->srvsByBucket as $bucket ) {
			if ( count( $bucket ) > 1 ) { // multiple peers
				// Tracks peers that couldn't be queried recently to avoid lengthy
				// connection timeouts. This is useless if each bucket has one peer.
				try {
					$this->statusCache = ObjectCache::newAccelerator( array() );
				} catch ( Exception $e ) {
					trigger_error( __CLASS__ .
						" using multiple DB peers without apc, xcache, or wincache." );
				}
				break;
			}
		}

		$this->session = wfRandomString( 31 );
	}

	// @todo change this code to work in one batch
	protected function getLocksOnServer( $lockSrv, array $pathsByType ) {
		$status = Status::newGood();
		foreach ( $pathsByType as $type => $paths ) {
			$status->merge( $this->doGetLocksOnServer( $lockSrv, $paths, $type ) );
		}

		return $status;
	}

	protected function freeLocksOnServer( $lockSrv, array $pathsByType ) {
		return Status::newGood();
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
	 * @return DatabaseBase
	 * @throws DBError
	 */
	protected function getConnection( $lockDb ) {
		if ( !isset( $this->conns[$lockDb] ) ) {
			$db = null;
			if ( $lockDb === 'localDBMaster' ) {
				$lb = wfGetLBFactory()->getMainLB( $this->domain );
				$db = $lb->getConnection( DB_MASTER, array(), $this->domain );
			} elseif ( isset( $this->dbServers[$lockDb] ) ) {
				$config = $this->dbServers[$lockDb];
				$db = DatabaseBase::factory( $config['type'], $config );
			}
			if ( !$db ) {
				return null; // config error?
			}
			$this->conns[$lockDb] = $db;
			$this->conns[$lockDb]->clearFlag( DBO_TRX );
			# If the connection drops, try to avoid letting the DB rollback
			# and release the locks before the file operations are finished.
			# This won't handle the case of DB server restarts however.
			$options = array();
			if ( $this->lockExpiry > 0 ) {
				$options['connTimeout'] = $this->lockExpiry;
			}
			$this->conns[$lockDb]->setSessionOptions( $options );
			$this->initConnection( $lockDb, $this->conns[$lockDb] );
		}
		if ( !$this->conns[$lockDb]->trxLevel() ) {
			$this->conns[$lockDb]->begin( __METHOD__ ); // start transaction
		}

		return $this->conns[$lockDb];
	}

	/**
	 * Do additional initialization for new lock DB connection
	 *
	 * @param string $lockDb
	 * @param DatabaseBase $db
	 * @throws DBError
	 */
	protected function initConnection( $lockDb, DatabaseBase $db ) {
	}

	/**
	 * Checks if the DB has not recently had connection/query errors.
	 * This just avoids wasting time on doomed connection attempts.
	 *
	 * @param string $lockDb
	 * @return bool
	 */
	protected function cacheCheckFailures( $lockDb ) {
		return ( $this->statusCache && $this->safeDelay > 0 )
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
		return ( $this->statusCache && $this->safeDelay > 0 )
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
		$lockDb = ( $lockDb === 'localDBMaster' ) ? wfWikiID() : $lockDb; // non-relative
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

/**
 * MySQL version of DBLockManager that supports shared locks.
 * All locks are non-blocking, which avoids deadlocks.
 *
 * @ingroup LockManager
 */
class MySqlLockManager extends DBLockManager {
	/** @var array Mapping of lock types to the type actually used */
	protected $lockTypeMap = array(
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	);

	/**
	 * @param string $lockDb
	 * @param DatabaseBase $db
	 */
	protected function initConnection( $lockDb, DatabaseBase $db ) {
		# Let this transaction see lock rows from other transactions
		$db->query( "SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;" );
	}

	/**
	 * Get a connection to a lock DB and acquire locks on $paths.
	 * This does not use GET_LOCK() per http://bugs.mysql.com/bug.php?id=1118.
	 *
	 * @see DBLockManager::getLocksOnServer()
	 * @param string $lockSrv
	 * @param array $paths
	 * @param string $type
	 * @return Status
	 */
	protected function doGetLocksOnServer( $lockSrv, array $paths, $type ) {
		$status = Status::newGood();

		$db = $this->getConnection( $lockSrv ); // checked in isServerUp()

		$keys = array(); // list of hash keys for the paths
		$data = array(); // list of rows to insert
		$checkEXKeys = array(); // list of hash keys that this has no EX lock on
		# Build up values for INSERT clause
		foreach ( $paths as $path ) {
			$key = $this->sha1Base36Absolute( $path );
			$keys[] = $key;
			$data[] = array( 'fls_key' => $key, 'fls_session' => $this->session );
			if ( !isset( $this->locksHeld[$path][self::LOCK_EX] ) ) {
				$checkEXKeys[] = $key;
			}
		}

		# Block new writers (both EX and SH locks leave entries here)...
		$db->insert( 'filelocks_shared', $data, __METHOD__, array( 'IGNORE' ) );
		# Actually do the locking queries...
		if ( $type == self::LOCK_SH ) { // reader locks
			$blocked = false;
			# Bail if there are any existing writers...
			if ( count( $checkEXKeys ) ) {
				$blocked = $db->selectField( 'filelocks_exclusive', '1',
					array( 'fle_key' => $checkEXKeys ),
					__METHOD__
				);
			}
			# Other prospective writers that haven't yet updated filelocks_exclusive
			# will recheck filelocks_shared after doing so and bail due to this entry.
		} else { // writer locks
			$encSession = $db->addQuotes( $this->session );
			# Bail if there are any existing writers...
			# This may detect readers, but the safe check for them is below.
			# Note: if two writers come at the same time, both bail :)
			$blocked = $db->selectField( 'filelocks_shared', '1',
				array( 'fls_key' => $keys, "fls_session != $encSession" ),
				__METHOD__
			);
			if ( !$blocked ) {
				# Build up values for INSERT clause
				$data = array();
				foreach ( $keys as $key ) {
					$data[] = array( 'fle_key' => $key );
				}
				# Block new readers/writers...
				$db->insert( 'filelocks_exclusive', $data, __METHOD__ );
				# Bail if there are any existing readers...
				$blocked = $db->selectField( 'filelocks_shared', '1',
					array( 'fls_key' => $keys, "fls_session != $encSession" ),
					__METHOD__
				);
			}
		}

		if ( $blocked ) {
			foreach ( $paths as $path ) {
				$status->fatal( 'lockmanager-fail-acquirelock', $path );
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

		foreach ( $this->conns as $lockDb => $db ) {
			if ( $db->trxLevel() ) { // in transaction
				try {
					$db->rollback( __METHOD__ ); // finish transaction and kill any rows
				} catch ( DBError $e ) {
					$status->fatal( 'lockmanager-fail-db-release', $lockDb );
				}
			}
		}

		return $status;
	}
}

/**
 * PostgreSQL version of DBLockManager that supports shared locks.
 * All locks are non-blocking, which avoids deadlocks.
 *
 * @ingroup LockManager
 */
class PostgreSqlLockManager extends DBLockManager {
	/** @var array Mapping of lock types to the type actually used */
	protected $lockTypeMap = array(
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	);

	protected function doGetLocksOnServer( $lockSrv, array $paths, $type ) {
		$status = Status::newGood();
		if ( !count( $paths ) ) {
			return $status; // nothing to lock
		}

		$db = $this->getConnection( $lockSrv ); // checked in isServerUp()
		$bigints = array_unique( array_map(
			function ( $key ) {
				return wfBaseConvert( substr( $key, 0, 15 ), 16, 10 );
			},
			array_map( array( $this, 'sha1Base16Absolute' ), $paths )
		) );

		// Try to acquire all the locks...
		$fields = array();
		foreach ( $bigints as $bigint ) {
			$fields[] = ( $type == self::LOCK_SH )
				? "pg_try_advisory_lock_shared({$db->addQuotes( $bigint )}) AS K$bigint"
				: "pg_try_advisory_lock({$db->addQuotes( $bigint )}) AS K$bigint";
		}
		$res = $db->query( 'SELECT ' . implode( ', ', $fields ), __METHOD__ );
		$row = (array)$res->fetchObject();

		if ( in_array( 'f', $row ) ) {
			// Release any acquired locks if some could not be acquired...
			$fields = array();
			foreach ( $row as $kbigint => $ok ) {
				if ( $ok === 't' ) { // locked
					$bigint = substr( $kbigint, 1 ); // strip off the "K"
					$fields[] = ( $type == self::LOCK_SH )
						? "pg_advisory_unlock_shared({$db->addQuotes( $bigint )})"
						: "pg_advisory_unlock({$db->addQuotes( $bigint )})";
				}
			}
			if ( count( $fields ) ) {
				$db->query( 'SELECT ' . implode( ', ', $fields ), __METHOD__ );
			}
			foreach ( $paths as $path ) {
				$status->fatal( 'lockmanager-fail-acquirelock', $path );
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

		foreach ( $this->conns as $lockDb => $db ) {
			try {
				$db->query( "SELECT pg_advisory_unlock_all()", __METHOD__ );
			} catch ( DBError $e ) {
				$status->fatal( 'lockmanager-fail-db-release', $lockDb );
			}
		}

		return $status;
	}
}
