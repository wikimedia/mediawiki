<?php

use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\IDatabase;

/**
 * MySQL version of DBLockManager that supports shared locks.
 *
 * Do NOT use this on connection handles that are also being used for anything
 * else as the transaction isolation will be wrong and all the other changes will
 * get rolled back when the locks release!
 *
 * All lock servers must have the innodb table defined in maintenance/locking/filelocks.sql.
 * All locks are non-blocking, which avoids deadlocks.
 *
 * @ingroup LockManager
 */
class MySqlLockManager extends DBLockManager {
	/** @var array Mapping of lock types to the type actually used */
	protected $lockTypeMap = [
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	];

	public function __construct( array $config ) {
		parent::__construct( $config );

		$this->session = substr( $this->session, 0, 31 ); // fit to field
	}

	protected function initConnection( $lockDb, IDatabase $db ) {
		# Let this transaction see lock rows from other transactions
		$db->query( "SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;", __METHOD__ );
		# Do everything in a transaction as it all gets rolled back eventually
		$db->startAtomic( __CLASS__ );
	}

	/**
	 * Get a connection to a lock DB and acquire locks on $paths.
	 * This does not use GET_LOCK() per https://bugs.mysql.com/bug.php?id=1118.
	 *
	 * @see DBLockManager::getLocksOnServer()
	 * @param string $lockSrv
	 * @param array $paths
	 * @param string $type
	 * @return StatusValue
	 */
	protected function doGetLocksOnServer( $lockSrv, array $paths, $type ) {
		$status = StatusValue::newGood();

		$db = $this->getConnection( $lockSrv ); // checked in isServerUp()

		$keys = []; // list of hash keys for the paths
		$data = []; // list of rows to insert
		$checkEXKeys = []; // list of hash keys that this has no EX lock on
		# Build up values for INSERT clause
		foreach ( $paths as $path ) {
			$key = $this->sha1Base36Absolute( $path );
			$keys[] = $key;
			$data[] = [ 'fls_key' => $key, 'fls_session' => $this->session ];
			if ( !isset( $this->locksHeld[$path][self::LOCK_EX] ) ) {
				$checkEXKeys[] = $key; // this has no EX lock on $key itself
			}
		}

		# Block new writers (both EX and SH locks leave entries here)...
		$db->insert( 'filelocks_shared', $data, __METHOD__, [ 'IGNORE' ] );
		# Actually do the locking queries...
		if ( $type == self::LOCK_SH ) { // reader locks
			# Bail if there are any existing writers...
			if ( count( $checkEXKeys ) ) {
				$blocked = $db->selectField(
					'filelocks_exclusive',
					'1',
					[ 'fle_key' => $checkEXKeys ],
					__METHOD__
				);
			} else {
				$blocked = false;
			}
			# Other prospective writers that haven't yet updated filelocks_exclusive
			# will recheck filelocks_shared after doing so and bail due to this entry.
		} else { // writer locks
			$encSession = $db->addQuotes( $this->session );
			# Bail if there are any existing writers...
			# This may detect readers, but the safe check for them is below.
			# Note: if two writers come at the same time, both bail :)
			$blocked = $db->selectField(
				'filelocks_shared',
				'1',
				[ 'fls_key' => $keys, "fls_session != $encSession" ],
				__METHOD__
			);
			if ( !$blocked ) {
				# Build up values for INSERT clause
				$data = [];
				foreach ( $keys as $key ) {
					$data[] = [ 'fle_key' => $key ];
				}
				# Block new readers/writers...
				$db->insert( 'filelocks_exclusive', $data, __METHOD__ );
				# Bail if there are any existing readers...
				$blocked = $db->selectField(
					'filelocks_shared',
					'1',
					[ 'fls_key' => $keys, "fls_session != $encSession" ],
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
	 * @return StatusValue
	 */
	protected function releaseAllLocks() {
		$status = StatusValue::newGood();

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
