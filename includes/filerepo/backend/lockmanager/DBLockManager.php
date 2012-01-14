<?php

/**
 * Version of LockManager based on using DB table locks.
 * This is meant for multi-wiki systems that may share files.
 * All locks are blocking, so it might be useful to set a small
 * lock-wait timeout via server config to curtail deadlocks.
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
class DBLockManager extends LockManager {
	/** @var Array Map of DB names to server config */
	protected $dbServers; // (DB name => server config array)
	/** @var Array Map of bucket indexes to peer DB lists */
	protected $dbsByBucket; // (bucket index => (ldb1, ldb2, ...))
	/** @var BagOStuff */
	protected $statusCache;

	protected $lockExpiry; // integer number of seconds
	protected $safeDelay; // integer number of seconds

	protected $session = 0; // random integer
	/** @var Array Map Database connections (DB name => Database) */
	protected $conns = array();

	/**
	 * Construct a new instance from configuration.
	 * 
	 * $config paramaters include:
	 *     'dbServers'   : Associative array of DB names to server configuration.
	 *                     Configuration is an associative array that includes:
	 *                     'host'        - DB server name
	 *                     'dbname'      - DB name
	 *                     'type'        - DB type (mysql,postgres,...)
	 *                     'user'        - DB user
	 *                     'password'    - DB user password
	 *                     'tablePrefix' - DB table prefix
	 *                     'flags'       - DB flags (see DatabaseBase)
	 *     'dbsByBucket' : Array of 1-16 consecutive integer keys, starting from 0,
	 *                     each having an odd-numbered list of DB names (peers) as values.
	 *                     Any DB named 'localDBMaster' will automatically use the DB master
	 *                     settings for this wiki (without the need for a dbServers entry).
	 *     'lockExpiry'  : Lock timeout (seconds) for dropped connections. [optional]
	 *                     This tells the DB server how long to wait before assuming
	 *                     connection failure and releasing all the locks for a session.
	 *
	 * @param Array $config 
	 */
	public function __construct( array $config ) {
		$this->dbServers = isset( $config['dbServers'] )
			? $config['dbServers']
			: array(); // likely just using 'localDBMaster'
		// Sanitize dbsByBucket config to prevent PHP errors
		$this->dbsByBucket = array_filter( $config['dbsByBucket'], 'is_array' );
		$this->dbsByBucket = array_values( $this->dbsByBucket ); // consecutive

		if ( isset( $config['lockExpiry'] ) ) {
			$this->lockExpiry = $config['lockExpiry'];
		} else {
			$met = ini_get( 'max_execution_time' );
			$this->lockExpiry = $met ? $met : 60; // use some sane amount if 0
		}
		$this->safeDelay = ( $this->lockExpiry <= 0 )
			? 60 // pick a safe-ish number to match DB timeout default
			: $this->lockExpiry; // cover worst case

		foreach ( $this->dbsByBucket as $bucket ) {
			if ( count( $bucket ) > 1 ) {
				// Tracks peers that couldn't be queried recently to avoid lengthy
				// connection timeouts. This is useless if each bucket has one peer.
				$this->statusCache = wfGetMainCache();
				break;
			}
		}

		$this->session = '';
		for ( $i = 0; $i < 5; $i++ ) {
			$this->session .= mt_rand( 0, 2147483647 );
		}
		$this->session = wfBaseConvert( sha1( $this->session ), 16, 36, 31 );
	}

	/**
	 * @see LockManager::doLock()
	 */
	protected function doLock( array $paths, $type ) {
		$status = Status::newGood();

		$pathsToLock = array();
		// Get locks that need to be acquired (buckets => locks)...
		foreach ( $paths as $path ) {
			if ( isset( $this->locksHeld[$path][$type] ) ) {
				++$this->locksHeld[$path][$type];
			} elseif ( isset( $this->locksHeld[$path][self::LOCK_EX] ) ) {
				$this->locksHeld[$path][$type] = 1;
			} else {
				$bucket = $this->getBucketFromKey( $path );
				$pathsToLock[$bucket][] = $path;
			}
		}

		$lockedPaths = array(); // files locked in this attempt
		// Attempt to acquire these locks...
		foreach ( $pathsToLock as $bucket => $paths ) {
			// Try to acquire the locks for this bucket
			$res = $this->doLockingQueryAll( $bucket, $paths, $type );
			if ( $res === 'cantacquire' ) {
				// Resources already locked by another process.
				// Abort and unlock everything we just locked.
				foreach ( $paths as $path ) {
					$status->fatal( 'lockmanager-fail-acquirelock', $path );
				}
				$status->merge( $this->doUnlock( $lockedPaths, $type ) );
				return $status;
			} elseif ( $res !== true ) {
				// Couldn't contact any DBs for this bucket.
				// Abort and unlock everything we just locked.
				$status->fatal( 'lockmanager-fail-db-bucket', $bucket );
				$status->merge( $this->doUnlock( $lockedPaths, $type ) );
				return $status;
			}
			// Record these locks as active
			foreach ( $paths as $path ) {
				$this->locksHeld[$path][$type] = 1; // locked
			}
			// Keep track of what locks were made in this attempt
			$lockedPaths = array_merge( $lockedPaths, $paths );
		}

		return $status;
	}

	/**
	 * @see LockManager::doUnlock()
	 */
	protected function doUnlock( array $paths, $type ) {
		$status = Status::newGood();

		foreach ( $paths as $path ) {
			if ( !isset( $this->locksHeld[$path] ) ) {
				$status->warning( 'lockmanager-notlocked', $path );
			} elseif ( !isset( $this->locksHeld[$path][$type] ) ) {
				$status->warning( 'lockmanager-notlocked', $path );
			} else {
				--$this->locksHeld[$path][$type];
				if ( $this->locksHeld[$path][$type] <= 0 ) {
					unset( $this->locksHeld[$path][$type] );
				}
				if ( !count( $this->locksHeld[$path] ) ) {
					unset( $this->locksHeld[$path] ); // no SH or EX locks left for key
				}
			}
		}

		// Reference count the locks held and COMMIT when zero
		if ( !count( $this->locksHeld ) ) {
			$status->merge( $this->finishLockTransactions() );
		}

		return $status;
	}

	/**
	 * Get a connection to a lock DB and acquire locks on $paths.
	 * This does not use GET_LOCK() per http://bugs.mysql.com/bug.php?id=1118.
	 *
	 * @param $lockDb string
	 * @param $paths Array
	 * @param $type integer LockManager::LOCK_EX or LockManager::LOCK_SH
	 * @return bool Resources able to be locked
	 * @throws DBError
	 */
	protected function doLockingQuery( $lockDb, array $paths, $type ) {
		if ( $type == self::LOCK_EX ) { // writer locks
			$db = $this->getConnection( $lockDb );
			if ( !$db ) {
				return false; // bad config
			}
			$keys = array_unique( array_map( 'LockManager::sha1Base36', $paths ) );
			# Build up values for INSERT clause
			$data = array();
			foreach ( $keys as $key ) {
				$data[] = array( 'fle_key' => $key );
			}
			# Wait on any existing writers and block new ones if we get in
			$db->insert( 'filelocks_exclusive', $data, __METHOD__ );
		}
		return true;
	}

	/**
	 * Attempt to acquire locks with the peers for a bucket.
	 * This should avoid throwing any exceptions.
	 *
	 * @param $bucket integer
	 * @param $paths Array List of resource keys to lock
	 * @param $type integer LockManager::LOCK_EX or LockManager::LOCK_SH
	 * @return bool|string One of (true, 'cantacquire', 'dberrors')
	 */
	protected function doLockingQueryAll( $bucket, array $paths, $type ) {
		$yesVotes = 0; // locks made on trustable DBs
		$votesLeft = count( $this->dbsByBucket[$bucket] ); // remaining DBs
		$quorum = floor( $votesLeft/2 + 1 ); // simple majority
		// Get votes for each DB, in order, until we have enough...
		foreach ( $this->dbsByBucket[$bucket] as $lockDb ) {
			// Check that DB is not *known* to be down
			if ( $this->cacheCheckFailures( $lockDb ) ) {
				try {
					// Attempt to acquire the lock on this DB
					if ( !$this->doLockingQuery( $lockDb, $paths, $type ) ) {
						return 'cantacquire'; // vetoed; resource locked
					}
					++$yesVotes; // success for this peer
					if ( $yesVotes >= $quorum ) {
						return true; // lock obtained
					}
				} catch ( DBConnectionError $e ) {
					$this->cacheRecordFailure( $lockDb );
				} catch ( DBError $e ) {
					if ( $this->lastErrorIndicatesLocked( $lockDb ) ) {
						return 'cantacquire'; // vetoed; resource locked
					}
				}
			}
			--$votesLeft;
			$votesNeeded = $quorum - $yesVotes;
			if ( $votesNeeded > $votesLeft ) {
				// In "trust cache" mode we don't have to meet the quorum
				break; // short-circuit
			}
		}
		// At this point, we must not have meet the quorum
		return 'dberrors'; // not enough votes to ensure correctness
	}

	/**
	 * Get (or reuse) a connection to a lock DB
	 *
	 * @param $lockDb string
	 * @return Database
	 * @throws DBError
	 */
	protected function getConnection( $lockDb ) {
		if ( !isset( $this->conns[$lockDb] ) ) {
			$db = null;
			if ( $lockDb === 'localDBMaster' ) {
				$lb = wfGetLBFactory()->newMainLB();
				$db = $lb->getConnection( DB_MASTER );
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
			$this->conns[$lockDb]->begin(); // start transaction
		}
		return $this->conns[$lockDb];
	}

	/**
	 * Do additional initialization for new lock DB connection
	 *
	 * @param $lockDb string
	 * @param $db DatabaseBase
	 * @return void
	 * @throws DBError
	 */
	protected function initConnection( $lockDb, DatabaseBase $db ) {}

	/**
	 * Commit all changes to lock-active databases.
	 * This should avoid throwing any exceptions.
	 *
	 * @return Status
	 */
	protected function finishLockTransactions() {
		$status = Status::newGood();
		foreach ( $this->conns as $lockDb => $db ) {
			if ( $db->trxLevel() ) { // in transaction
				try {
					$db->rollback(); // finish transaction and kill any rows
				} catch ( DBError $e ) {
					$status->fatal( 'lockmanager-fail-db-release', $lockDb );
				}
			}
		}
		return $status;
	}

	/**
	 * Check if the last DB error for $lockDb indicates
	 * that a requested resource was locked by another process.
	 * This should avoid throwing any exceptions.
	 * 
	 * @param $lockDb string
	 * @return bool
	 */
	protected function lastErrorIndicatesLocked( $lockDb ) {
		if ( isset( $this->conns[$lockDb] ) ) { // sanity
			$db = $this->conns[$lockDb];
			return ( $db->wasDeadlock() || $db->wasLockTimeout() );
		}
		return false;
	}

	/**
	 * Checks if the DB has not recently had connection/query errors.
	 * This just avoids wasting time on doomed connection attempts.
	 * 
	 * @param $lockDb string
	 * @return bool
	 */
	protected function cacheCheckFailures( $lockDb ) {
		if ( $this->statusCache && $this->safeDelay > 0 ) {
			$path = $this->getMissKey( $lockDb );
			$misses = $this->statusCache->get( $path );
			return !$misses;
		}
		return true;
	}

	/**
	 * Log a lock request failure to the cache
	 *
	 * @param $lockDb string
	 * @return bool Success
	 */
	protected function cacheRecordFailure( $lockDb ) {
		if ( $this->statusCache && $this->safeDelay > 0 ) {
			$path = $this->getMissKey( $lockDb );
			$misses = $this->statusCache->get( $path );
			if ( $misses ) {
				return $this->statusCache->incr( $path );
			} else {
				return $this->statusCache->add( $path, 1, $this->safeDelay );
			}
		}
		return true;
	}

	/**
	 * Get a cache key for recent query misses for a DB
	 *
	 * @param $lockDb string
	 * @return string
	 */
	protected function getMissKey( $lockDb ) {
		return 'lockmanager:querymisses:' . str_replace( ' ', '_', $lockDb );
	}

	/**
	 * Get the bucket for resource path.
	 * This should avoid throwing any exceptions.
	 *
	 * @param $path string
	 * @return integer
	 */
	protected function getBucketFromKey( $path ) {
		$prefix = substr( sha1( $path ), 0, 2 ); // first 2 hex chars (8 bits)
		return intval( base_convert( $prefix, 16, 10 ) ) % count( $this->dbsByBucket );
	}

	/**
	 * Make sure remaining locks get cleared for sanity
	 */
	function __destruct() {
		foreach ( $this->conns as $lockDb => $db ) {
			if ( $db->trxLevel() ) { // in transaction
				try {
					$db->rollback(); // finish transaction and kill any rows
				} catch ( DBError $e ) {
					// oh well
				}
			}
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
	/** @var Array Mapping of lock types to the type actually used */
	protected $lockTypeMap = array(
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	);

	protected function initConnection( $lockDb, DatabaseBase $db ) {
		# Let this transaction see lock rows from other transactions
		$db->query( "SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;" );
	}

	protected function doLockingQuery( $lockDb, array $paths, $type ) {
		$db = $this->getConnection( $lockDb );
		if ( !$db ) {
			return false;
		}
		$keys = array_unique( array_map( 'LockManager::sha1Base36', $paths ) );
		# Build up values for INSERT clause
		$data = array();
		foreach ( $keys as $key ) {
			$data[] = array( 'fls_key' => $key, 'fls_session' => $this->session );
		}
		# Block new writers...
		$db->insert( 'filelocks_shared', $data, __METHOD__, array( 'IGNORE' ) );
		# Actually do the locking queries...
		if ( $type == self::LOCK_SH ) { // reader locks
			# Bail if there are any existing writers...
			$blocked = $db->selectField( 'filelocks_exclusive', '1',
				array( 'fle_key' => $keys ),
				__METHOD__
			);
			# Prospective writers that haven't yet updated filelocks_exclusive
			# will recheck filelocks_shared after doing so and bail due to our entry.
		} else { // writer locks
			$encSession = $db->addQuotes( $this->session );
			# Bail if there are any existing writers...
			# The may detect readers, but the safe check for them is below.
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
		return !$blocked;
	}
}
