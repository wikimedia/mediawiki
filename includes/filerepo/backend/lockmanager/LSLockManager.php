<?php

/**
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
 */
class LSLockManager extends LockManager {
	/** @var Array Mapping of lock types to the type actually used */
	protected $lockTypeMap = array(
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	);

	/** @var Array Map of server names to server config */
	protected $lockServers; // (server name => server config array)
	/** @var Array Map of bucket indexes to peer server lists */
	protected $srvsByBucket; // (bucket index => (lsrv1, lsrv2, ...))

	/** @var Array Map of (locked key => lock type => count) */
	protected $locksHeld = array();
	/** @var Array Map Server connections (server name => resource) */
	protected $conns = array();

	protected $connTimeout; // float number of seconds
	protected $session = ''; // random SHA-1 string

	/**
	 * Construct a new instance from configuration.
	 * $config paramaters include:
	 *     'lockServers'  : Associative array of server names to configuration.
	 *                      Configuration is an associative array that includes:
	 *                      'host'    - IP address/hostname
	 *                      'port'    - TCP port
	 *                      'authKey' - Secret string the lock server uses
	 *     'srvsByBucket' : Array of 1-16 consecutive integer keys, starting from 0,
	 *                      each having an odd-numbered list of server names (peers) as values.
	 *     'connTimeout'  : Lock server connection attempt timeout. [optional]
	 *
	 * @param Array $config 
	 */
	public function __construct( array $config ) {
		$this->lockServers = $config['lockServers'];
		// Sanitize srvsByBucket config to prevent PHP errors
		$this->srvsByBucket = array_filter( $config['srvsByBucket'], 'is_array' );
		$this->srvsByBucket = array_values( $this->srvsByBucket ); // consecutive

		if ( isset( $config['connTimeout'] ) ) {
			$this->connTimeout = $config['connTimeout'];
		} else {
			$this->connTimeout = 3; // use some sane amount
		}

		$this->session = '';
		for ( $i = 0; $i < 5; $i++ ) {
			$this->session .= mt_rand( 0, 2147483647 );
		}
		$this->session = wfBaseConvert( sha1( $this->session ), 16, 36, 31 );
	}

	protected function doLock( array $keys, $type ) {
		$status = Status::newGood();

		$keysToLock = array();
		// Get locks that need to be acquired (buckets => locks)...
		foreach ( $keys as $key ) {
			if ( isset( $this->locksHeld[$key][$type] ) ) {
				++$this->locksHeld[$key][$type];
			} elseif ( isset( $this->locksHeld[$key][self::LOCK_EX] ) ) {
				$this->locksHeld[$key][$type] = 1;
			} else {
				$bucket = $this->getBucketFromKey( $key );
				$keysToLock[$bucket][] = $key;
			}
		}

		$lockedKeys = array(); // files locked in this attempt
		// Attempt to acquire these locks...
		foreach ( $keysToLock as $bucket => $keys ) {
			// Try to acquire the locks for this bucket
			$res = $this->doLockingRequestAll( $bucket, $keys, $type );
			if ( $res === 'cantacquire' ) {
				// Resources already locked by another process.
				// Abort and unlock everything we just locked.
				$status->fatal( 'lockmanager-fail-acquirelocks', implode( ', ', $keys ) );
				$status->merge( $this->doUnlock( $lockedKeys, $type ) );
				return $status;
			} elseif ( $res !== true ) {
				// Couldn't contact any servers for this bucket.
				// Abort and unlock everything we just locked.
				$status->fatal( 'lockmanager-fail-acquirelocks', implode( ', ', $keys ) );
				$status->merge( $this->doUnlock( $lockedKeys, $type ) );
				return $status;
			}
			// Record these locks as active
			foreach ( $keys as $key ) {
				$this->locksHeld[$key][$type] = 1; // locked
			}
			// Keep track of what locks were made in this attempt
			$lockedKeys = array_merge( $lockedKeys, $keys );
		}

		return $status;
	}

	protected function doUnlock( array $keys, $type ) {
		$status = Status::newGood();

		foreach ( $keys as $key ) {
			if ( !isset( $this->locksHeld[$key] ) ) {
				$status->warning( 'lockmanager-notlocked', $key );
			} elseif ( !isset( $this->locksHeld[$key][$type] ) ) {
				$status->warning( 'lockmanager-notlocked', $key );
			} else {
				--$this->locksHeld[$key][$type];
				if ( $this->locksHeld[$key][$type] <= 0 ) {
					unset( $this->locksHeld[$key][$type] );
				}
				if ( !count( $this->locksHeld[$key] ) ) {
					unset( $this->locksHeld[$key] ); // no SH or EX locks left for key
				}
			}
		}

		// Reference count the locks held and release locks when zero
		if ( !count( $this->locksHeld ) ) {
			$status->merge( $this->releaseLocks() );
		}

		return $status;
	}

	/**
	 * Get a connection to a lock server and acquire locks on $keys
	 *
	 * @param $lockSrv string
	 * @param $keys Array
	 * @param $type integer LockManager::LOCK_EX or LockManager::LOCK_SH
	 * @return bool Resources able to be locked
	 */
	protected function doLockingRequest( $lockSrv, array $keys, $type ) {
		if ( $type == self::LOCK_SH ) { // reader locks
			$type = 'SH';
		} elseif ( $type == self::LOCK_EX ) { // writer locks
			$type = 'EX';
		} else {
			return true; // ok...
		}

		// Send out the command and get the response...
		$response = $this->sendCommand( $lockSrv, 'ACQUIRE', $type, $keys );

		return ( $response === 'ACQUIRED' );
	}

	/**
	 * Send a command and get back the response
	 *
	 * @param $lockSrv string
	 * @param $action string
	 * @param $type string
	 * @param $values Array
	 * @return string|false
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
	 * Attempt to acquire locks with the peers for a bucket
	 *
	 * @param $bucket integer
	 * @param $keys Array List of resource keys to lock
	 * @param $type integer LockManager::LOCK_EX or LockManager::LOCK_SH
	 * @return bool|string One of (true, 'cantacquire', 'srverrors')
	 */
	protected function doLockingRequestAll( $bucket, array $keys, $type ) {
		$yesVotes = 0; // locks made on trustable servers
		$votesLeft = count( $this->srvsByBucket[$bucket] ); // remaining peers
		$quorum = floor( $votesLeft/2 + 1 ); // simple majority
		// Get votes for each peer, in order, until we have enough...
		foreach ( $this->srvsByBucket[$bucket] as $index => $lockSrv ) {
			// Attempt to acquire the lock on this peer
			if ( !$this->doLockingRequest( $lockSrv, $keys, $type ) ) {
				return 'cantacquire'; // vetoed; resource locked
			}
			++$yesVotes; // success for this peer
			if ( $yesVotes >= $quorum ) {
				return true; // lock obtained
			}
			$votesLeft--;
			$votesNeeded = $quorum - $yesVotes;
			if ( $votesNeeded > $votesLeft ) {
				// In "trust cache" mode we don't have to meet the quorum
				break; // short-circuit
			}
		}
		// At this point, we must not have meet the quorum
		return 'srverrors'; // not enough votes to ensure correctness
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
	 * Release all locks that this session is holding
	 *
	 * @return Status
	 */
	protected function releaseLocks() {
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
	 * Get the bucket for lock key
	 *
	 * @param $key string (31 char hex key)
	 * @return integer
	 */
	protected function getBucketFromKey( $key ) {
		$prefix = substr( $key, 0, 2 ); // first 2 hex chars (8 bits)
		return intval( base_convert( $prefix, 16, 10 ) ) % count( $this->srvsByBucket );
	}

	/**
	 * Make sure remaining locks get cleared for sanity
	 */
	function __destruct() {
		$this->releaseLocks();
		foreach ( $this->conns as $lockSrv => $conn ) {
			fclose( $conn );
		}
	}
}
