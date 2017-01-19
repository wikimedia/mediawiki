<?php

use Wikimedia\Rdbms\DBError;

/**
 * PostgreSQL version of DBLockManager that supports shared locks.
 * All locks are non-blocking, which avoids deadlocks.
 *
 * @ingroup LockManager
 */
class PostgreSqlLockManager extends DBLockManager implements InterruptMutexManager {
	/** @var array Mapping of lock types to the type actually used */
	protected $lockTypeMap = [
		self::LOCK_SH => self::LOCK_SH,
		self::LOCK_UW => self::LOCK_SH,
		self::LOCK_EX => self::LOCK_EX
	];

	protected function doGetLocksOnServer( $lockSrv, array $paths, $type ) {
		$status = StatusValue::newGood();
		if ( !count( $paths ) ) {
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

	public function acquireQueuedMutex( $key, $timeout = 0 ) {
		return $this->collectPledgeQuorum(
			$this->getBucketFromPath( $key ),
			function ( $lockSrv ) use ( $key, $timeout ) {
				$status = StatusValue::newGood();
				$bigint = Wikimedia\base_convert( substr( sha1( $key ), 0, 15 ), 16, 10 );
				$timeoutMs = $timeout * 1000;

				$db = $this->getConnection( $lockSrv );
				// https://www.postgresql.org/docs/9.3/static/runtime-config-client.html
				$db->query( "SET lock_timeout TO {$db->addQuotes( $timeoutMs )}", __METHOD__ );
				$res = $db->query(
					"SELECT pg_advisory_lock({$db->addQuotes( $bigint )}) AS status",
					__METHOD__,
					true // ignore timeout errors
				);
				$db->query( "SET lock_timeout TO DEFAULT", __METHOD__ );

				$row = $res ? $res->fetchObject() : false;
				if ( !$row || $row->status === 'f' ) {
					$status->fatal( 'lockmanager-fail-acquirelock', $key );
				}

				return $status;
			}
		);
	}

	public function releaseQueuedMutex( $key ) {
		return $this->releasePledges(
			$this->getBucketFromPath( $key ),
			function ( $lockSrv ) use ( $key ) {
				$status = StatusValue::newGood();
				$bigint = Wikimedia\base_convert( substr( sha1( $key ), 0, 15 ), 16, 10 );

				$db = $this->getConnection( $lockSrv );
				$res = $db->query(
					"SELECT pg_advisory_unlock({$db->addQuotes( $bigint )}) AS status",
					__METHOD__
				);

				$row = $res->fetchObject();
				if ( $row->status === 'f' ) {
					$status->fatal( 'lockmanager-fail-releaselock', $key );
				}

				return $status;
			}
		);
	}
}
