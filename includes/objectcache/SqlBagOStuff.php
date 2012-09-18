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

	/**
	 * @var DatabaseBase
	 */
	var $db;
	var $serverInfo;
	var $lastExpireAll = 0;
	var $purgePeriod = 100;
	var $shards = 1;
	var $tableName = 'objectcache';

	protected $connFailureTime = 0; // UNIX timestamp
	protected $connFailureError; // exception

	/**
	 * Constructor. Parameters are:
	 *   - server:   A server info structure in the format required by each
	 *               element in $wgDBServers.
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
	 *   - shards:      The number of tables to use for data storage. If this is
	 *                  more than 1, table names will be formed in the style
	 *                  objectcacheNNN where NNN is the shard index, between 0 and
	 *                  shards-1. The number of digits will be the minimum number
	 *                  required to hold the largest shard index. Data will be
	 *                  distributed across all tables by key hash. This is for
	 *                  MySQL bugs 61735 and 61736.
	 *
	 * @param $params array
	 */
	public function __construct( $params ) {
		if ( isset( $params['server'] ) ) {
			$this->serverInfo = $params['server'];
			$this->serverInfo['load'] = 1;
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
	 * @return DatabaseBase
	 */
	protected function getDB() {
		global $wgDebugDBTransactions;

		# Don't keep timing out trying to connect for each call if the DB is down
		if ( $this->connFailureError && ( time() - $this->connFailureTime ) < 60 ) {
			throw $this->connFailureError;
		}

		if ( !isset( $this->db ) ) {
			# If server connection info was given, use that
			if ( $this->serverInfo ) {
				if ( $wgDebugDBTransactions ) {
					wfDebug( sprintf( "Using provided serverInfo for SqlBagOStuff\n" ) );
				}
				$this->lb = new LoadBalancer( array(
					'servers' => array( $this->serverInfo ) ) );
				$this->db = $this->lb->getConnection( DB_MASTER );
				$this->db->clearFlag( DBO_TRX );
			} else {
				/*
				 * We must keep a separate connection to MySQL in order to avoid deadlocks
				 * However, SQLite has an opposite behaviour. And PostgreSQL needs to know
				 * if we are in transaction or no
				 */
				if ( wfGetDB( DB_MASTER )->getType() == 'mysql' ) {
					$this->lb = wfGetLBFactory()->newMainLB();
					$this->db = $this->lb->getConnection( DB_MASTER );
					$this->db->clearFlag( DBO_TRX ); // auto-commit mode
				} else {
					$this->db = wfGetDB( DB_MASTER );
				}
			}
			if ( $wgDebugDBTransactions ) {
				wfDebug( sprintf( "Connection %s will be used for SqlBagOStuff\n", $this->db ) );
			}
		}

		return $this->db;
	}

	/**
	 * Get the table name for a given key
	 * @param $key string
	 * @return string
	 */
	protected function getTableByKey( $key ) {
		if ( $this->shards > 1 ) {
			$hash = hexdec( substr( md5( $key ), 0, 8 ) ) & 0x7fffffff;
			return $this->getTableByShard( $hash % $this->shards );
		} else {
			return $this->tableName;
		}
	}

	/**
	 * Get the table name for a given shard index
	 * @param $index int
	 * @return string
	 */
	protected function getTableByShard( $index ) {
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
	 * @return mixed
	 */
	public function get( $key ) {
		$values = $this->getMulti( array( $key ) );
		return array_key_exists( $key, $values ) ? $values[$key] : false;
	}

	/**
	 * @param $keys array
	 * @return Array
	 */
	public function getMulti( array $keys ) {
		$values = array(); // array of (key => value)

		try {
			$db = $this->getDB();
			$keysByTableName = array();
			foreach ( $keys as $key ) {
				$tableName = $this->getTableByKey( $key );
				if ( !isset( $keysByTableName[$tableName] ) ) {
					$keysByTableName[$tableName] = array();
				}
				$keysByTableName[$tableName][] = $key;
			}

			$this->garbageCollect(); // expire old entries if any

			$dataRows = array();
			foreach ( $keysByTableName as $tableName => $tableKeys ) {
				$res = $db->select( $tableName,
					array( 'keyname', 'value', 'exptime' ),
					array( 'keyname' => $tableKeys ),
					__METHOD__ );
				foreach ( $res as $row ) {
					$dataRows[$row->keyname] = $row;
				}
			}

			foreach ( $keys as $key ) {
				if ( isset( $dataRows[$key] ) ) { // HIT?
					$row = $dataRows[$key];
					$this->debug( "get: retrieved data; expiry time is " . $row->exptime );
					if ( $this->isExpired( $row->exptime ) ) { // MISS
						$this->debug( "get: key has expired, deleting" );
						try {
							$db->begin( __METHOD__ );
							# Put the expiry time in the WHERE condition to avoid deleting a
							# newly-inserted value
							$db->delete( $this->getTableByKey( $key ),
								array( 'keyname' => $key, 'exptime' => $row->exptime ),
								__METHOD__ );
							$db->commit( __METHOD__ );
						} catch ( DBQueryError $e ) {
							$this->handleWriteError( $e );
						}
						$values[$key] = false;
					} else { // HIT
						$values[$key] = $this->unserialize( $db->decodeBlob( $row->value ) );
					}
				} else { // MISS
					$values[$key] = false;
					$this->debug( 'get: no matching rows' );
				}
			}
		} catch ( DBError $e ) {
			$this->handleReadError( $e );
		};

		return $values;
	}

	/**
	 * @param $key string
	 * @param $value mixed
	 * @param $exptime int
	 * @return bool
	 */
	public function set( $key, $value, $exptime = 0 ) {
		try {
			$db = $this->getDB();
			$exptime = intval( $exptime );

			if ( $exptime < 0 ) {
				$exptime = 0;
			}

			if ( $exptime == 0 ) {
				$encExpiry = $this->getMaxDateTime();
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
				$this->getTableByKey( $key ),
				array( 'keyname' ),
				array(
					'keyname' => $key,
					'value' => $db->encodeBlob( $this->serialize( $value ) ),
					'exptime' => $encExpiry
				), __METHOD__ );
			$db->commit( __METHOD__ );
		} catch ( DBError $e ) {
			$this->handleWriteError( $e );
			return false;
		}

		return true;
	}

	/**
	 * @param $key string
	 * @param $time int
	 * @return bool
	 */
	public function delete( $key, $time = 0 ) {
		try {
			$db = $this->getDB();
			$db->begin( __METHOD__ );
			$db->delete(
				$this->getTableByKey( $key ),
				array( 'keyname' => $key ),
				__METHOD__ );
			$db->commit( __METHOD__ );
		} catch ( DBError $e ) {
			$this->handleWriteError( $e );
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
		try {
			$db = $this->getDB();
			$tableName = $this->getTableByKey( $key );
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
			if ( $this->isExpired( $row->exptime ) ) {
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
			$this->handleWriteError( $e );
			return null;
		}

		return $newValue;
	}

	/**
	 * @return Array
	 */
	public function keys() {
		$result = array();

		try {
			$db = $this->getDB();
			for ( $i = 0; $i < $this->shards; $i++ ) {
				$res = $db->select( $this->getTableByShard( $i ),
					array( 'keyname' ), false, __METHOD__ );
				foreach ( $res as $row ) {
					$result[] = $row->keyname;
				}
			}
		} catch ( DBError $e ) {
			$this->handleReadError( $e );
		}

		return $result;
	}

	/**
	 * @param $exptime string
	 * @return bool
	 */
	protected function isExpired( $exptime ) {
		return $exptime != $this->getMaxDateTime() && wfTimestamp( TS_UNIX, $exptime ) < time();
	}

	/**
	 * @return string
	 */
	protected function getMaxDateTime() {
		if ( time() > 0x7fffffff ) {
			return $this->getDB()->timestamp( 1 << 62 );
		} else {
			return $this->getDB()->timestamp( 0x7fffffff );
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
		try {
			$db = $this->getDB();
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
						$this->getTableByShard( $i ),
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
						$this->getTableByShard( $i ),
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
						call_user_func( $progressCallback, $percent );
					}
				}
			}
		} catch ( DBError $e ) {
			$this->handleWriteError( $e );
			return false;
		}

		return true;
	}

	public function deleteAll() {
		try {
			$db = $this->getDB();
			for ( $i = 0; $i < $this->shards; $i++ ) {
				$db->begin( __METHOD__ );
				$db->delete( $this->getTableByShard( $i ), '*', __METHOD__ );
				$db->commit( __METHOD__ );
			}
		} catch ( DBError $e ) {
			$this->handleWriteError( $e );
			return false;
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
	protected function handleReadError( DBError $exception ) {
		if ( $exception instanceof DBConnectionError ) {
			$this->connFailureTime  = time();
			$this->connFailureError = $exception;
		}
		wfDebugLog( 'SQLBagOStuff', "DBError: {$exception->getMessage()}" );
		if ( $this->db ) {
			wfDebug( __METHOD__ . ": ignoring query error\n" );
		} else {
			wfDebug( __METHOD__ . ": ignoring connection error\n" );
		}
	}

	/**
	 * Handle a DBQueryError which occurred during a write operation.
	 */
	protected function handleWriteError( DBError $exception ) {
		if ( $exception instanceof DBConnectionError ) {
			$this->connFailureTime  = time();
			$this->connFailureError = $exception;
		}
		if ( $this->db && $this->db->wasReadOnlyError() ) {
			try {
				$this->db->rollback( __METHOD__ );
			} catch ( DBError $e ) {}
		}
		wfDebugLog( 'SQLBagOStuff', "DBError: {$exception->getMessage()}" );
		if ( $this->db ) {
			wfDebug( __METHOD__ . ": ignoring query error\n" );
		} else {
			wfDebug( __METHOD__ . ": ignoring connection error\n" );
		}
	}

	/**
	 * Create shard tables. For use from eval.php.
	 */
	public function createTables() {
		$db = $this->getDB();
		if ( $db->getType() !== 'mysql'
			|| version_compare( $db->getServerVersion(), '4.1.0', '<' ) )
		{
			throw new MWException( __METHOD__ . ' is not supported on this DB server' );
		}

		for ( $i = 0; $i < $this->shards; $i++ ) {
			$db->begin( __METHOD__ );
			$db->query(
				'CREATE TABLE ' . $db->tableName( $this->getTableByShard( $i ) ) .
				' LIKE ' . $db->tableName( 'objectcache' ),
				__METHOD__ );
			$db->commit( __METHOD__ );
		}
	}
}

/**
 * Backwards compatibility alias
 */
class MediaWikiBagOStuff extends SqlBagOStuff { }

