<?php
/**
 * Object caching using a SQL database.
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
 * @ingroup Cache
 */

/**
 * Class to store objects in the database
 *
 * @ingroup Cache
 */
class SqlBagOStuff extends BagOStuff {
	/**
	 * @var LoadBalancer
	 */
	var $lb;

	var $serverInfos;
	var $serverNames;
	var $numServers;
	var $conns;
	var $lastExpireAll = 0;
	var $purgePeriod = 100;
	var $shards = 1;
	var $tableName = 'objectcache';

	protected $connFailureTimes = array(); // UNIX timestamps
	protected $connFailureErrors = array(); // exceptions

	/**
	 * Constructor. Parameters are:
	 *   - server:      A server info structure in the format required by each
	 *                  element in $wgDBServers.
	 *
	 *   - servers:     An array of server info structures describing a set of
	 *                  database servers to distribute keys to. If this is
	 *                  specified, the "server" option will be ignored.
	 *
	 *   - purgePeriod: The average number of object cache requests in between
	 *                  garbage collection operations, where expired entries
	 *                  are removed from the database. Or in other words, the
	 *                  reciprocal of the probability of purging on any given
	 *                  request. If this is set to zero, purging will never be
	 *                  done.
	 *
	 *   - tableName:   The table name to use, default is "objectcache".
	 *
	 *   - shards:      The number of tables to use for data storage on each server.
	 *                  If this is more than 1, table names will be formed in the style
	 *                  objectcacheNNN where NNN is the shard index, between 0 and
	 *                  shards-1. The number of digits will be the minimum number
	 *                  required to hold the largest shard index. Data will be
	 *                  distributed across all tables by key hash. This is for
	 *                  MySQL bugs 61735 and 61736.
	 *
	 * @param $params array
	 */
	public function __construct( $params ) {
		if ( isset( $params['servers'] ) ) {
			$this->serverInfos = $params['servers'];
			$this->numServers = count( $this->serverInfos );
			$this->serverNames = array();
			foreach ( $this->serverInfos as $i => $info ) {
				$this->serverNames[$i] = isset( $info['host'] ) ? $info['host'] : "#$i";
			}
		} elseif ( isset( $params['server'] ) ) {
			$this->serverInfos = array( $params['server'] );
			$this->numServers = count( $this->serverInfos );
		} else {
			$this->serverInfos = false;
			$this->numServers = 1;
		}
		if ( isset( $params['purgePeriod'] ) ) {
			$this->purgePeriod = intval( $params['purgePeriod'] );
		}
		if ( isset( $params['tableName'] ) ) {
			$this->tableName = $params['tableName'];
		}
		if ( isset( $params['shards'] ) ) {
			$this->shards = intval( $params['shards'] );
		}
	}

	/**
	 * Get a connection to the specified database
	 *
	 * @param $serverIndex integer
	 * @return DatabaseBase
	 */
	protected function getDB( $serverIndex ) {
		global $wgDebugDBTransactions;

		if ( !isset( $this->conns[$serverIndex] ) ) {
			if ( $serverIndex >= $this->numServers ) {
				throw new MWException( __METHOD__ . ": Invalid server index \"$serverIndex\"" );
			}

			# Don't keep timing out trying to connect for each call if the DB is down
			if ( isset( $this->connFailureErrors[$serverIndex] )
				&& ( time() - $this->connFailureTimes[$serverIndex] ) < 60 )
			{
				throw $this->connFailureErrors[$serverIndex];
			}

			# If server connection info was given, use that
			if ( $this->serverInfos ) {
				if ( $wgDebugDBTransactions ) {
					wfDebug( "Using provided serverInfo for SqlBagOStuff\n" );
				}
				$info = $this->serverInfos[$serverIndex];
				$type = isset( $info['type'] ) ? $info['type'] : 'mysql';
				$host = isset( $info['host'] ) ? $info['host'] : '[unknown]';
				wfDebug( __CLASS__ . ": connecting to $host\n" );
				$db = DatabaseBase::factory( $type, $info );
				$db->clearFlag( DBO_TRX );
			} else {
				/*
				 * We must keep a separate connection to MySQL in order to avoid deadlocks
				 * However, SQLite has an opposite behavior. And PostgreSQL needs to know
				 * if we are in transaction or no
				 */
				if ( wfGetDB( DB_MASTER )->getType() == 'mysql' ) {
					$this->lb = wfGetLBFactory()->newMainLB();
					$db = $this->lb->getConnection( DB_MASTER );
					$db->clearFlag( DBO_TRX ); // auto-commit mode
				} else {
					$db = wfGetDB( DB_MASTER );
				}
			}
			if ( $wgDebugDBTransactions ) {
				wfDebug( sprintf( "Connection %s will be used for SqlBagOStuff\n", $db ) );
			}
			$this->conns[$serverIndex] = $db;
		}

		return $this->conns[$serverIndex];
	}

	/**
	 * Get the server index and table name for a given key
	 * @param $key string
	 * @return Array: server index and table name
	 */
	protected function getTableByKey( $key ) {
		if ( $this->shards > 1 ) {
			$hash = hexdec( substr( md5( $key ), 0, 8 ) ) & 0x7fffffff;
			$tableIndex = $hash % $this->shards;
		} else {
			$tableIndex = 0;
		}
		if ( $this->numServers > 1 ) {
			$sortedServers = $this->serverNames;
			ArrayUtils::consistentHashSort( $sortedServers, $key );
			reset( $sortedServers );
			$serverIndex = key( $sortedServers );
		} else {
			$serverIndex = 0;
		}
		return array( $serverIndex, $this->getTableNameByShard( $tableIndex ) );
	}

	/**
	 * Get the table name for a given shard index
	 * @param $index int
	 * @return string
	 */
	protected function getTableNameByShard( $index ) {
		if ( $this->shards > 1 ) {
			$decimals = strlen( $this->shards - 1 );
			return $this->tableName .
				sprintf( "%0{$decimals}d", $index );
		} else {
			return $this->tableName;
		}
	}

	/**
	 * @param $key string
	 * @param $casToken[optional] mixed
	 * @return mixed
	 */
	public function get( $key, &$casToken = null ) {
		$values = $this->getMulti( array( $key ) );
		if ( array_key_exists( $key, $values ) ) {
			$casToken = $values[$key];
			return $values[$key];
		}
		return false;
	}

	/**
	 * @param $keys array
	 * @return Array
	 */
	public function getMulti( array $keys ) {
		$values = array(); // array of (key => value)

		$keysByTable = array();
		foreach ( $keys as $key ) {
			list( $serverIndex, $tableName ) = $this->getTableByKey( $key );
			$keysByTable[$serverIndex][$tableName][] = $key;
		}

		$this->garbageCollect(); // expire old entries if any

		$dataRows = array();
		foreach ( $keysByTable as $serverIndex => $serverKeys ) {
			try {
				$db = $this->getDB( $serverIndex );
				foreach ( $serverKeys as $tableName => $tableKeys ) {
					$res = $db->select( $tableName,
						array( 'keyname', 'value', 'exptime' ),
						array( 'keyname' => $tableKeys ),
						__METHOD__ );
					foreach ( $res as $row ) {
						$row->serverIndex = $serverIndex;
						$row->tableName = $tableName;
						$dataRows[$row->keyname] = $row;
					}
				}
			} catch ( DBError $e ) {
				$this->handleReadError( $e, $serverIndex );
			}
		}

		foreach ( $keys as $key ) {
			if ( isset( $dataRows[$key] ) ) { // HIT?
				$row = $dataRows[$key];
				$this->debug( "get: retrieved data; expiry time is " . $row->exptime );
				try {
					$db = $this->getDB( $row->serverIndex );
					if ( $this->isExpired( $db, $row->exptime ) ) { // MISS
						$this->debug( "get: key has expired, deleting" );
						$db->begin( __METHOD__ );
						# Put the expiry time in the WHERE condition to avoid deleting a
						# newly-inserted value
						$db->delete( $row->tableName,
							array( 'keyname' => $key, 'exptime' => $row->exptime ),
							__METHOD__ );
						$db->commit( __METHOD__ );
						$values[$key] = false;
					} else { // HIT
						$values[$key] = $this->unserialize( $db->decodeBlob( $row->value ) );
					}
				} catch ( DBQueryError $e ) {
					$this->handleWriteError( $e, $row->serverIndex );
				}
			} else { // MISS
				$values[$key] = false;
				$this->debug( 'get: no matching rows' );
			}
		}

		return $values;
	}

	/**
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime int
	 * @return bool
	 */
	public function set( $key, $value, $exptime = 0 ) {
		list( $serverIndex, $tableName ) = $this->getTableByKey( $key );
		try {
			$db = $this->getDB( $serverIndex );
			$exptime = intval( $exptime );

			if ( $exptime < 0 ) {
				$exptime = 0;
			}

			if ( $exptime == 0 ) {
				$encExpiry = $this->getMaxDateTime( $db );
			} else {
				if ( $exptime < 3.16e8 ) { # ~10 years
					$exptime += time();
				}

				$encExpiry = $db->timestamp( $exptime );
			}
			$db->begin( __METHOD__ );
			// (bug 24425) use a replace if the db supports it instead of
			// delete/insert to avoid clashes with conflicting keynames
			$db->replace(
				$tableName,
				array( 'keyname' ),
				array(
					'keyname' => $key,
					'value' => $db->encodeBlob( $this->serialize( $value ) ),
					'exptime' => $encExpiry
				), __METHOD__ );
			$db->commit( __METHOD__ );
		} catch ( DBError $e ) {
			$this->handleWriteError( $e, $serverIndex );
			return false;
		}

		return true;
	}

	/**
	 * @param $casToken mixed
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime int
	 * @return bool
	 */
	public function cas( $casToken, $key, $value, $exptime = 0 ) {
		list( $serverIndex, $tableName ) = $this->getTableByKey( $key );
		try {
			$db = $this->getDB( $serverIndex );
			$exptime = intval( $exptime );

			if ( $exptime < 0 ) {
				$exptime = 0;
			}

			if ( $exptime == 0 ) {
				$encExpiry = $this->getMaxDateTime( $db );
			} else {
				if ( $exptime < 3.16e8 ) { # ~10 years
					$exptime += time();
				}
				$encExpiry = $db->timestamp( $exptime );
			}
			$db->begin( __METHOD__ );
			// (bug 24425) use a replace if the db supports it instead of
			// delete/insert to avoid clashes with conflicting keynames
			$db->update(
				$tableName,
				array(
					'keyname' => $key,
					'value' => $db->encodeBlob( $this->serialize( $value ) ),
					'exptime' => $encExpiry
				),
				array(
					'keyname' => $key,
					'value' => $db->encodeBlob( $this->serialize( $casToken ) )
				),
				__METHOD__
			);
			$db->commit( __METHOD__ );
		} catch ( DBQueryError $e ) {
			$this->handleWriteError( $e, $serverIndex );

			return false;
		}

		return (bool)$db->affectedRows();
	}

	/**
	 * @param $key string
	 * @param $time int
	 * @return bool
	 */
	public function delete( $key, $time = 0 ) {
		list( $serverIndex, $tableName ) = $this->getTableByKey( $key );
		try {
			$db = $this->getDB( $serverIndex );
			$db->begin( __METHOD__ );
			$db->delete(
				$tableName,
				array( 'keyname' => $key ),
				__METHOD__ );
			$db->commit( __METHOD__ );
		} catch ( DBError $e ) {
			$this->handleWriteError( $e, $serverIndex );
			return false;
		}

		return true;
	}

	/**
	 * @param $key string
	 * @param $step int
	 * @return int|null
	 */
	public function incr( $key, $step = 1 ) {
		list( $serverIndex, $tableName ) = $this->getTableByKey( $key );
		try {
			$db = $this->getDB( $serverIndex );
			$step = intval( $step );
			$db->begin( __METHOD__ );
			$row = $db->selectRow(
				$tableName,
				array( 'value', 'exptime' ),
				array( 'keyname' => $key ),
				__METHOD__,
				array( 'FOR UPDATE' ) );
			if ( $row === false ) {
				// Missing
				$db->commit( __METHOD__ );

				return null;
			}
			$db->delete( $tableName, array( 'keyname' => $key ), __METHOD__ );
			if ( $this->isExpired( $db, $row->exptime ) ) {
				// Expired, do not reinsert
				$db->commit( __METHOD__ );

				return null;
			}

			$oldValue = intval( $this->unserialize( $db->decodeBlob( $row->value ) ) );
			$newValue = $oldValue + $step;
			$db->insert( $tableName,
				array(
					'keyname' => $key,
					'value' => $db->encodeBlob( $this->serialize( $newValue ) ),
					'exptime' => $row->exptime
				), __METHOD__, 'IGNORE' );

			if ( $db->affectedRows() == 0 ) {
				// Race condition. See bug 28611
				$newValue = null;
			}
			$db->commit( __METHOD__ );
		} catch ( DBError $e ) {
			$this->handleWriteError( $e, $serverIndex );
			return null;
		}

		return $newValue;
	}

	/**
	 * @param $exptime string
	 * @return bool
	 */
	protected function isExpired( $db, $exptime ) {
		return $exptime != $this->getMaxDateTime( $db ) && wfTimestamp( TS_UNIX, $exptime ) < time();
	}

	/**
	 * @return string
	 */
	protected function getMaxDateTime( $db ) {
		if ( time() > 0x7fffffff ) {
			return $db->timestamp( 1 << 62 );
		} else {
			return $db->timestamp( 0x7fffffff );
		}
	}

	protected function garbageCollect() {
		if ( !$this->purgePeriod ) {
			// Disabled
			return;
		}
		// Only purge on one in every $this->purgePeriod requests.
		if ( $this->purgePeriod !== 1 && mt_rand( 0, $this->purgePeriod - 1 ) ) {
			return;
		}
		$now = time();
		// Avoid repeating the delete within a few seconds
		if ( $now > ( $this->lastExpireAll + 1 ) ) {
			$this->lastExpireAll = $now;
			$this->expireAll();
		}
	}

	public function expireAll() {
		$this->deleteObjectsExpiringBefore( wfTimestampNow() );
	}

	/**
	 * Delete objects from the database which expire before a certain date.
	 * @param $timestamp string
	 * @param $progressCallback bool|callback
	 * @return bool
	 */
	public function deleteObjectsExpiringBefore( $timestamp, $progressCallback = false ) {
		for ( $serverIndex = 0; $serverIndex < $this->numServers; $serverIndex++ ) {
			try {
				$db = $this->getDB( $serverIndex );
				$dbTimestamp = $db->timestamp( $timestamp );
				$totalSeconds = false;
				$baseConds = array( 'exptime < ' . $db->addQuotes( $dbTimestamp ) );
				for ( $i = 0; $i < $this->shards; $i++ ) {
					$maxExpTime = false;
					while ( true ) {
						$conds = $baseConds;
						if ( $maxExpTime !== false ) {
							$conds[] = 'exptime > ' . $db->addQuotes( $maxExpTime );
						}
						$rows = $db->select(
							$this->getTableNameByShard( $i ),
							array( 'keyname', 'exptime' ),
							$conds,
							__METHOD__,
							array( 'LIMIT' => 100, 'ORDER BY' => 'exptime' ) );
						if ( !$rows->numRows() ) {
							break;
						}
						$keys = array();
						$row = $rows->current();
						$minExpTime = $row->exptime;
						if ( $totalSeconds === false ) {
							$totalSeconds = wfTimestamp( TS_UNIX, $timestamp )
								- wfTimestamp( TS_UNIX, $minExpTime );
						}
						foreach ( $rows as $row ) {
							$keys[] = $row->keyname;
							$maxExpTime = $row->exptime;
						}

						$db->begin( __METHOD__ );
						$db->delete(
							$this->getTableNameByShard( $i ),
							array(
								'exptime >= ' . $db->addQuotes( $minExpTime ),
								'exptime < ' . $db->addQuotes( $dbTimestamp ),
								'keyname' => $keys
							),
							__METHOD__ );
						$db->commit( __METHOD__ );

						if ( $progressCallback ) {
							if ( intval( $totalSeconds ) === 0 ) {
								$percent = 0;
							} else {
								$remainingSeconds = wfTimestamp( TS_UNIX, $timestamp )
									- wfTimestamp( TS_UNIX, $maxExpTime );
								if ( $remainingSeconds > $totalSeconds ) {
									$totalSeconds = $remainingSeconds;
								}
								$percent = ( $i + $remainingSeconds / $totalSeconds )
									/ $this->shards * 100;
							}
							$percent = ( $percent / $this->numServers )
								+ ( $serverIndex / $this->numServers * 100 );
							call_user_func( $progressCallback, $percent );
						}
					}
				}
			} catch ( DBError $e ) {
				$this->handleWriteError( $e, $serverIndex );
				return false;
			}
		}
		return true;
	}

	public function deleteAll() {
		for ( $serverIndex = 0; $serverIndex < $this->numServers; $serverIndex++ ) {
			try {
				$db = $this->getDB( $serverIndex );
				for ( $i = 0; $i < $this->shards; $i++ ) {
					$db->begin( __METHOD__ );
					$db->delete( $this->getTableNameByShard( $i ), '*', __METHOD__ );
					$db->commit( __METHOD__ );
				}
			} catch ( DBError $e ) {
				$this->handleWriteError( $e, $serverIndex );
				return false;
			}
		}
		return true;
	}

	/**
	 * Serialize an object and, if possible, compress the representation.
	 * On typical message and page data, this can provide a 3X decrease
	 * in storage requirements.
	 *
	 * @param $data mixed
	 * @return string
	 */
	protected function serialize( &$data ) {
		$serial = serialize( $data );

		if ( function_exists( 'gzdeflate' ) ) {
			return gzdeflate( $serial );
		} else {
			return $serial;
		}
	}

	/**
	 * Unserialize and, if necessary, decompress an object.
	 * @param $serial string
	 * @return mixed
	 */
	protected function unserialize( $serial ) {
		if ( function_exists( 'gzinflate' ) ) {
			wfSuppressWarnings();
			$decomp = gzinflate( $serial );
			wfRestoreWarnings();

			if ( false !== $decomp ) {
				$serial = $decomp;
			}
		}

		$ret = unserialize( $serial );

		return $ret;
	}

	/**
	 * Handle a DBError which occurred during a read operation.
	 */
	protected function handleReadError( DBError $exception, $serverIndex ) {
		if ( $exception instanceof DBConnectionError ) {
			$this->markServerDown( $exception, $serverIndex );
		}
		wfDebugLog( 'SQLBagOStuff', "DBError: {$exception->getMessage()}" );
		if ( $exception instanceof DBConnectionError ) {
			wfDebug( __METHOD__ . ": ignoring connection error\n" );
		} else {
			wfDebug( __METHOD__ . ": ignoring query error\n" );
		}
	}

	/**
	 * Handle a DBQueryError which occurred during a write operation.
	 */
	protected function handleWriteError( DBError $exception, $serverIndex ) {
		if ( $exception instanceof DBConnectionError ) {
			$this->markServerDown( $exception, $serverIndex );
		}
		if ( $exception->db && $exception->db->wasReadOnlyError() ) {
			try {
				$exception->db->rollback( __METHOD__ );
			} catch ( DBError $e ) {}
		}
		wfDebugLog( 'SQLBagOStuff', "DBError: {$exception->getMessage()}" );
		if ( $exception instanceof DBConnectionError ) {
			wfDebug( __METHOD__ . ": ignoring connection error\n" );
		} else {
			wfDebug( __METHOD__ . ": ignoring query error\n" );
		}
	}

	/**
	 * Mark a server down due to a DBConnectionError exception
	 */
	protected function markServerDown( $exception, $serverIndex ) {
		if ( isset( $this->connFailureTimes[$serverIndex] ) ) {
			if ( time() - $this->connFailureTimes[$serverIndex] >= 60 ) {
				unset( $this->connFailureTimes[$serverIndex] );
				unset( $this->connFailureErrors[$serverIndex] );
			} else {
				wfDebug( __METHOD__ . ": Server #$serverIndex already down\n" );
				return;
			}
		}
		$now = time();
		wfDebug( __METHOD__ . ": Server #$serverIndex down until " . ( $now + 60 ) . "\n" );
		$this->connFailureTimes[$serverIndex] = $now;
		$this->connFailureErrors[$serverIndex] = $exception;
	}

	/**
	 * Create shard tables. For use from eval.php.
	 */
	public function createTables() {
		for ( $serverIndex = 0; $serverIndex < $this->numServers; $serverIndex++ ) {
			$db = $this->getDB( $serverIndex );
			if ( $db->getType() !== 'mysql'
				|| version_compare( $db->getServerVersion(), '4.1.0', '<' ) )
			{
				throw new MWException( __METHOD__ . ' is not supported on this DB server' );
			}

			for ( $i = 0; $i < $this->shards; $i++ ) {
				$db->begin( __METHOD__ );
				$db->query(
					'CREATE TABLE ' . $db->tableName( $this->getTableNameByShard( $i ) ) .
					' LIKE ' . $db->tableName( 'objectcache' ),
					__METHOD__ );
				$db->commit( __METHOD__ );
			}
		}
	}
}

/**
 * Backwards compatibility alias
 */
class MediaWikiBagOStuff extends SqlBagOStuff { }
