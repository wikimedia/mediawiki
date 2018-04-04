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

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBQueryError;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\LoadBalancer;
use Wikimedia\Rdbms\TransactionProfiler;
use Wikimedia\WaitConditionLoop;

/**
 * Class to store objects in the database
 *
 * @ingroup Cache
 */
class SqlBagOStuff extends BagOStuff {
	/** @var array[] (server index => server config) */
	protected $serverInfos;
	/** @var string[] (server index => tag/host name) */
	protected $serverTags;
	/** @var int */
	protected $numServers;
	/** @var int */
	protected $lastExpireAll = 0;
	/** @var int */
	protected $purgePeriod = 100;
	/** @var int */
	protected $shards = 1;
	/** @var string */
	protected $tableName = 'objectcache';
	/** @var bool */
	protected $replicaOnly = false;
	/** @var int */
	protected $syncTimeout = 3;

	/** @var LoadBalancer|null */
	protected $separateMainLB;
	/** @var array */
	protected $conns;
	/** @var array UNIX timestamps */
	protected $connFailureTimes = [];
	/** @var array Exceptions */
	protected $connFailureErrors = [];

	/**
	 * Constructor. Parameters are:
	 *   - server:      A server info structure in the format required by each
	 *                  element in $wgDBServers.
	 *
	 *   - servers:     An array of server info structures describing a set of database servers
	 *                  to distribute keys to. If this is specified, the "server" option will be
	 *                  ignored. If string keys are used, then they will be used for consistent
	 *                  hashing *instead* of the host name (from the server config). This is useful
	 *                  when a cluster is replicated to another site (with different host names)
	 *                  but each server has a corresponding replica in the other cluster.
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
	 *   - slaveOnly:   Whether to only use replica DBs and avoid triggering
	 *                  garbage collection logic of expired items. This only
	 *                  makes sense if the primary DB is used and only if get()
	 *                  calls will be used. This is used by ReplicatedBagOStuff.
	 *   - syncTimeout: Max seconds to wait for replica DBs to catch up for WRITE_SYNC.
	 *
	 * @param array $params
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		$this->attrMap[self::ATTR_EMULATION] = self::QOS_EMULATION_SQL;
		$this->attrMap[self::ATTR_SYNCWRITES] = self::QOS_SYNCWRITES_NONE;

		if ( isset( $params['servers'] ) ) {
			$this->serverInfos = [];
			$this->serverTags = [];
			$this->numServers = count( $params['servers'] );
			$index = 0;
			foreach ( $params['servers'] as $tag => $info ) {
				$this->serverInfos[$index] = $info;
				if ( is_string( $tag ) ) {
					$this->serverTags[$index] = $tag;
				} else {
					$this->serverTags[$index] = isset( $info['host'] ) ? $info['host'] : "#$index";
				}
				++$index;
			}
		} elseif ( isset( $params['server'] ) ) {
			$this->serverInfos = [ $params['server'] ];
			$this->numServers = count( $this->serverInfos );
		} else {
			// Default to using the main wiki's database servers
			$this->serverInfos = false;
			$this->numServers = 1;
			$this->attrMap[self::ATTR_SYNCWRITES] = self::QOS_SYNCWRITES_BE;
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
		if ( isset( $params['syncTimeout'] ) ) {
			$this->syncTimeout = $params['syncTimeout'];
		}
		$this->replicaOnly = !empty( $params['slaveOnly'] );
	}

	/**
	 * Get a connection to the specified database
	 *
	 * @param int $serverIndex
	 * @return Database
	 * @throws MWException
	 */
	protected function getDB( $serverIndex ) {
		if ( !isset( $this->conns[$serverIndex] ) ) {
			if ( $serverIndex >= $this->numServers ) {
				throw new MWException( __METHOD__ . ": Invalid server index \"$serverIndex\"" );
			}

			# Don't keep timing out trying to connect for each call if the DB is down
			if ( isset( $this->connFailureErrors[$serverIndex] )
				&& ( time() - $this->connFailureTimes[$serverIndex] ) < 60
			) {
				throw $this->connFailureErrors[$serverIndex];
			}

			if ( $this->serverInfos ) {
				// Use custom database defined by server connection info
				$info = $this->serverInfos[$serverIndex];
				$type = isset( $info['type'] ) ? $info['type'] : 'mysql';
				$host = isset( $info['host'] ) ? $info['host'] : '[unknown]';
				$this->logger->debug( __CLASS__ . ": connecting to $host" );
				// Use a blank trx profiler to ignore expections as this is a cache
				$info['trxProfiler'] = new TransactionProfiler();
				$db = Database::factory( $type, $info );
				$db->clearFlag( DBO_TRX ); // auto-commit mode
			} else {
				// Use the main LB database
				$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
				$index = $this->replicaOnly ? DB_REPLICA : DB_MASTER;
				if ( $lb->getServerType( $lb->getWriterIndex() ) !== 'sqlite' ) {
					// Keep a separate connection to avoid contention and deadlocks
					$db = $lb->getConnection( $index, [], false, $lb::CONN_TRX_AUTOCOMMIT );
					// @TODO: Use a blank trx profiler to ignore expections as this is a cache
				} else {
					// However, SQLite has the opposite behavior due to DB-level locking.
					// Stock sqlite MediaWiki installs use a separate sqlite cache DB instead.
					$db = $lb->getConnection( $index );
				}
			}

			$this->logger->debug( sprintf( "Connection %s will be used for SqlBagOStuff", $db ) );
			$this->conns[$serverIndex] = $db;
		}

		return $this->conns[$serverIndex];
	}

	/**
	 * Get the server index and table name for a given key
	 * @param string $key
	 * @return array Server index and table name
	 */
	protected function getTableByKey( $key ) {
		if ( $this->shards > 1 ) {
			$hash = hexdec( substr( md5( $key ), 0, 8 ) ) & 0x7fffffff;
			$tableIndex = $hash % $this->shards;
		} else {
			$tableIndex = 0;
		}
		if ( $this->numServers > 1 ) {
			$sortedServers = $this->serverTags;
			ArrayUtils::consistentHashSort( $sortedServers, $key );
			reset( $sortedServers );
			$serverIndex = key( $sortedServers );
		} else {
			$serverIndex = 0;
		}
		return [ $serverIndex, $this->getTableNameByShard( $tableIndex ) ];
	}

	/**
	 * Get the table name for a given shard index
	 * @param int $index
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

	protected function doGet( $key, $flags = 0 ) {
		$casToken = null;

		return $this->getWithToken( $key, $casToken, $flags );
	}

	protected function getWithToken( $key, &$casToken, $flags = 0 ) {
		$values = $this->getMulti( [ $key ] );
		if ( array_key_exists( $key, $values ) ) {
			$casToken = $values[$key];
			return $values[$key];
		}
		return false;
	}

	public function getMulti( array $keys, $flags = 0 ) {
		$values = []; // array of (key => value)

		$keysByTable = [];
		foreach ( $keys as $key ) {
			list( $serverIndex, $tableName ) = $this->getTableByKey( $key );
			$keysByTable[$serverIndex][$tableName][] = $key;
		}

		$this->garbageCollect(); // expire old entries if any

		$dataRows = [];
		foreach ( $keysByTable as $serverIndex => $serverKeys ) {
			try {
				$db = $this->getDB( $serverIndex );
				foreach ( $serverKeys as $tableName => $tableKeys ) {
					$res = $db->select( $tableName,
						[ 'keyname', 'value', 'exptime' ],
						[ 'keyname' => $tableKeys ],
						__METHOD__,
						// Approximate write-on-the-fly BagOStuff API via blocking.
						// This approximation fails if a ROLLBACK happens (which is rare).
						// We do not want to flush the TRX as that can break callers.
						$db->trxLevel() ? [ 'LOCK IN SHARE MODE' ] : []
					);
					if ( $res === false ) {
						continue;
					}
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
				$db = null;
				try {
					$db = $this->getDB( $row->serverIndex );
					if ( $this->isExpired( $db, $row->exptime ) ) { // MISS
						$this->debug( "get: key has expired" );
					} else { // HIT
						$values[$key] = $this->unserialize( $db->decodeBlob( $row->value ) );
					}
				} catch ( DBQueryError $e ) {
					$this->handleWriteError( $e, $db, $row->serverIndex );
				}
			} else { // MISS
				$this->debug( 'get: no matching rows' );
			}
		}

		return $values;
	}

	public function setMulti( array $data, $expiry = 0 ) {
		$keysByTable = [];
		foreach ( $data as $key => $value ) {
			list( $serverIndex, $tableName ) = $this->getTableByKey( $key );
			$keysByTable[$serverIndex][$tableName][] = $key;
		}

		$this->garbageCollect(); // expire old entries if any

		$result = true;
		$exptime = (int)$expiry;
		foreach ( $keysByTable as $serverIndex => $serverKeys ) {
			$db = null;
			try {
				$db = $this->getDB( $serverIndex );
			} catch ( DBError $e ) {
				$this->handleWriteError( $e, $db, $serverIndex );
				$result = false;
				continue;
			}

			if ( $exptime < 0 ) {
				$exptime = 0;
			}

			if ( $exptime == 0 ) {
				$encExpiry = $this->getMaxDateTime( $db );
			} else {
				$exptime = $this->convertExpiry( $exptime );
				$encExpiry = $db->timestamp( $exptime );
			}
			foreach ( $serverKeys as $tableName => $tableKeys ) {
				$rows = [];
				foreach ( $tableKeys as $key ) {
					$rows[] = [
						'keyname' => $key,
						'value' => $db->encodeBlob( $this->serialize( $data[$key] ) ),
						'exptime' => $encExpiry,
					];
				}

				try {
					$db->replace(
						$tableName,
						[ 'keyname' ],
						$rows,
						__METHOD__
					);
				} catch ( DBError $e ) {
					$this->handleWriteError( $e, $db, $serverIndex );
					$result = false;
				}

			}

		}

		return $result;
	}

	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		$ok = $this->setMulti( [ $key => $value ], $exptime );
		if ( ( $flags & self::WRITE_SYNC ) == self::WRITE_SYNC ) {
			$ok = $this->waitForReplication() && $ok;
		}

		return $ok;
	}

	protected function cas( $casToken, $key, $value, $exptime = 0 ) {
		list( $serverIndex, $tableName ) = $this->getTableByKey( $key );
		$db = null;
		try {
			$db = $this->getDB( $serverIndex );
			$exptime = intval( $exptime );

			if ( $exptime < 0 ) {
				$exptime = 0;
			}

			if ( $exptime == 0 ) {
				$encExpiry = $this->getMaxDateTime( $db );
			} else {
				$exptime = $this->convertExpiry( $exptime );
				$encExpiry = $db->timestamp( $exptime );
			}
			// (T26425) use a replace if the db supports it instead of
			// delete/insert to avoid clashes with conflicting keynames
			$db->update(
				$tableName,
				[
					'keyname' => $key,
					'value' => $db->encodeBlob( $this->serialize( $value ) ),
					'exptime' => $encExpiry
				],
				[
					'keyname' => $key,
					'value' => $db->encodeBlob( $this->serialize( $casToken ) )
				],
				__METHOD__
			);
		} catch ( DBQueryError $e ) {
			$this->handleWriteError( $e, $db, $serverIndex );

			return false;
		}

		return (bool)$db->affectedRows();
	}

	public function delete( $key ) {
		list( $serverIndex, $tableName ) = $this->getTableByKey( $key );
		$db = null;
		try {
			$db = $this->getDB( $serverIndex );
			$db->delete(
				$tableName,
				[ 'keyname' => $key ],
				__METHOD__ );
		} catch ( DBError $e ) {
			$this->handleWriteError( $e, $db, $serverIndex );
			return false;
		}

		return true;
	}

	public function incr( $key, $step = 1 ) {
		list( $serverIndex, $tableName ) = $this->getTableByKey( $key );
		$db = null;
		try {
			$db = $this->getDB( $serverIndex );
			$step = intval( $step );
			$row = $db->selectRow(
				$tableName,
				[ 'value', 'exptime' ],
				[ 'keyname' => $key ],
				__METHOD__,
				[ 'FOR UPDATE' ] );
			if ( $row === false ) {
				// Missing

				return null;
			}
			$db->delete( $tableName, [ 'keyname' => $key ], __METHOD__ );
			if ( $this->isExpired( $db, $row->exptime ) ) {
				// Expired, do not reinsert

				return null;
			}

			$oldValue = intval( $this->unserialize( $db->decodeBlob( $row->value ) ) );
			$newValue = $oldValue + $step;
			$db->insert( $tableName,
				[
					'keyname' => $key,
					'value' => $db->encodeBlob( $this->serialize( $newValue ) ),
					'exptime' => $row->exptime
				], __METHOD__, 'IGNORE' );

			if ( $db->affectedRows() == 0 ) {
				// Race condition. See T30611
				$newValue = null;
			}
		} catch ( DBError $e ) {
			$this->handleWriteError( $e, $db, $serverIndex );
			return null;
		}

		return $newValue;
	}

	public function merge( $key, callable $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		$ok = $this->mergeViaCas( $key, $callback, $exptime, $attempts );
		if ( ( $flags & self::WRITE_SYNC ) == self::WRITE_SYNC ) {
			$ok = $this->waitForReplication() && $ok;
		}

		return $ok;
	}

	public function changeTTL( $key, $expiry = 0 ) {
		list( $serverIndex, $tableName ) = $this->getTableByKey( $key );
		$db = null;
		try {
			$db = $this->getDB( $serverIndex );
			$db->update(
				$tableName,
				[ 'exptime' => $db->timestamp( $this->convertExpiry( $expiry ) ) ],
				[ 'keyname' => $key, 'exptime > ' . $db->addQuotes( $db->timestamp( time() ) ) ],
				__METHOD__
			);
			if ( $db->affectedRows() == 0 ) {
				return false;
			}
		} catch ( DBError $e ) {
			$this->handleWriteError( $e, $db, $serverIndex );
			return false;
		}

		return true;
	}

	/**
	 * @param IDatabase $db
	 * @param string $exptime
	 * @return bool
	 */
	protected function isExpired( $db, $exptime ) {
		return $exptime != $this->getMaxDateTime( $db ) && wfTimestamp( TS_UNIX, $exptime ) < time();
	}

	/**
	 * @param IDatabase $db
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
		if ( !$this->purgePeriod || $this->replicaOnly ) {
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
	 * @param string $timestamp
	 * @param bool|callable $progressCallback
	 * @return bool
	 */
	public function deleteObjectsExpiringBefore( $timestamp, $progressCallback = false ) {
		for ( $serverIndex = 0; $serverIndex < $this->numServers; $serverIndex++ ) {
			$db = null;
			try {
				$db = $this->getDB( $serverIndex );
				$dbTimestamp = $db->timestamp( $timestamp );
				$totalSeconds = false;
				$baseConds = [ 'exptime < ' . $db->addQuotes( $dbTimestamp ) ];
				for ( $i = 0; $i < $this->shards; $i++ ) {
					$maxExpTime = false;
					while ( true ) {
						$conds = $baseConds;
						if ( $maxExpTime !== false ) {
							$conds[] = 'exptime >= ' . $db->addQuotes( $maxExpTime );
						}
						$rows = $db->select(
							$this->getTableNameByShard( $i ),
							[ 'keyname', 'exptime' ],
							$conds,
							__METHOD__,
							[ 'LIMIT' => 100, 'ORDER BY' => 'exptime' ] );
						if ( $rows === false || !$rows->numRows() ) {
							break;
						}
						$keys = [];
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

						$db->delete(
							$this->getTableNameByShard( $i ),
							[
								'exptime >= ' . $db->addQuotes( $minExpTime ),
								'exptime < ' . $db->addQuotes( $dbTimestamp ),
								'keyname' => $keys
							],
							__METHOD__ );

						if ( $progressCallback ) {
							if ( intval( $totalSeconds ) === 0 ) {
								$percent = 0;
							} else {
								$remainingSeconds = wfTimestamp( TS_UNIX, $timestamp )
									- wfTimestamp( TS_UNIX, $maxExpTime );
								if ( $remainingSeconds > $totalSeconds ) {
									$totalSeconds = $remainingSeconds;
								}
								$processedSeconds = $totalSeconds - $remainingSeconds;
								$percent = ( $i + $processedSeconds / $totalSeconds )
									/ $this->shards * 100;
							}
							$percent = ( $percent / $this->numServers )
								+ ( $serverIndex / $this->numServers * 100 );
							call_user_func( $progressCallback, $percent );
						}
					}
				}
			} catch ( DBError $e ) {
				$this->handleWriteError( $e, $db, $serverIndex );
				return false;
			}
		}
		return true;
	}

	/**
	 * Delete content of shard tables in every server.
	 * Return true if the operation is successful, false otherwise.
	 * @return bool
	 */
	public function deleteAll() {
		for ( $serverIndex = 0; $serverIndex < $this->numServers; $serverIndex++ ) {
			$db = null;
			try {
				$db = $this->getDB( $serverIndex );
				for ( $i = 0; $i < $this->shards; $i++ ) {
					$db->delete( $this->getTableNameByShard( $i ), '*', __METHOD__ );
				}
			} catch ( DBError $e ) {
				$this->handleWriteError( $e, $db, $serverIndex );
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
	 * @param mixed &$data
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
	 * @param string $serial
	 * @return mixed
	 */
	protected function unserialize( $serial ) {
		if ( function_exists( 'gzinflate' ) ) {
			Wikimedia\suppressWarnings();
			$decomp = gzinflate( $serial );
			Wikimedia\restoreWarnings();

			if ( false !== $decomp ) {
				$serial = $decomp;
			}
		}

		$ret = unserialize( $serial );

		return $ret;
	}

	/**
	 * Handle a DBError which occurred during a read operation.
	 *
	 * @param DBError $exception
	 * @param int $serverIndex
	 */
	protected function handleReadError( DBError $exception, $serverIndex ) {
		if ( $exception instanceof DBConnectionError ) {
			$this->markServerDown( $exception, $serverIndex );
		}
		$this->logger->error( "DBError: {$exception->getMessage()}" );
		if ( $exception instanceof DBConnectionError ) {
			$this->setLastError( BagOStuff::ERR_UNREACHABLE );
			$this->logger->debug( __METHOD__ . ": ignoring connection error" );
		} else {
			$this->setLastError( BagOStuff::ERR_UNEXPECTED );
			$this->logger->debug( __METHOD__ . ": ignoring query error" );
		}
	}

	/**
	 * Handle a DBQueryError which occurred during a write operation.
	 *
	 * @param DBError $exception
	 * @param IDatabase|null $db DB handle or null if connection failed
	 * @param int $serverIndex
	 * @throws Exception
	 */
	protected function handleWriteError( DBError $exception, IDatabase $db = null, $serverIndex ) {
		if ( !$db ) {
			$this->markServerDown( $exception, $serverIndex );
		} elseif ( $db->wasReadOnlyError() ) {
			if ( $db->trxLevel() && $this->usesMainDB() ) {
				// Errors like deadlocks and connection drops already cause rollback.
				// For consistency, we have no choice but to throw an error and trigger
				// complete rollback if the main DB is also being used as the cache DB.
				throw $exception;
			}
		}

		$this->logger->error( "DBError: {$exception->getMessage()}" );
		if ( $exception instanceof DBConnectionError ) {
			$this->setLastError( BagOStuff::ERR_UNREACHABLE );
			$this->logger->debug( __METHOD__ . ": ignoring connection error" );
		} else {
			$this->setLastError( BagOStuff::ERR_UNEXPECTED );
			$this->logger->debug( __METHOD__ . ": ignoring query error" );
		}
	}

	/**
	 * Mark a server down due to a DBConnectionError exception
	 *
	 * @param DBError $exception
	 * @param int $serverIndex
	 */
	protected function markServerDown( DBError $exception, $serverIndex ) {
		unset( $this->conns[$serverIndex] ); // bug T103435

		if ( isset( $this->connFailureTimes[$serverIndex] ) ) {
			if ( time() - $this->connFailureTimes[$serverIndex] >= 60 ) {
				unset( $this->connFailureTimes[$serverIndex] );
				unset( $this->connFailureErrors[$serverIndex] );
			} else {
				$this->logger->debug( __METHOD__ . ": Server #$serverIndex already down" );
				return;
			}
		}
		$now = time();
		$this->logger->info( __METHOD__ . ": Server #$serverIndex down until " . ( $now + 60 ) );
		$this->connFailureTimes[$serverIndex] = $now;
		$this->connFailureErrors[$serverIndex] = $exception;
	}

	/**
	 * Create shard tables. For use from eval.php.
	 */
	public function createTables() {
		for ( $serverIndex = 0; $serverIndex < $this->numServers; $serverIndex++ ) {
			$db = $this->getDB( $serverIndex );
			if ( $db->getType() !== 'mysql' ) {
				throw new MWException( __METHOD__ . ' is not supported on this DB server' );
			}

			for ( $i = 0; $i < $this->shards; $i++ ) {
				$db->query(
					'CREATE TABLE ' . $db->tableName( $this->getTableNameByShard( $i ) ) .
					' LIKE ' . $db->tableName( 'objectcache' ),
					__METHOD__ );
			}
		}
	}

	/**
	 * @return bool Whether the main DB is used, e.g. wfGetDB( DB_MASTER )
	 */
	protected function usesMainDB() {
		return !$this->serverInfos;
	}

	protected function waitForReplication() {
		if ( !$this->usesMainDB() ) {
			// Custom DB server list; probably doesn't use replication
			return true;
		}

		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		if ( $lb->getServerCount() <= 1 ) {
			return true; // no replica DBs
		}

		// Main LB is used; wait for any replica DBs to catch up
		$masterPos = $lb->getMasterPos();
		if ( !$masterPos ) {
			return true; // not applicable
		}

		$loop = new WaitConditionLoop(
			function () use ( $lb, $masterPos ) {
				return $lb->waitForAll( $masterPos, 1 );
			},
			$this->syncTimeout,
			$this->busyCallbacks
		);

		return ( $loop->invoke() === $loop::CONDITION_REACHED );
	}
}
