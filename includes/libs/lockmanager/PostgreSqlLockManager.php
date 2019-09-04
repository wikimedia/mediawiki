<?php

use Wikimedia\Rdbms\DBError;

/**
 * PostgreSQL version of DBLockManager that supports shared locks.
 * All locks are non-blocking, which avoids deadlocks.
 *
 * @ingroup LockManager
 * @phan-file-suppress PhanUndeclaredConstant Phan doesn't read constants in LockManager
 *   when accessed via self::
 */
class PostgreSqlLockManager extends DBLockManager {
	/** @var array Mapping of lock types to the type actually used */
	protected $lockTypeMap = [
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	];

	protected function doGetLocksOnServer( $lockSrv, array $paths, $type ) {
		$status = StatusValue::newGood();
		if ( $paths === [] ) {
			return $status; // nothing to lock
		}

		$db = $this->getConnection( $lockSrv ); // checked in isServerUp()
		$bigints = array_unique( array_map(
			function ( $key ) {
				return Wikimedia\base_convert( substr( $key, 0, 15 ), 16, 10 );
			},
			array_map( [ $this, 'sha1Base16Absolute' ], $paths )
		) );

		// Try to acquire all the locks...
		$fields = [];
		foreach ( $bigints as $bigint ) {
			$fields[] = ( $type == self::LOCK_SH )
				? "pg_try_advisory_lock_shared({$db->addQuotes( $bigint )}) AS K$bigint"
				: "pg_try_advisory_lock({$db->addQuotes( $bigint )}) AS K$bigint";
		}
		$res = $db->query( 'SELECT ' . implode( ', ', $fields ), __METHOD__ );
		$row = $res->fetchRow();

		if ( in_array( 'f', $row ) ) {
			// Release any acquired locks if some could not be acquired...
			$fields = [];
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
	 * @return StatusValue
	 */
	protected function releaseAllLocks() {
		$status = StatusValue::newGood();

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
